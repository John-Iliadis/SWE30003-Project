<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () { return view('home'); });
Route::get('/home', function () { return view('home'); });

Route::get('/login', function () {
    return view('login');
})->name('login'); // Add ->name('login') here

Route::get('/register', function() {
    return view('register');
});

// todo: delete this
Route::get('/restart-session', [CartController::class, 'restartSession']);

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



Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', ProductController::class)
        ->names([
            'index' => 'admin.products.index',
            'create' => 'admin.products.create',
            'store' => 'admin.products.store',
            'edit' => 'admin.products.edit',
            'update' => 'admin.products.update',
            'destroy' => 'admin.products.destroy'
        ]);
});

// Admin Authentication Routes
// Comment out or delete these admin-specific routes
// Route::prefix('admin')->name('admin.')->group(function () {
//     Route::get('/register', [App\Http\Controllers\Admin\AuthController::class, 'showRegistrationForm'])->name('register');
//     Route::post('/register', [App\Http\Controllers\Admin\AuthController::class, 'register']);
//     Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
//     Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
//     Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
//     Route::get('/dashboard', function () {
//         return view('admin.dashboard');
//     })->middleware('auth.admin')->name('dashboard');
// });
