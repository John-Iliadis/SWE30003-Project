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

Route::prefix('admin')->group(function () {
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index']);
    Route::get('/products/create', [App\Http\Controllers\ProductController::class, 'create']);
    Route::post('/products', [App\Http\Controllers\ProductController::class, 'store']);
    Route::get('/products/{product}/edit', [App\Http\Controllers\ProductController::class, 'edit']);
    Route::put('/products/{product}', [App\Http\Controllers\ProductController::class, 'update']);
    Route::delete('/products/{product}', [App\Http\Controllers\ProductController::class, 'destroy']);
});
