<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Customer Routes
Route::get('/dashboard', function () {
    return view('customer.dashboard');
})->middleware('auth');

Route::get('/bookings', function () {
    return view('customer.bookings');
})->middleware('auth');

Route::get('/chat', function () {
    return view('customer.chat');
})->middleware('auth');

Route::get('/rooms', function () {
    return view('welcome');
});

Route::get('/premium', function () {
    return view('welcome');
});
