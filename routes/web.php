<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DriverController;

use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Driver\DriverPortalController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Customer\CustomerBookingController;


Route::get('/', function () {
    return redirect()->route('dashboard.home');
});

use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\DriverAuthController;

// === Customer Authentication ===
Route::get('/login/customer', function() { return view('auth.customer_auth', ['mode' => 'login']); })->name('view.login.customer');
Route::get('/register/customer', function() { return view('auth.customer_auth', ['mode' => 'register']); })->name('view.register.customer');

Route::post('/login/customer', [CustomerAuthController::class, 'login'])->name('customer.login.submit');
Route::post('/register/customer', [CustomerAuthController::class, 'register'])->name('customer.register.submit');
Route::post('/logout/customer', [CustomerAuthController::class, 'logout'])->name('customer.logout');

// === Driver Authentication ===
Route::get('/login/driver', function() { return view('auth.driver_login'); })->name('view.login.driver');
Route::get('/register/driver', function() { return view('auth.driver_register'); })->name('view.register.driver');
Route::get('/register/driver/step2', [DriverAuthController::class, 'showStep2'])->name('driver.register.step2');

Route::post('/login/driver', [DriverAuthController::class, 'login'])->name('driver.login.submit');
Route::post('/register/driver/initial', [DriverAuthController::class, 'registerInitial'])->name('driver.register.initial.submit');
Route::get('/onboarding/personal', [DriverAuthController::class, 'onboardingPersonal'])->name('driver.onboarding.personal');
Route::post('/onboarding/personal', [DriverAuthController::class, 'onboardingPersonalStore'])->name('driver.onboarding.personal.store');
Route::get('/onboarding/vehicle', [DriverAuthController::class, 'onboardingVehicle'])->name('driver.onboarding.vehicle');
Route::post('/onboarding/vehicle', [DriverAuthController::class, 'onboardingVehicleStore'])->name('driver.onboarding.vehicle.store');
Route::post('/logout/driver', [DriverAuthController::class, 'logout'])->name('driver.logout');


// Admin Withdrawal Management
Route::get('admin/withdrawals', [WithdrawalController::class, 'index'])->name('admin.withdrawals.index');
Route::post('admin/withdrawals/{id}/approve', [WithdrawalController::class, 'approve'])->name('admin.withdrawals.approve');
Route::post('admin/withdrawals/{id}/reject', [WithdrawalController::class, 'reject'])->name('admin.withdrawals.reject');

// Customer Interaction Routes (Protected by auth:customer)
Route::middleware('auth:customer')->group(function () {
    Route::get('/home', [CustomerBookingController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/activities', [CustomerBookingController::class, 'activities'])->name('customer.activities');
    Route::get('/my-settings', [CustomerBookingController::class, 'profile'])->name('customer.settings');
    Route::post('/my-settings/update', [CustomerBookingController::class, 'updateProfile'])->name('customer.settings.update');
    
    Route::get('/wallet/topup', [CustomerBookingController::class, 'topupView'])->name('customer.wallet.topup');
    Route::post('/wallet/topup', [CustomerBookingController::class, 'topupStore'])->name('customer.wallet.topup.store');
    Route::post('/wallet/exchange-points', [CustomerBookingController::class, 'exchangePoints'])->name('customer.wallet.exchange');

    Route::get('/onboarding', [CustomerBookingController::class, 'onboarding'])->name('customer.onboarding');
    Route::get('/payment-setup', [CustomerBookingController::class, 'paymentSetup'])->name('customer.payment.setup');
    
    Route::get('/book', [CustomerBookingController::class, 'index'])->name('customer.booking');
    Route::post('/book/store', [CustomerBookingController::class, 'store'])->name('customer.booking.store');
    Route::get('/waiting/{bookingId}', [CustomerBookingController::class, 'waiting'])->name('customer.waiting');
    Route::post('/booking/{bookingId}/cancel', [CustomerBookingController::class, 'cancel'])->name('customer.booking.cancel');
    
});

// Driver Portal Routes
Route::prefix('driver')->group(function () {
    Route::get('/{id}/dashboard', [DriverPortalController::class, 'dashboard'])->name('driver.dashboard');
    Route::get('/{id}/jobs', [DriverPortalController::class, 'jobs'])->name('driver.jobs');
    Route::post('/{id}/withdrawal', [DriverPortalController::class, 'requestWithdrawal'])->name('driver.withdrawal.request');
    Route::get('/{id}/withdrawal/{withdrawalId}', [DriverPortalController::class, 'withdrawalDetail'])->name('driver.withdrawal.detail');
    Route::post('/{id}/toggle-status', [DriverPortalController::class, 'toggleStatus'])->name('driver.status.toggle');
    Route::post('/{id}/trip/{tripId}/update', [DriverPortalController::class, 'updateTripStatus'])->name('driver.trip.update');
    Route::get('/{id}/withdrawals', [DriverPortalController::class, 'withdrawals'])->name('driver.withdrawals');
    Route::get('/{id}/profile', [DriverPortalController::class, 'profile'])->name('driver.profile');
    
    Route::get('/{id}/leaderboard', [DriverPortalController::class, 'leaderboard'])->name('driver.leaderboard');
    Route::get('/{id}/earnings', [DriverPortalController::class, 'earnings'])->name('driver.earnings');
    Route::get('/{id}/vehicle', [DriverPortalController::class, 'vehicle'])->name('driver.vehicle');
    Route::get('/{id}/reviews', [DriverPortalController::class, 'reviews'])->name('driver.reviews');
    Route::get('/{id}/referrals', [DriverPortalController::class, 'referrals'])->name('driver.referrals');
    Route::get('/{id}/notifications', [DriverPortalController::class, 'notifications_history'])->name('driver.notifications');
    Route::get('/{id}/demand-map', [DriverPortalController::class, 'demandMap'])->name('driver.demand.map');
    Route::get('/api/demand-data', [DriverPortalController::class, 'apiDemandData'])->name('driver.api.demand');
    
    // Accept/Decline Routes
    Route::post('/{id}/trip/{tripId}/accept', [DriverPortalController::class, 'acceptBooking'])->name('driver.trip.accept');
    Route::post('/{id}/trip/{tripId}/decline', [DriverPortalController::class, 'declineBooking'])->name('driver.trip.decline');
});







Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.home');

Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');


Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::post('settings', [SettingController::class, 'update'])->name('settings.update');



Route::resource('drivers', DriverController::class);
Route::resource('vehicles', VehicleController::class);
Route::resource('customers', CustomerController::class);
Route::resource('bookings', BookingController::class);
Route::resource('transactions', TransactionController::class);

// Reporting Routes
Route::get('reports/transactions/csv', [ReportController::class, 'exportTransactionsCsv'])->name('reports.transactions.csv');
Route::get('reports/transactions/print', [ReportController::class, 'printTransactions'])->name('reports.transactions.print');


Route::get('map-dashboard', [BookingController::class, 'mapDashboard'])->name('bookings.map');
Route::get('map-dashboard-data', [BookingController::class, 'mapDashboardData'])->name('bookings.map.data');
Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status.update');

Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
