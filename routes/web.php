<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\AttendanceController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('landing');
});

// Authentication routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/registration/success', [RegisterController::class, 'success'])->name('registration.success');

// HR routes
Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'hr', 'as' => 'hr.'], function () {
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
});

