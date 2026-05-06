<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DriverController, VehicleController, CustomerController, 
    BookingController, AnalyticsController, TransactionController, 
    ReportController, DashboardController, SettingController, 
    NotificationController, WithdrawalController, PointRewardController
};
use App\Http\Controllers\Driver\DriverPortalController;
use App\Http\Controllers\Customer\{CustomerBookingController, PointController};
use App\Http\Controllers\Auth\{CustomerAuthController, DriverAuthController};

// === Home Redirect ===
// =========================================================================
// === MAIN ENTRY POINTS (အဓိက ဝင်ပေါက် ၃ ခု) ===
// =========================================================================
Route::get('/', fn() => redirect()->route('dashboard.home')); // Root Redirect
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.home'); // 1. Admin Panel
Route::get('/login/customer', fn() => view('auth.customer_auth', ['mode' => 'login']))->name('view.login.customer'); // 2. Customer App
Route::get('/login/driver', fn() => view('auth.driver_login'))->name('view.login.driver'); // 3. Driver Portal
// =========================================================================

// === Import Controllers ===

// === Customer Authentication ===
Route::controller(CustomerAuthController::class)->group(function() {
    Route::get('/register/customer', fn() => view('auth.customer_auth', ['mode' => 'register']))->name('view.register.customer');
    Route::post('/login/customer', 'login')->name('customer.login.submit');
    Route::post('/register/customer', 'register')->name('customer.register.submit');
    Route::post('/logout/customer', 'logout')->name('customer.logout');
});

// === Driver Authentication ===
Route::controller(DriverAuthController::class)->group(function() {
    Route::get('/register/driver', fn() => view('auth.driver_register'))->name('view.register.driver');
    Route::post('/login/driver', 'login')->name('driver.login.submit');
    Route::post('/register/driver/initial', 'registerInitial')->name('driver.register.initial.submit');
    Route::get('/onboarding/personal', 'onboardingPersonal')->name('driver.onboarding.personal');
    Route::post('/onboarding/personal', 'onboardingPersonalStore')->name('driver.onboarding.personal.store');
    Route::get('/onboarding/vehicle', 'onboardingVehicle')->name('driver.onboarding.vehicle');
    Route::post('/onboarding/vehicle', 'onboardingVehicleStore')->name('driver.onboarding.vehicle.store');
    Route::post('/logout/driver', 'logout')->name('driver.logout');
});

// === Customer Portal (Protected) ===
Route::middleware('auth:customer')->controller(CustomerBookingController::class)->group(function () {
    Route::get('/home', 'dashboard')->name('customer.dashboard');
    Route::get('/activities', 'activities')->name('customer.activities');
    Route::get('/my-settings', 'profile')->name('customer.settings');
    Route::post('/my-settings/update', 'updateProfile')->name('customer.settings.update');
    Route::get('/wallet/topup', 'topupView')->name('customer.wallet.topup');
    Route::post('/wallet/topup', 'topupStore')->name('customer.wallet.topup.store');
    Route::post('/wallet/exchange-points', 'exchangePoints')->name('customer.wallet.exchange');
    Route::get('/onboarding', 'onboarding')->name('customer.onboarding');
    Route::get('/payment-setup', 'paymentSetup')->name('customer.payment.setup');
    Route::get('/book', 'index')->name('customer.booking');
    Route::post('/book/store', 'store')->name('customer.booking.store');
    Route::get('/waiting/{bookingId}', 'waiting')->name('customer.waiting');
    Route::post('/booking/{bookingId}/cancel', 'cancel')->name('customer.booking.cancel');
    
    // Points System
    Route::get('/points/exchange', [PointController::class, 'index'])->name('customer.points.exchange');
    Route::post('/points/exchange/{rewardId}', [PointController::class, 'exchange'])->name('customer.points.exchange.submit');
});

// === Driver Portal ===
Route::prefix('driver')->controller(DriverPortalController::class)->group(function () {
    Route::get('/{id}/dashboard', 'dashboard')->name('driver.dashboard');
    Route::get('/{id}/jobs', 'jobs')->name('driver.jobs');
    Route::post('/{id}/withdrawal', 'requestWithdrawal')->name('driver.withdrawal.request');
    Route::get('/{id}/withdrawal/{withdrawalId}', 'withdrawalDetail')->name('driver.withdrawal.detail');
    Route::post('/{id}/toggle-status', 'toggleStatus')->name('driver.status.toggle');
    Route::post('/{id}/trip/{tripId}/update', 'updateTripStatus')->name('driver.trip.update');
    Route::get('/{id}/withdrawals', 'withdrawals')->name('driver.withdrawals');
    Route::get('/{id}/profile', 'profile')->name('driver.profile');
    Route::get('/{id}/leaderboard', 'leaderboard')->name('driver.leaderboard');
    Route::get('/{id}/earnings', 'earnings')->name('driver.earnings');
    Route::get('/{id}/vehicle', 'vehicle')->name('driver.vehicle');
    Route::get('/{id}/reviews', 'reviews')->name('driver.reviews');
    Route::get('/{id}/referrals', 'referrals')->name('driver.referrals');
    Route::get('/{id}/notifications', 'notifications_history')->name('driver.notifications');
    Route::get('/{id}/demand-map', 'demandMap')->name('driver.demand.map');
    Route::get('/api/demand-data', 'apiDemandData')->name('driver.api.demand');
    Route::post('/{id}/trip/{tripId}/accept', 'acceptBooking')->name('driver.trip.accept');
    Route::post('/{id}/trip/{tripId}/decline', 'declineBooking')->name('driver.trip.decline');
});

// === Admin Dashboard & Management ===
Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

Route::controller(SettingController::class)->group(function() {
    Route::get('settings', 'index')->name('settings.index');
    Route::post('settings', 'update')->name('settings.update');
});

Route::controller(WithdrawalController::class)->prefix('admin')->group(function() {
    Route::get('withdrawals', 'index')->name('admin.withdrawals.index');
    Route::post('withdrawals/{id}/approve', 'approve')->name('admin.withdrawals.approve');
    Route::post('withdrawals/{id}/reject', 'reject')->name('admin.withdrawals.reject');
});

// === Resource Routes ===
Route::resources([
    'drivers' => DriverController::class,
    'vehicles' => VehicleController::class,
    'customers' => CustomerController::class,
    'bookings' => BookingController::class,
    'transactions' => TransactionController::class,
    'point-rewards' => PointRewardController::class,
]);

// === Custom Admin Routes ===
Route::controller(ReportController::class)->prefix('reports')->group(function() {
    Route::get('transactions/csv', 'exportTransactionsCsv')->name('reports.transactions.csv');
    Route::get('transactions/print', 'printTransactions')->name('reports.transactions.print');
});

Route::controller(BookingController::class)->group(function() {
    Route::get('map-dashboard', 'mapDashboard')->name('bookings.map');
    Route::get('map-dashboard-data', 'mapDashboardData')->name('bookings.map.data');
    Route::patch('bookings/{booking}/status', 'updateStatus')->name('bookings.status.update');
});

Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
