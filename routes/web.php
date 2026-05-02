<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ServiceController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/premium-plans', function () {
        return view('admin.premium_plans');
    })->name('premium_plans');
});

// Manager Routes
Route::middleware(['auth', 'role:manager,admin'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard'); // Shared for now or specific
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/chat', function () {
        return view('admin.chat');
    })->name('chat');
});

// Customer Routes (Protected)
Route::middleware(['auth', 'role:customer,manager,admin'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/bookings', function () {
        return view('customer.bookings');
    })->name('bookings.index');
});

require __DIR__ . '/auth.php';
