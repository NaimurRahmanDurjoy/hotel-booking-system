<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

use App\Http\Controllers\TravelPackageController;
use App\Http\Controllers\TravelBookingController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\CarController;

// Public data
Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/rooms/{room}', [RoomController::class, 'show']);
Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/{car}', [CarController::class, 'show']);
Route::get('/hotels/{hotel}', [HotelController::class, 'show']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/travel-packages', [TravelPackageController::class, 'index']);
Route::get('/travel-packages/{travelPackage}', [TravelPackageController::class, 'show']);
Route::get('/premium-plans', [PremiumController::class, 'plans']);
Route::post('/contact', [ContactController::class, 'store']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Rooms
    Route::post('/rooms', [RoomController::class, 'store']);
    Route::put('/rooms/{room}', [RoomController::class, 'update']);
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy']);
    
    // Services
    Route::post('/services', [ServiceController::class, 'store']);
    Route::get('/services/{service}', [ServiceController::class, 'show']);
    Route::put('/services/{service}', [ServiceController::class, 'update']);
    Route::delete('/services/{service}', [ServiceController::class, 'destroy']);
    
    // Bookings
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/my', [BookingController::class, 'myBookings']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::put('/bookings/{booking}', [BookingController::class, 'update']);
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']);
    
    // Chat
    Route::get('/chat', [ChatController::class, 'index']);
    Route::get('/chat/conversations', [ChatController::class, 'conversations']);
    Route::post('/chat/send', [ChatController::class, 'sendMessageDirect']);
    Route::get('/chat/messages/{userId}', [ChatController::class, 'messagesByUserId']);
    Route::get('/chat/{user}/messages', [ChatController::class, 'messages']);
    Route::post('/chat/{user}/send', [ChatController::class, 'sendMessage']);
    Route::get('/chat/unread', [ChatController::class, 'unreadCount']);
    
    // Premium
    Route::get('/premium', [PremiumController::class, 'index']);
    Route::get('/premium/status', [PremiumController::class, 'checkStatus']);
    Route::post('/premium/subscribe', [PremiumController::class, 'subscribe']);
    Route::post('/premium/cancel', [PremiumController::class, 'cancel']);
    Route::post('/premium/extend', [PremiumController::class, 'extend']);
    
    // Travel Packages
    Route::post('/travel-packages', [TravelPackageController::class, 'store']);
    Route::put('/travel-packages/{travelPackage}', [TravelPackageController::class, 'update']);
    Route::delete('/travel-packages/{travelPackage}', [TravelPackageController::class, 'destroy']);

    // Travel Bookings
    Route::get('/travel-bookings', [TravelBookingController::class, 'index']);
    Route::post('/travel-bookings', [TravelBookingController::class, 'store']);
    Route::put('/travel-bookings/{travelBooking}', [TravelBookingController::class, 'update']);
    Route::post('/car-bookings', [CarController::class, 'storeBooking']);

    // Admin
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/users', [AdminController::class, 'users']);
    Route::get('/admin/users/{user}', [AdminController::class, 'user']);
    Route::put('/admin/users/{user}', [AdminController::class, 'updateUser']);
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser']);
    Route::post('/admin/users', [AdminController::class, 'storeUser']);
    Route::get('/admin/bookings', [AdminController::class, 'bookings']);
    Route::get('/admin/services', [AdminController::class, 'services']);
    
    // Premium Plan Admin
    Route::post('/admin/premium-plans', [PremiumController::class, 'storePlan']);
    Route::put('/admin/premium-plans/{plan}', [PremiumController::class, 'updatePlan']);
    Route::delete('/admin/premium-plans/{plan}', [PremiumController::class, 'deletePlan']);
});