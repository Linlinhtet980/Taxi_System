<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Core\NotificationController as CoreNotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes - Unified Main Entry
|--------------------------------------------------------------------------
*/

// Root Redirect
Route::get('/', fn() => redirect()->route('dashboard.home'));
Route::get('/login', fn() => redirect()->route('view.login.customer'))->name('login');

// Global Core Features (Notifications, etc.)
Route::post('/notifications/{id}/mark-as-read', [CoreNotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
Route::post('/notifications/mark-all-as-read', [CoreNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');

// Load Separated Routes
require __DIR__.'/admin.php';
require __DIR__.'/driver.php';
require __DIR__.'/customer.php';
