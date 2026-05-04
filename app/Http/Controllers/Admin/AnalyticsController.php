<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Core\Booking;
use App\Models\Auth\Driver;
use App\Models\Core\Vehicle;
use App\Models\Auth\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        // 1. Overview Stats
        $totalRevenue = Booking::query()->where('status', 'completed')->sum('fare');
        $totalBookings = Booking::query()->count('*');
        $completedBookings = Booking::query()->where('status', 'completed')->count('*');
        $activeDrivers = Driver::query()->where('driver_status', 'active')->count('*');

        // 2. Revenue Trend (Last 7 Days)
        $revenueTrend = Booking::query()
            ->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->select([
                DB::raw('DATE(created_at) as date'), 
                DB::raw('SUM(fare) as total')
            ])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 3. Booking Status Distribution
        $statusCounts = Booking::query()
            ->select(['status', DB::raw('count(*) as count')])
            ->groupBy('status')
            ->pluck('count', 'status');

        // 4. Top Drivers (by completed rides)
        $topDrivers = Driver::withCount(['bookings' => function($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();

        // 5. Recent High-Value Trips
        $highValueTrips = Booking::with('customer')
            ->where('status', 'completed')
            ->orderBy('fare', 'desc')
            ->take(5)
            ->get();

        return view('dashboardview.analytics.index', compact(
            'totalRevenue', 
            'totalBookings', 
            'completedBookings', 
            'activeDrivers',
            'revenueTrend',
            'statusCounts',
            'topDrivers',
            'highValueTrips'
        ));
    }
}

