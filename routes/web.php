<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [FoodController::class, 'index'])->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/menu', [FoodController::class, 'menu'])->name('menu.index');

// QR Dine-In Routes
Route::get('/dine-in', [App\Http\Controllers\DineInController::class, 'menu'])->name('dine-in.menu');
Route::post('/dine-in/order', [App\Http\Controllers\DineInController::class, 'placeOrder'])->name('dine-in.place');
Route::get('/dine-in/payment/{order}', [App\Http\Controllers\DineInController::class, 'payment'])->name('dine-in.payment');
Route::post('/dine-in/payment-success', [App\Http\Controllers\DineInController::class, 'paymentSuccess'])->name('dine-in.payment.success');

Route::get('/dashboard', function () {
    if (Auth::user()?->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/data', [CartController::class, 'data'])->name('cart.data');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

Route::middleware('auth')->group(function () {
    Route::get('/redirect', [AuthController::class, 'redirectToDashboard'])->name('auth.redirect');

    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/checkout', [OrderController::class, 'place'])->name('orders.place');
    Route::post('/payment-success', [OrderController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/orders/success/{order}', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('/my-orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/addresses', [ProfileController::class, 'storeAddress'])->name('profile.addresses.store');
    Route::delete('/profile/addresses/{address}', [ProfileController::class, 'deleteAddress'])->name('profile.addresses.delete');
    Route::patch('/profile/addresses/{address}/default', [ProfileController::class, 'setDefaultAddress'])->name('profile.addresses.default');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/analytics/revenue', [AdminController::class, 'revenueAnalytics'])->name('analytics.revenue');

    Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::patch('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.delete');

    Route::get('/foods', [AdminController::class, 'foods'])->name('foods.index');
    Route::post('/foods', [AdminController::class, 'storeFood'])->name('foods.store');
    Route::patch('/foods/{food}', [AdminController::class, 'updateFood'])->name('foods.update');
    Route::delete('/foods/{food}', [AdminController::class, 'deleteFood'])->name('foods.delete');

    Route::get('/coupons', [AdminController::class, 'coupons'])->name('coupons.index');
    Route::post('/coupons', [AdminController::class, 'storeCoupon'])->name('coupons.store');
    Route::delete('/coupons/{coupon}', [AdminController::class, 'deleteCoupon'])->name('coupons.delete');
    Route::patch('/coupons/{coupon}/toggle', [AdminController::class, 'toggleCoupon'])->name('coupons.toggle');

    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');

    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});

require __DIR__.'/auth.php';
