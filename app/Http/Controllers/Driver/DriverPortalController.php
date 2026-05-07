<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Auth\Driver;
use App\Models\Core\Booking;
use App\Models\Core\Transaction;
use App\Models\Core\Withdrawal;
use App\Models\Core\Notification as NotificationModel;
use App\Models\Core\OnlineLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DriverPortalController extends Controller
{
    public function dashboard(int $id)
    {
        $driver = Driver::with('vehicle')->findOrFail($id);
        
        $activeJobs = Booking::query()->where('driver_id', $id)
            ->whereIn('status', ['confirmed', 'ongoing'])
            ->get();
            
        $newRequests = $activeJobs->isEmpty() 
            ? Booking::query()->where('driver_id', $id)->where('status', 'pending')->get() 
            : collect();
            
        // Earnings Stats
        $todayEarnings = Transaction::query()->where('driver_id', $id)
            ->whereDate('created_at', Carbon::today())
            ->sum('driver_amount');
            
        $thisWeekEarnings = Transaction::query()->where('driver_id', $id)
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('driver_amount');
            
        $completedJobsCount = Booking::query()->where('driver_id', $id)
            ->where('status', 'completed')
            ->count();
            
        $recentTransactions = Transaction::query()->where('driver_id', $id)
            ->latest()
            ->take(5)
            ->get();

        // Earnings Data for Chart (Last 7 Days)
        $dailyEarnings = Transaction::query()->where('driver_id', $id)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE_FORMAT(created_at, "%M %d") as date, sum(driver_amount) as total')
            ->groupBy('date')
            ->orderBy('created_at')
            ->pluck('total', 'date');
            
        // Daily Goal (e.g., 50,000 MMK)
        $dailyTarget = 50000;
        $goalProgress = min(100, round(($todayEarnings / $dailyTarget) * 100));

        // Online Hours Tracking (Today)
        $todayOnlineMinutes = OnlineLog::query()
            ->where('driver_id', '=', $id)
            ->whereDate('started_at', '=', Carbon::today())
            ->sum('duration_minutes');
            
        // If currently active, ensure we have an open log
        if ($driver->driver_status == 'active') {
            /** @var OnlineLog $currentLog */
            $currentLog = OnlineLog::query()
                ->where('driver_id', '=', $id)
                ->whereNull('ended_at')
                ->latest()
                ->first();
            
            if (!$currentLog) {
                // Auto-start log if driver is active but no open log (e.g., after migration)
                $currentLog = OnlineLog::create([
                    'driver_id' => $id,
                    'started_at' => now(),
                ]);
            }
            
            if ($currentLog) {
                $todayOnlineMinutes += $currentLog->started_at->diffInMinutes(now());
            }
        }
        
        $onlineHours = floor($todayOnlineMinutes / 60);
        $onlineMins = $todayOnlineMinutes % 60;
        $todayOnlineTime = "{$onlineHours}h {$onlineMins}m";

        return view('driverview.dashboard', compact(
            'driver', 'activeJobs', 'newRequests', 'completedJobsCount', 
            'recentTransactions', 'todayEarnings', 'thisWeekEarnings', 'dailyEarnings',
            'goalProgress', 'dailyTarget', 'todayOnlineTime'
        ));
    }


    public function acceptBooking(int $id, int $tripId)
    {
        $booking = Booking::query()->findOrFail($tripId);
        $booking->update(['status' => 'confirmed']);
        
        // Redirect directly to the map for navigation
        return redirect()->route('driver.demand.map', $id)->with('success', 'Booking accepted. Starting navigation.');
    }

    public function declineBooking(int $id, int $tripId)
    {
        $booking = Booking::query()->findOrFail($tripId);
        $booking->update(['status' => 'cancelled']); // Or re-assign to another driver
        
        return back()->with('success', 'You have declined the ride request.');
    }

    public function jobs(Request $request, int $id)
    {
        $driver = Driver::query()->findOrFail($id);
        $perPage = $request->has('show_all') ? 20 : 5;
        
        $allJobs = Booking::query()->where('driver_id', $id)
            ->with('customer')
            ->latest()
            ->paginate($perPage);
            
        return view('driverview.jobs', compact('driver', 'allJobs'));
    }

    public function updateTripStatus(Request $request, int $id, int $tripId)
    {
        $booking = Booking::query()->findOrFail($tripId);
        $newStatus = $request->status;
        $paymentMethod = $request->payment_method ?? 'Cash';

        $oldStatus = $booking->status;
        $booking->update(['status' => $newStatus]);

        if ($newStatus == 'completed' && $oldStatus != 'completed') {
            $fare = $booking->fare;
            $rate = \App\Models\Core\Setting::get('commission_rate', 15) / 100;
            $commission = round($fare * $rate);
            $driverAmount = $fare - $commission;

            \Illuminate\Support\Facades\DB::beginTransaction();
            try {
                Transaction::create([
                    'booking_id' => $booking->id,
                    'driver_id' => $booking->driver_id,
                    'customer_id' => $booking->customer_id,
                    'amount' => $fare,
                    'commission_amount' => $commission,
                    'driver_amount' => $driverAmount,
                    'payment_method' => $paymentMethod,
                    'status' => 'Completed'
                ]);

                $driver = Driver::query()->find($booking->driver_id);
                if ($driver) {
                    if ($paymentMethod == 'Cash') {
                        // Driver received full amount, owes commission to company
                        $driver->wallet_balance -= $commission;
                    } else {
                        // Company received full amount, owes driver share to driver
                        $driver->wallet_balance += $driverAmount;
                    }
                    $driver->save();
                }

                // Award Loyalty Points to Customer (Only for CASH at completion, Digital is instant)
                $customer = $booking->customer;
                if ($customer && $paymentMethod == 'Cash') {
                    $pointRatio = (float) \App\Models\Core\Setting::get('point_earning_ratio_cash', 1);
                    $earnedPoints = floor($fare / 1000) * $pointRatio;
                    if ($earnedPoints > 0) {
                        $customer->increment('loyalty_points', (int)$earnedPoints);
                    }
                }

                \Illuminate\Support\Facades\DB::commit();
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                return back()->with('error', 'Trip completion failed: ' . $e->getMessage());
            }


        NotificationModel::send(
                'Trip Completed',
                "Driver {$driver->full_name} has completed trip #{$booking->id}.",
                'success',
                route('transactions.index'),
                \App\Models\Auth\User::first(['*']) // Notify Admin
            );
            
        NotificationModel::send(
                'Ride Completed',
                "Your ride #{$booking->id} has been finished. Thank you!",
                'success',
                route('customer.activities'),
                $booking->customer
            );
        }

        if ($booking->vehicle_id) {
            $vehicleStatus = ($newStatus == 'completed' || $newStatus == 'cancelled') ? 'Available' : 'On Ride';
            $booking->vehicle->update(['status' => $vehicleStatus]);
        }

        if ($newStatus == 'completed') {
            return redirect()->route('driver.dashboard', $id)->with('success', 'Trip completed successfully!');
        }

        return back()->with('success', 'Trip status updated to ' . strtoupper($newStatus));
    }

    public function requestWithdrawal(Request $request, int $id)
    {
        $driver = Driver::query()->findOrFail($id);
        
        $request->validate([
            'amount' => 'required|numeric|min:1000|max:' . $driver->wallet_balance,
            'payment_method' => 'required'
        ]);

        Withdrawal::create([
            'driver_id' => $id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => 'pending'
        ]);

        NotificationModel::send(
            'New Withdrawal Request',
            "Driver {$driver->full_name} has requested a withdrawal of " . number_format($request->amount) . " MMK.",
            'warning',
            route('admin.withdrawals.index'),
            \App\Models\Auth\User::first(['*']) // Notify Admin
        );

        return back()->with('success', 'Withdrawal request submitted successfully.');
    }

    public function toggleStatus(int $id)
    {
        $driver = Driver::query()->findOrFail($id);
        $oldStatus = $driver->driver_status;
        $newStatus = ($oldStatus == 'active') ? 'inactive' : 'active';
        $driver->update(['driver_status' => $newStatus]);
        
        if ($newStatus == 'active') {
            // Start a new online log
            OnlineLog::create([
                'driver_id' => $id,
                'started_at' => now(),
            ]);
        } else {
            // Close the current online log
            /** @var OnlineLog $log */
            $log = OnlineLog::query()
                ->where('driver_id', '=', $id)
                ->whereNull('ended_at')
                ->latest()
                ->first();
                
            if ($log) {
                $endedAt = now();
                $duration = $log->started_at->diffInMinutes($endedAt);
                $log->update([
                    'ended_at' => $endedAt,
                    'duration_minutes' => $duration
                ]);
            }
        }

        return back()->with('success', 'Status updated to ' . ucfirst($newStatus));
    }

    public function withdrawals(Request $request, int $id)
    {
        $driver = Driver::query()->findOrFail($id);
        $perPage = $request->has('show_all') ? 20 : 5;
        
        $withdrawals = Withdrawal::query()->where('driver_id', $id)->latest()->paginate($perPage);
        
        return view('driverview.withdrawals', compact('driver', 'withdrawals'));
    }

    public function withdrawalDetail(int $id, int $withdrawalId)
    {
        $driver = Driver::query()->findOrFail($id);
        $withdrawal = Withdrawal::query()->where('driver_id', $id)->findOrFail($withdrawalId);
        
        return view('driverview.withdrawal_detail', compact('driver', 'withdrawal'));
    }

    public function profile(int $id)
    {
        $driver = Driver::query()->with('vehicle')->findOrFail($id);
        return view('driverview.profile', compact('driver'));
    }

    public function leaderboard(int $id)
    {
        $driver = Driver::query()->findOrFail($id);
        $topDrivers = Driver::query()->withCount(['bookings' => function($q) {
                $q->where('status', 'completed');
            }])
            ->orderBy('bookings_count', 'desc')
            ->take(10)
            ->get();
            
        return view('driverview.leaderboard', compact('driver', 'topDrivers'));
    }

    public function reviews(int $id)
    {
        $driver = Driver::query()->findOrFail($id);
        $reviews = Booking::query()->where('driver_id', $id)
            ->whereNotNull('review')
            ->with('customer')
            ->latest()
            ->paginate(10);
            
        $averageRating = Booking::query()->where('driver_id', $id)
            ->whereNotNull('rating')
            ->avg('rating');

        return view('driverview.reviews', compact('driver', 'reviews', 'averageRating'));
    }

    public function notifications_history(int $id)
    {
        $driver = Driver::query()->findOrFail($id);
        // Using Notification model scoped to driver
        $notifications = NotificationModel::query()
            ->where('user_id', $id)
            ->where('user_type', get_class($driver))
            ->latest()
            ->paginate(10);
        
        return view('driverview.notifications', compact('driver', 'notifications'));
    }

    public function demandMap(int $id)
    {
        $driver = Driver::query()->findOrFail($id);
        $activeBooking = Booking::query()->where('driver_id', $id)
            ->whereIn('status', ['confirmed', 'ongoing'])
            ->with('customer')
            ->first();
            
        return view('driverview.demand_map', compact('driver', 'activeBooking'));
    }

    public function earnings(int $id)
    {
        $driver = Driver::query()->findOrFail($id);
        
        // Stats for current month
        $currentMonthEarnings = Transaction::query()->where('driver_id', $id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('driver_amount');
            
        $currentMonthCommission = Transaction::query()->where('driver_id', $id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('commission_amount');

        // Chart Data: Last 30 Days Daily Earnings
        $dailyData = Transaction::query()->where('driver_id', $id)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, sum(driver_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('driverview.earnings', compact('driver', 'currentMonthEarnings', 'currentMonthCommission', 'dailyData'));
    }

    public function referrals(int $id)
    {
        $driver = Driver::query()->findOrFail($id);
        
        if (!$driver->referral_code) {
            $driver->update(['referral_code' => 'TXI' . strtoupper(substr(uniqid(), -5))]);
        }

        $referredDrivers = Driver::query()->where('referred_by', '=', $driver->referral_code)->get();

        return view('driverview.referrals', compact('driver', 'referredDrivers'));
    }

    public function vehicle(int $id)
    {
        $driver = Driver::query()->with('vehicle')->findOrFail($id);
        return view('driverview.vehicle', compact('driver'));
    }

    public function apiDemandData()
    {
        // Fetch pickup locations of bookings from the last 48 hours
        $bookings = Booking::query()
            ->where('created_at', '>=', now()->subHours(48))
            ->whereNotNull('pickup_lat')
            ->whereNotNull('pickup_lng')
            ->select(['pickup_lat', 'pickup_lng'])
            ->get();

        return response()->json($bookings);
    }
}
