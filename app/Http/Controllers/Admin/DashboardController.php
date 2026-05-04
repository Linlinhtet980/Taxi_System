<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Core\Booking;
use App\Models\Auth\Driver;
use App\Models\Auth\Customer;
use App\Models\Core\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats
        $totalBookings = Booking::query()->count('*');
        $activeDrivers = Driver::query()->where('driver_status', 'active')->count('*');
        $totalCustomers = Customer::query()->count('*');
        
        // Revenue (Today)
        $todayRevenue = Transaction::query()->whereDate('created_at', '=', Carbon::today(), 'and')
            ->where('status', '=', 'Completed')
            ->sum('amount');

        // Recent Bookings
        $recentBookings = Booking::query()->with(['customer', 'driver'])->latest()->take(5)->get();

        // Booking Status Data for Chart
        $statusCounts = Booking::query()->selectRaw('status, count(*) as count', [])
            ->groupBy('status')
            ->pluck('count', 'status');

        // Revenue Data for Line Chart (Last 6 Months)
        $monthlyRevenue = Transaction::query()->where('status', 'Completed')
            ->selectRaw('MONTHNAME(created_at) as month, sum(amount) as total')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('created_at')
            ->pluck('total', 'month');

        return view('dashboardview.dashboard.index', compact(
            'totalBookings', 
            'activeDrivers', 
            'totalCustomers', 
            'todayRevenue', 
            'recentBookings',
            'statusCounts',
            'monthlyRevenue'
        ));

    }
}
