<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () { return view('home'); });
Route::get('/home', function () { return view('home'); });

// todo: These 2 should be handled by the account controller
Route::get('/login', function () {
    return view('account.login');
})->name('login'); // Add ->name('login') here

Route::get('/register', function() {
    return view('account.register');
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

// account
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

// UPDATED Admin Routes: Remove authentication for direct access
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        // Redirect to products index, or show a dashboard view
        return redirect()->route('admin.products.index');
    })->name('dashboard');

    // Product Catalogue Management Routes using Route::resource
    Route::resource('products', ProductController::class);

    // You can add routes for category management here later
    // Route::resource('categories', AdminCategoryController::class);
});

