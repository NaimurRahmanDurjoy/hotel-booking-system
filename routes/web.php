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

use App\Http\Controllers\HotelController;
use App\Http\Controllers\TravelPackageController;
use App\Http\Controllers\TravelBookingController;
use App\Http\Controllers\CarController;

// Manager Routes
Route::middleware(['auth', 'role:manager,admin'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('hotels', HotelController::class);
    
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/chat', function () {
        return view('admin.chat');
    })->name('chat');

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('travel-packages', TravelPackageController::class);
        Route::resource('cars', CarController::class);
        Route::get('/car-bookings', [CarController::class, 'bookingsIndex'])->name('car_bookings.index');
        Route::put('/car-bookings/{carBooking}', [CarController::class, 'updateBooking'])->name('car_bookings.update');
        Route::get('/travel-bookings', [TravelBookingController::class, 'index'])->name('travel_bookings.index');
        Route::put('/travel-bookings/{travelBooking}', [TravelBookingController::class, 'update'])->name('travel_bookings.update');
    });
});

// Customer Routes (Protected)
Route::middleware(['auth', 'role:customer,manager,admin'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/bookings', function () {
        return view('customer.bookings');
    })->name('bookings.index');
});

require __DIR__ . '/auth.php';
