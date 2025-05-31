<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController; // Good, this is imported
use App\Http\Controllers\CommerceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AccountController;
// ProductController is already imported as Admin\ProductController below
use App\Http\Controllers\TransactionController;

Route::get('/', function () { return view('home'); });
Route::get('/home', function () { return view('home'); });

Route::get('/login', function () {
    return view('login');
})->name('login'); // Add ->name('login') here

Route::get('/register', function() {
    return view('register');
});

Route::get('/restart-session', [CartController::class, 'restartSession']);

// catalogue
Route::get('/catalogue', [CommerceController::class, 'catalogue']);
Route::get('/product/{id}', [CommerceController::class, 'product']);
Route::post('/catalogue/filter', [CommerceController::class, 'filter']);

// cart
Route::get('/cart', [CartController::class, 'cart']);
Route::get('/cart/add/{product_id}/{qty}', [CartController::class, 'add']);
Route::get('/cart/remove/{productId}', [CartController::class, 'remove']);
Route::get('cart/clear', [CartController::class, 'clear']);
Route::get('/cart/modify/{product_id}/{qty}', [CartController::class, 'modify']);

Route::post('/login', [AccountController::class, 'login'])->name('login.post');
Route::post('/register', [AccountController::class, 'register'])->name('register.post');
Route::middleware('auth')->group(function() {
    Route::get('/account', [AccountController::class, 'show'])->name('account');
    Route::patch('/account', [AccountController::class, 'update'])->name('account.update');
});
Route::post('logout', [AccountController::class, 'logout'])->name('logout');

// Transaction routes
Route::get('/checkout', [TransactionController::class, 'checkout'])->name('transaction.checkout');
Route::get('/payment', [TransactionController::class, 'payment'])->name('transaction.payment');
Route::post('/process-payment', [TransactionController::class, 'processPayment'])->name('transaction.process');
Route::get('/confirmation/{order_id}', [TransactionController::class, 'confirmation'])->name('transaction.confirmation');

// UPDATED Admin Routes: Middleware removed
Route::prefix('admin')->name('admin.')->group(function () { 
    Route::get('/dashboard', function () {
        // Redirect to products index, or show a dashboard view
        return redirect()->route('admin.products.index'); 
    })->name('dashboard');

    // Product Catalogue Management Routes using Route::resource
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)
        ->names([
            'index' => 'products.index', 
            'create' => 'products.create',
            'store' => 'products.store',
            'show' => 'products.show',
            'edit' => 'products.edit',
            'update' => 'products.update',
            'destroy' => 'products.destroy'
        ]);
    
    // You can add routes for category management here later
    // Route::resource('categories', AdminCategoryController::class);
});

