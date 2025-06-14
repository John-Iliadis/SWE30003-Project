<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CatalogueManagerController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () { return view('home'); });
Route::get('/home', function () { return view('home'); });

// account
Route::get('/login', [AccountController::class, 'account']);
Route::get('/register', [AccountController::class, 'register']);
Route::post('/attempt-login', [AccountController::class, 'attemptLogin']);
Route::post('/create-account', [AccountController::class, 'createAccount']);
Route::middleware('auth')->group(function() {
    Route::get('/account', [AccountController::class, 'account']);
    Route::patch('/account/update', [AccountController::class, 'update']);
    Route::get('/order-history', [AccountController::class, 'orderHistory']);
    Route::post('/logout', [AccountController::class, 'logout']);
});

// catalogue
Route::get('/catalogue', [CatalogueController::class, 'catalogue']);
Route::get('/product/{id}', [CatalogueController::class, 'product']);
Route::post('/catalogue/filter', [CatalogueController::class, 'filter']);

// cart
Route::get('/cart', [CartController::class, 'cart']);
Route::get('/cart/add/{product_id}/{qty}', [CartController::class, 'add']);
Route::get('/cart/remove/{productId}', [CartController::class, 'remove']);
Route::get('cart/clear', [CartController::class, 'clear']);
Route::get('/cart/modify/{product_id}/{qty}', [CartController::class, 'modify']);

// Transaction routes
Route::get('/checkout', [TransactionController::class, 'checkout']);
Route::post('/process-payment', [TransactionController::class, 'processPayment']);
Route::get('/confirmation/{order_id}', [TransactionController::class, 'confirmation'])->name('transaction.confirmation');

// UPDATED Admin Routes: Remove authentication for direct access

// todo Ahnaf: change the /products route to catalogue-manager

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        // Redirect to products index, or show a dashboard view
        return redirect()->route('admin.products.index');
    })->name('dashboard');

    // Product Catalogue Management Routes using Route::resource
    Route::resource('products', CatalogueManagerController::class);

    // You can add routes for category management here later
    // Route::resource('categories', AdminCategoryController::class);
});

