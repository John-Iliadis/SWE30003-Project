<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController; // Good, this is imported
use App\Http\Controllers\CommerceController;
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
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Registration Routes - URLs will be /admin/register
    Route::get('/register', [AdminAuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AdminAuthController::class, 'register'])->name('register.post'); // This was already correct

    // Admin Login Routes - URLs will be /admin/login
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post'); // This was already correct
    
    // Admin Logout Route - URL will be /admin/logout
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout'); // This was already correct

    // Protected Admin Routes (Example: Dashboard)
    // You'll need to create a DashboardController or similar for this
    // And ensure the 'auth:admin' middleware is correctly configured
    Route::middleware(['auth:admin'])->group(function () { // Add 'auth:admin' middleware here
        // Example: Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // For now, let's add a simple test route for the dashboard
        Route::get('/dashboard', function() {
            return "Admin Dashboard - Welcome " . auth()->guard('admin')->user()->name;
        })->name('dashboard');
    });
});
