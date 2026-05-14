<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DriverController, VehicleController, CustomerController, 
    BookingController, AnalyticsController, TransactionController, 
    ReportController, DashboardController, SettingController, 
    WithdrawalController, PointRewardController
};

// Admin Auth View (Temporary)
Route::get('/admin/login', fn() => view('auth.admin_auth'))->name('view.login.admin');

// Admin Panel Home
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.home');

// Settings
Route::controller(SettingController::class)->group(function() {
    Route::get('settings', 'index')->name('settings.index');
    Route::post('settings', 'update')->name('settings.update');
});

// Withdrawals
Route::controller(WithdrawalController::class)->prefix('admin')->group(function() {
    Route::get('withdrawals', 'index')->name('admin.withdrawals.index');
    Route::post('withdrawals/{id}/approve', 'approve')->name('admin.withdrawals.approve');
    Route::post('withdrawals/{id}/reject', 'reject')->name('admin.withdrawals.reject');
});

// Resource Routes
Route::resources([
    'drivers' => DriverController::class,
    'vehicles' => VehicleController::class,
    'customers' => CustomerController::class,
    'bookings' => BookingController::class,
    'transactions' => TransactionController::class,
    'point-rewards' => PointRewardController::class,
]);

// Reports
Route::controller(ReportController::class)->prefix('reports')->group(function() {
    Route::get('transactions/csv', 'exportTransactionsCsv')->name('reports.transactions.csv');
    Route::get('transactions/print', 'printTransactions')->name('reports.transactions.print');
});

// Advanced Booking Features
Route::controller(BookingController::class)->group(function() {
    Route::get('map-dashboard', 'mapDashboard')->name('bookings.map');
    Route::get('map-dashboard-data', 'mapDashboardData')->name('bookings.map.data');
    Route::patch('bookings/{booking}/status', 'updateStatus')->name('bookings.status.update');
});

// Analytics
Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
