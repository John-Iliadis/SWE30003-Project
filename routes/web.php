<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommerceController;

Route::get('/', function () {
    return view('home');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function() {
    return view('register');
});

Route::get('/catalogue', [CommerceController::class, 'catalogue']);
Route::get('/cart', [CommerceController::class, 'cart']);
Route::get('/product/{id}', [CommerceController::class, 'product']);

Route::post('/login', [AccountController::class, 'login']);
Route::post('continue', function() {
    return view('register_details');
});
