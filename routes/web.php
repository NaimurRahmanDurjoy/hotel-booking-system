<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Customer Routes
Route::get('/dashboard', function () {
    return view('customer.dashboard');
});

Route::get('/bookings', function () {
    return view('customer.bookings');
});

Route::get('/chat', function () {
    return view('customer.chat');
});

Route::get('/rooms', function () {
    return view('customer.rooms');
});

Route::get('/premium', function () {
    return view('customer.premium');
});
