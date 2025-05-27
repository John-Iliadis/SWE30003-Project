<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommerceController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductController;

Route::get('/', function () { return view('home'); });
Route::get('/home', function () { return view('home'); });

Route::get('/login', function () {
    return view('login');
})->name('login'); // Add ->name('login') here

Route::get('/register', function() {
    return view('register');
});

Route::get('/catalogue', [CommerceController::class, 'catalogue']);
Route::get('/cart', [CommerceController::class, 'cart']);
Route::get('/product/{id}', [CommerceController::class, 'product']);

Route::post('/login', [AccountController::class, 'login'])->name('login.post');
Route::post('continue', function() {
    return view('register_details');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', \App\Http\Controllers\ProductController::class)
        ->names([
            'index' => 'admin.products.index',
            'create' => 'admin.products.create',
            'store' => 'admin.products.store',
            'edit' => 'admin.products.edit',
            'update' => 'admin.products.update',
            'destroy' => 'admin.products.destroy'
        ]);
});
