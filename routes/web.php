<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LandingPageSettingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\CouponController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

// Reviews and Discussions
Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
Route::post('/products/{product}/discussions', [DiscussionController::class, 'store'])->name('discussions.store');
Route::delete('/discussions/{discussion}', [DiscussionController::class, 'destroy'])->name('discussions.destroy');

// Authentication routes
Auth::routes();

// Routes requiring authentication
Route::middleware('auth')->group(function () {
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Coupon Routes
    Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
    Route::delete('/coupons', [CouponController::class, 'destroy'])->name('coupons.destroy');

    // Order Routes
    Route::get('/checkout', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/reviews/{product}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/discussions/{product}', [DiscussionController::class, 'store'])->name('discussions.store');
});

// Route sementara untuk check database
Route::get('/check-orders', function() {
    $columns = Schema::getColumnListing('orders');
    $structure = DB::select('DESCRIBE orders');
    
    return response()->json([
        'columns' => $columns,
        'structure' => $structure
    ]);
});

// Admin routes
Route::middleware(['auth', 'can:is-admin'])->prefix('admin')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Product Management
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Category Management
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Order Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Stock Management
    Route::get('/stock', [StockController::class, 'index'])->name('admin.stock.index');
    Route::put('/stock/{product}', [StockController::class, 'update'])->name('admin.stock.update');
    Route::put('/stock', [StockController::class, 'bulkUpdate'])->name('admin.stock.bulk-update');

    // Landing Page Settings
    Route::get('/landing-settings', [LandingPageSettingController::class, 'index'])->name('admin.landing.index');
    Route::put('/landing-settings', [LandingPageSettingController::class, 'update'])->name('admin.landing.update');

    // Coupon Management
        Route::resource('coupons', AdminCouponController::class)->names([
        'index' => 'admin.coupons.index',
        'create' => 'admin.coupons.create',
        'store' => 'admin.coupons.store',
        'edit' => 'admin.coupons.edit',
        'update' => 'admin.coupons.update',
        'destroy' => 'admin.coupons.destroy',
    ]);

    // Bank Account Management
    Route::resource('bank-accounts', \App\Http\Controllers\Admin\BankAccountController::class);
});