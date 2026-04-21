<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriverController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('drivers', DriverController::class);
