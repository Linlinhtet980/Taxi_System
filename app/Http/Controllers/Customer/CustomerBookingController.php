<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Core\Booking;
use App\Models\Auth\Driver;
use App\Models\Auth\Customer;
use App\Models\Core\Notification;
use App\Models\Core\Transaction;
use Illuminate\Http\Request;

class CustomerBookingController extends Controller
{
    public function index()
    {
        $customer = auth('customer')->user();
        
        // Get 4 online drivers
        $availableDrivers = Driver::query()->where('driver_status', 'active')
            ->with('vehicle')
            ->inRandomOrder()
            ->take(4)
            ->get();
            
        return view('customerview.booking', compact('customer', 'availableDrivers'));
    }

    public function dashboard()
    {
        $customer = auth('customer')->user();
        
        // Fetch the latest active booking (if any)
        $activeBooking = Booking::query()->where('customer_id', $customer->id)
            ->whereIn('status', ['pending', 'accepted', 'started'])
            ->with('driver.vehicle')
            ->latest()
            ->first();

        // Fetch last 2 completed/cancelled bookings for re-booking
        $recentBookings = Booking::query()->where('customer_id', $customer->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->with('driver.vehicle')
            ->latest()
            ->take(2)
            ->get();

        // Get unread notifications count
        $unreadNotificationsCount = Notification::query()->where('is_read', false)->count();

        // Determine greeting
        $hour = now()->hour;
        $greeting = "မင်္ဂလာပါ";
        if ($hour >= 5 && $hour < 12) $greeting = "မင်္ဂလာနံနက်ခင်းပါ";
        elseif ($hour >= 12 && $hour < 17) $greeting = "မင်္ဂလာနေ့ခင်းပါ";
        elseif ($hour >= 17 && $hour < 21) $greeting = "မင်္ဂလာညနေခင်းပါ";
        else $greeting = "မင်္ဂလာညချမ်းပါ";

        return view('customerview.dashboard', compact(
            'customer', 
            'activeBooking', 
            'recentBookings', 
            'greeting',
            'unreadNotificationsCount'
        ));
    }

    public function activities()
    {
        $customer = auth('customer')->user();
        $bookings = Booking::query()->where('customer_id', $customer->id)
            ->with('driver.vehicle')
            ->latest()
            ->paginate(10);
            
        return view('customerview.activities', compact('customer', 'bookings'));
    }

    public function profile()
    {
        $customer = auth('customer')->user();
        return view('customerview.settings', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $customer = auth('customer')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone,' . $customer->id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
        ];

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_profile_' . $customer->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/customers'), $filename);
            $data['profile_picture'] = 'uploads/customers/' . $filename;
        }

        Customer::where('id', $customer->id)->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function topupView()
    {
        $customer = auth('customer')->user();
        return view('customerview.topup', compact('customer'));
    }

    public function topupStore(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        $customer = auth('customer')->user();
        $amount = $request->amount;

        // Update customer wallet
        Customer::query()->where('id', $customer->id)->increment('wallet_balance', $amount);

        // Create Transaction Record
        Transaction::query()->create([
            'customer_id' => $customer->id,
            'amount' => $amount,
            'type' => 'Recharge',
            'status' => 'Completed',
            'note' => 'Demo Wallet Top-up',
            'payment_method' => 'Digital'
        ]);

        return redirect()->route('customer.dashboard')->with('success', number_format($amount) . ' Ks added to your wallet!');
    }

    public function onboarding()
    {
        return view('customerview.onboarding');
    }

    public function paymentSetup()
    {
        return view('customerview.payment_setup');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required',
            'driver_id' => 'required',
            'pickup_location' => 'required',
            'dropoff_location' => 'required',
            'fare' => 'required'
        ]);

        // [Logic Added] Prevent duplicate bookings within 2 minutes
        $existing = Booking::query()->where('customer_id', $request->customer_id)
            ->where('pickup_location', $request->pickup_location)
            ->where('dropoff_location', $request->dropoff_location)
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subMinutes(2))
            ->first();

        if ($existing) {
            return redirect()->route('customer.waiting', $existing->id)
                ->with('warning', 'Your previous booking is still being processed. Please wait.');
        }

        $driver = Driver::query()->find($request->driver_id);
        
        $booking = Booking::query()->create([
            'customer_id' => $request->customer_id,
            'driver_id' => $request->driver_id,
            'vehicle_id' => $driver->vehicle->id ?? null,
            'pickup_location' => $request->pickup_location,
            'dropoff_location' => $request->dropoff_location,
            'fare' => $request->fare,
            'status' => 'pending', 
            'payment_method' => $request->payment_method ?? 'cash',
            'payment_status' => $request->payment_method == 'wallet' ? 'paid' : 'unpaid',
            'pickup_lat' => $request->pickup_lat,
            'pickup_lng' => $request->pickup_lng,
            'dropoff_lat' => $request->dropoff_lat,
            'dropoff_lng' => $request->dropoff_lng,
        ]);

        // Handle Wallet Deduction
        if ($request->payment_method == 'wallet') {
            $customer = Customer::query()->find($request->customer_id);
            if ($customer->wallet_balance >= $request->fare) {
                $customer->decrement('wallet_balance', $request->fare);
                
                // Create Transaction Record
                Transaction::query()->create([
                    'customer_id' => $customer->id,
                    'booking_id' => $booking->id,
                    'amount' => $request->fare,
                    'type' => 'Ride Fare',
                    'status' => 'Completed',
                    'note' => 'Ride Fare Payment (Prepaid)',
                    'payment_method' => 'Digital'
                ]);

                // Award Points Immediately for Digital (Prepaid)
                $pointRatio = (float) \App\Models\Core\Setting::get('point_earning_ratio_digital', 2);
                $earnedPoints = floor($request->fare / 1000) * $pointRatio;
                if ($earnedPoints > 0) {
                    $customer->increment('loyalty_points', (int)$earnedPoints);
                }
            }
        }

        // Notify Driver
        Notification::send(
            'New Ride Request',
            "You have a new ride request from {$booking->customer->name}. Please accept or decline.",
            'warning',
            route('driver.dashboard', $driver->id)
        );

        return redirect()->route('customer.waiting', $booking->id);
    }

    public function waiting(int $bookingId)
    {
        $booking = Booking::query()->with('driver.vehicle')->findOrFail($bookingId);
        return view('customerview.waiting', compact('booking'));
    }

    public function cancel(int $bookingId)
    {
        $booking = Booking::query()->findOrFail($bookingId);
        
        if ($booking->status == 'pending') {
            $booking->update(['status' => 'cancelled']);
            
            // Refund Wallet and Points if it was a Digital Payment
            if ($booking->payment_method == 'wallet') {
                $customer = $booking->customer;
                if ($customer) {
                    // Refund Money
                    $customer->wallet_balance += $booking->fare;
                    
                    // Deduct Points (Since they were awarded instantly)
                    $pointRateDigital = \App\Models\Core\Setting::get('point_rate_digital', 500);
                    $pointsToDeduct = floor($booking->fare / $pointRateDigital);
                    if ($pointsToDeduct > 0) {
                        // Ensure points don't go below 0 (just in case)
                        $customer->loyalty_points = max(0, $customer->loyalty_points - $pointsToDeduct);
                    }
                    $customer->save();

                    // Create Refund Transaction Record
                    Transaction::query()->create([
                        'customer_id' => $customer->id,
                        'booking_id' => $booking->id,
                        'amount' => $booking->fare,
                        'type' => 'Refund',
                        'status' => 'Completed',
                        'note' => 'Ride Cancelled - Automatic Refund',
                        'payment_method' => 'Digital'
                    ]);
                }
            }

            return redirect()->route('customer.booking')->with('success', 'Your ride has been cancelled and funds (if any) have been refunded.');
        }

        return back()->with('error', 'Cannot cancel a ride that is already in progress.');
    }

    public function exchangePoints(Request $request)
    {
        $customer = auth('customer')->user();
        $pointsToExchange = $request->points;

        if ($pointsToExchange < 100) {
            return back()->with('error', 'အနည်းဆုံး Points ၁၀၀ ရှိမှ လဲလှယ်နိုင်ပါမည်။');
        }

        if ($customer->loyalty_points < $pointsToExchange) {
            return back()->with('error', 'သင့်ထံတွင် Points မလုံလောက်ပါ။');
        }

        $conversionRate = 10; // 1 Point = 10 Ks
        $amountToAdd = $pointsToExchange * $conversionRate;

        // Update Customer
        $customer->loyalty_points -= $pointsToExchange;
        $customer->wallet_balance += $amountToAdd;
        $customer->save();

        // Record Transaction
        Transaction::query()->create([
            'customer_id' => $customer->id,
            'amount' => $amountToAdd,
            'type' => 'Points Exchange',
            'status' => 'Completed',
            'note' => "Exchanged $pointsToExchange points for $amountToAdd Ks",
            'payment_method' => 'Digital'
        ]);

        return back()->with('success', "Points $pointsToExchange ခုအား $amountToAdd Ks အဖြစ် အောင်မြင်စွာ လဲလှယ်ပြီးပါပြီ။");
    }
}
