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
    public function index(Request $request)
    {
        $query = Booking::query();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $period = $request->input('period', 'all');

        if ($period === 'today') {
            $startDate = Carbon::today()->toDateString();
            $endDate = Carbon::today()->toDateString();
        } elseif ($period === 'week') {
            $startDate = Carbon::now()->subDays(7)->toDateString();
            $endDate = Carbon::today()->toDateString();
        } elseif ($period === 'month') {
            $startDate = Carbon::now()->subDays(30)->toDateString();
            $endDate = Carbon::today()->toDateString();
        }

        if ($startDate) {
            $query->where('created_at', '>=', $startDate . ' 00:00:00');
        }
        if ($endDate) {
            $query->where('created_at', '<=', $endDate . ' 23:59:59');
        }

        // 1. Overview Stats
        $totalRevenue = (clone $query)->where('status', 'completed')->sum('fare');
        $totalBookings = (clone $query)->count('*');
        $completedBookings = (clone $query)->where('status', 'completed')->count('*');
        $activeDrivers = Driver::query()->where('driver_status', 'active')->count('*');

        // 2. Revenue Trend (Custom Range)
        $trendQuery = Booking::query()->where('status', 'completed');
        if ($startDate) $trendQuery->where('created_at', '>=', $startDate . ' 00:00:00');
        if ($endDate) $trendQuery->where('created_at', '<=', $endDate . ' 23:59:59');

        $revenueTrend = $trendQuery
            ->select([
                DB::raw('DATE(created_at) as date'), 
                DB::raw('SUM(fare) as total')
            ])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 3. Booking Status Distribution
        $statusCounts = (clone $query)
            ->select(['status', DB::raw('count(*) as count')])
            ->groupBy('status')
            ->pluck('count', 'status');

        // 4. Top Drivers (by completed rides)
        $topDrivers = Driver::withCount(['bookings' => function($q) use ($startDate, $endDate) {
                $q->where('status', 'completed');
                if ($startDate) $q->where('created_at', '>=', $startDate . ' 00:00:00');
                if ($endDate) $q->where('created_at', '<=', $endDate . ' 23:59:59');
            }])
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();

        // 5. Recent High-Value Trips
        $highValueTrips = (clone $query)->with('customer')
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
            'highValueTrips',
            'period',
            'startDate',
            'endDate'
        ));
    }
}

