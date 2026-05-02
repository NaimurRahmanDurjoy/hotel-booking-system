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
});

// Manager Routes
Route::middleware(['auth', 'role:manager,admin'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard'); // Shared for now or specific
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
});

// Customer Routes (Protected)
Route::middleware(['auth', 'role:customer,manager,admin'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('customer.dashboard');
    })->name('dashboard');

    Route::get('/bookings', function () {
        return view('customer.bookings');
    })->name('bookings.index');

    Route::get('/chat', function () {
        return view('customer.chat');
    })->name('chat');

    Route::get('/rooms', function () {
        return view('customer.rooms');
    })->name('rooms.browse');

    Route::get('/premium', function () {
        return view('customer.premium');
    })->name('premium');
});

require __DIR__ . '/auth.php';
