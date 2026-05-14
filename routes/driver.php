<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Driver\DriverPortalController;
use App\Http\Controllers\Auth\DriverAuthController;

// Driver Auth
Route::controller(DriverAuthController::class)->group(function() {
    Route::get('/login/driver', fn() => view('auth.driver_login'))->name('view.login.driver');
    Route::get('/register/driver', fn() => view('auth.driver_register'))->name('view.register.driver');
    Route::post('/login/driver', 'login')->name('driver.login.submit');
    Route::post('/register/driver/initial', 'registerInitial')->name('driver.register.initial.submit');
    Route::get('/onboarding/personal', 'onboardingPersonal')->name('driver.onboarding.personal');
    Route::post('/onboarding/personal', 'onboardingPersonalStore')->name('driver.onboarding.personal.store');
    Route::get('/onboarding/vehicle', 'onboardingVehicle')->name('driver.onboarding.vehicle');
    Route::post('/onboarding/vehicle', 'onboardingVehicleStore')->name('driver.onboarding.vehicle.store');
    Route::post('/logout/driver', 'logout')->name('driver.logout');
});

// Driver Portal
Route::prefix('driver')->controller(DriverPortalController::class)->group(function () {
    Route::get('/{id}/dashboard', 'dashboard')->name('driver.dashboard');
    Route::get('/{id}/jobs', 'jobs')->name('driver.jobs');
    Route::post('/{id}/withdrawal', 'requestWithdrawal')->name('driver.withdrawal.request');
    Route::get('/{id}/withdrawal/{withdrawalId}', 'withdrawalDetail')->name('driver.withdrawal.detail');
    Route::post('/{id}/status/toggle', 'toggleStatus')->name('driver.status.toggle');
    Route::post('/{id}/trip/{tripId}/update', 'updateTripStatus')->name('driver.trip.update');
    Route::get('/{id}/withdrawals', 'withdrawals')->name('driver.withdrawals');
    Route::get('/{id}/profile', 'profile')->name('driver.profile');
    Route::post('/{id}/profile/update', 'updateProfile')->name('driver.profile.update');
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
