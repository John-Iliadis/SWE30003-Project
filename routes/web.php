<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommerceController;

Route::get('/', function () {
    return view('home');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/catalogue', [CommerceController::class, 'catalogue']);

Route::get('/product', function () {
    return view('product');
});
