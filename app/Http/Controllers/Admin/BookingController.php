<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Core\Booking;
use App\Models\Auth\Customer;
use App\Models\Core\Vehicle;
use App\Models\Auth\Driver;
use App\Models\Core\Transaction;
use App\Models\Core\Notification as NotificationModel;
use Illuminate\Http\Request;


class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::query()->with(['customer', 'vehicle', 'driver'])->latest()->paginate(5);
        $totalBookings = Booking::query()->count('*');
        $ongoingTrips = Booking::query()->where('status', 'ongoing')->count();
        
        return view('dashboardview.booking.index', compact('bookings', 'totalBookings', 'ongoingTrips'));
    }

    public function mapDashboard()
    {
        $bookings = Booking::query()->with(['customer', 'vehicle', 'driver'])->get();
        $activeDrivers = Driver::query()->where('driver_status', 'active')->count();
        return view('dashboardview.booking.map', compact('bookings', 'activeDrivers'));
    }


    public function mapDashboardData()
    {
        $drivers = Driver::with('vehicle')->get()->map(function ($driver) {
            return [
                'id' => $driver->id,
                'name' => $driver->full_name,
                'status' => $driver->driver_status,
                'lat' => $driver->current_lat ?? 16.8409,
                'lng' => $driver->current_lng ?? 96.1735,
                'vehicle' => $driver->vehicle ? $driver->vehicle->license_plate : 'No Vehicle'
            ];
        });

        $activeBookings = Booking::query()->whereIn('status', ['pending', 'confirmed', 'ongoing'], 'and', false)->get();

        return response()->json([
            'drivers' => $drivers,
            'bookings' => $activeBookings
        ]);
    }

    public function create()
    {
        $customers = Customer::query()->where('status', 'active')->get();
        $drivers = Driver::query()->where('driver_status', 'active')->get();
        $vehicles = Vehicle::query()->where('status', 'Available')->get();
        
        $baseFare = \App\Models\Core\Setting::get('base_fare', 2500);
        $pricePerKm = \App\Models\Core\Setting::get('price_per_km', 800);

        return view('dashboardview.booking.create', compact('customers', 'vehicles', 'drivers', 'baseFare', 'pricePerKm'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'pickup_location' => 'required|max:255',
            'pickup_lat' => 'nullable|numeric',
            'pickup_lng' => 'nullable|numeric',
            'dropoff_location' => 'required|max:255',
            'dropoff_lat' => 'nullable|numeric',
            'dropoff_lng' => 'nullable|numeric',
            'pickup_time' => 'nullable|date',
            'fare' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,ongoing,completed,cancelled',
            'notes' => 'nullable',
        ]);

        if ($request->vehicle_id) {
            $vehicle = Vehicle::query()->find($request->vehicle_id, ['*']);
            if ($vehicle && $vehicle->driver_id) {
                $validated['driver_id'] = $vehicle->driver_id;
            }
        }

        $booking = Booking::create($validated);

        // Send Notification
        NotificationModel::send(
            'New Booking Received',
            "A new ride has been booked by {$booking->customer->name} for " . number_format($booking->fare) . " MMK.",
            'info',
            route('bookings.index'),
            \App\Models\Auth\User::first(['*'])
        );


        return redirect()->route('bookings.index')->with('success', 'Booking created successfully.');
    }

    public function edit(Booking $booking)
    {
        $customers = Customer::all();
        $vehicles = Vehicle::all();
        $drivers = Driver::all();
        return view('dashboardview.booking.edit', compact('booking', 'customers', 'vehicles', 'drivers'));
    }


    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'pickup_location' => 'required|max:255',
            'pickup_lat' => 'nullable|numeric',
            'pickup_lng' => 'nullable|numeric',
            'dropoff_location' => 'required|max:255',
            'dropoff_lat' => 'nullable|numeric',
            'dropoff_lng' => 'nullable|numeric',
            'pickup_time' => 'nullable|date',
            'fare' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,ongoing,completed,cancelled',
            'notes' => 'nullable',
        ]);

        $oldStatus = $booking->status;
        $oldVehicleId = $booking->vehicle_id;

        $booking->update($validated);

        // Update Vehicle Status based on Booking Status
        if ($booking->vehicle_id) {
            if ($booking->status == 'completed' || $booking->status == 'cancelled') {
                Vehicle::query()->where('id', $booking->vehicle_id)->update(['status' => 'Available']);
            } elseif ($booking->status == 'ongoing' || $booking->status == 'confirmed') {
                Vehicle::query()->where('id', $booking->vehicle_id)->update(['status' => 'On Ride']);
            }
        }

        // If vehicle changed, reset old vehicle status
        if ($oldVehicleId && $oldVehicleId != $booking->vehicle_id) {
            Vehicle::query()->where('id', $oldVehicleId)->update(['status' => 'Available']);
        }

        return redirect()->route('bookings.index')->with('success', 'Booking updated.');
    }

    public function destroy(Booking $booking)
    {
        // If booking was ongoing, free up the vehicle
        if ($booking->vehicle_id && in_array($booking->status, ['ongoing', 'confirmed'])) {
            Vehicle::query()->where('id', $booking->vehicle_id)->update(['status' => 'Available']);
        }
        
        Booking::query()->where('id', $booking->id)->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking record deleted.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,ongoing,completed,cancelled',
        ]);

        $newStatus = $validated['status'];
        $oldStatus = $booking->status;

        // Simple validation for logical transitions
        if ($oldStatus == 'completed' || $oldStatus == 'cancelled') {
            return back()->with('error', 'Cannot change status of a finished or cancelled trip.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $booking->update(['status' => $newStatus]);

            // Transaction & Wallet Logic
            if ($newStatus == 'completed' && $oldStatus != 'completed') {
                $fare = $booking->fare;
                $rate = \App\Models\Core\Setting::get('commission_rate', 15) / 100; // Default to 15 if not set
                $commission = $fare * $rate;
                $driverAmount = $fare - $commission;

                Transaction::create([
                    'booking_id' => $booking->id,
                    'driver_id' => $booking->driver_id,
                    'customer_id' => $booking->customer_id,
                    'amount' => $fare,
                    'commission_amount' => $commission,
                    'driver_amount' => $driverAmount,
                    'payment_method' => 'Cash',
                    'type' => 'Ride Fare',
                    'status' => 'Completed',
                    'note' => 'Auto-generated on completion.'
                ]);

                if ($booking->driver_id) {
                    $driver = Driver::query()->find((int)$booking->driver_id);
                    if ($driver) {
                        $driver->wallet_balance -= $commission; // Cash: deduct commission
                        $driver->save();
                    }
                }

                // Send Notification
                NotificationModel::send(
                    'Trip Completed',
                    "Trip #{$booking->id} is completed. Commission of " . number_format($commission) . " MMK has been processed.",
                    'success',
                    route('transactions.index'),
                    \App\Models\Auth\User::first(['*'])
                );
            }

            // Automatically manage Vehicle Status
            if ($booking->vehicle_id) {
                if (in_array($newStatus, ['completed', 'cancelled'])) {
                    Vehicle::query()->where('id', $booking->vehicle_id)->update(['status' => 'Available']);
                } elseif (in_array($newStatus, ['confirmed', 'ongoing'])) {
                    Vehicle::query()->where('id', $booking->vehicle_id)->update(['status' => 'On Ride']);
                }
            }

            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Update failed: ' . $e->getMessage());
        }

        return back()->with('success', "Booking status updated to " . ucfirst($newStatus));
    }
}



