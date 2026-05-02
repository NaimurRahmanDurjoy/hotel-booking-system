<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public data
Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/services', [ServiceController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Rooms
    Route::post('/rooms', [RoomController::class, 'store']);
    Route::get('/rooms/{room}', [RoomController::class, 'show']);
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
    
    // Admin
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/users', [AdminController::class, 'users']);
    Route::get('/admin/users/{user}', [AdminController::class, 'user']);
    Route::put('/admin/users/{user}', [AdminController::class, 'updateUser']);
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser']);
    Route::post('/admin/manager', [AdminController::class, 'createManager']);
    Route::get('/admin/bookings', [AdminController::class, 'bookings']);
    Route::get('/admin/services', [AdminController::class, 'services']);
});