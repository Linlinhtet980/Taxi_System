<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\{CustomerBookingController, PointController};
use App\Http\Controllers\Auth\CustomerAuthController;

// Customer Auth
Route::controller(CustomerAuthController::class)->group(function() {
    Route::get('/login/customer', fn() => view('auth.customer_auth', ['mode' => 'login']))->name('view.login.customer');
    Route::get('/register/customer', fn() => view('auth.customer_auth', ['mode' => 'register']))->name('view.register.customer');
    Route::post('/login/customer', 'login')->name('customer.login.submit');
    Route::post('/register/customer', 'register')->name('customer.register.submit');
    Route::post('/logout/customer', 'logout')->name('customer.logout');
});

// Customer Portal (Protected)
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
    Route::post('/booking/{bookingId}/review', 'submitReview')->name('customer.booking.review');
    
    // Points System
    Route::get('/points/exchange', [PointController::class, 'index'])->name('customer.points.exchange');
    Route::post('/points/exchange/{rewardId}', [PointController::class, 'exchange'])->name('customer.points.exchange.submit');
});
