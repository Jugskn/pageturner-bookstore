<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\TwoFactorController;


/*
|--------------------------------------------------------------------------
| Public Routes (visitors can access)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('books', BookController::class)->only([
    'index', 'show'
]);

Route::resource('categories', CategoryController::class)->only([
    'index', 'show'
]);


/*
|--------------------------------------------------------------------------
| Two-Factor Authentication
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/two-factor-challenge', [TwoFactorController::class, 'challenge'])
        ->name('two-factor.challenge');
    Route::post('/two-factor-challenge', [TwoFactorController::class, 'verify'])
        ->name('two-factor.verify');
    Route::post('/two-factor-challenge/resend', [TwoFactorController::class, 'resend'])
        ->name('two-factor.resend');
});


/*
|--------------------------------------------------------------------------
| Authenticated (but NOT requiring verified email)
| Profile must stay accessible so users can fix email typos
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard — works for both verified and unverified (shows account status)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (edit name, email, password, 2FA)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 2FA toggle from profile
    Route::post('/two-factor/enable', [TwoFactorController::class, 'enable'])
        ->name('two-factor.enable');
    Route::post('/two-factor/disable', [TwoFactorController::class, 'disable'])
        ->name('two-factor.disable');
});


/*
|--------------------------------------------------------------------------
| Authenticated + Verified + 2FA Users (customers + admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', '2fa'])->group(function () {

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{book}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{book}', [CartController::class, 'remove'])->name('cart.remove');

    // Orders
    Route::resource('orders', OrderController::class)->only([
        'index', 'store', 'show'
    ]);
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])
        ->name('orders.cancel');
    Route::patch('/orders/{order}/receive', [OrderController::class, 'receive'])
        ->name('orders.receive');
    Route::post('/orders/{order}/buy-again', [OrderController::class, 'buyAgain'])
        ->name('orders.buyAgain');

    // Reviews (per order — so the order gets marked completed after)
    Route::get('/orders/{order}/review', [ReviewController::class, 'create'])
        ->name('orders.review');
    Route::post('/orders/{order}/review', [ReviewController::class, 'store'])
        ->name('orders.review.store');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.markAsRead');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.markAllAsRead');
});


/*
|--------------------------------------------------------------------------
| Admin Only
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', '2fa', 'role:admin'])->prefix('admin')->group(function () {

    // Admin dashboard
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

    // Admin book management
    Route::get('/books', [BookController::class, 'adminIndex'])->name('admin.books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('admin.books.create');
    Route::post('/books', [BookController::class, 'store'])->name('admin.books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('admin.books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('admin.books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('admin.books.destroy');
    Route::patch('/books/{book}/status', [BookController::class, 'toggleStatus'])->name('admin.books.toggleStatus');

    // Category CRUD
    Route::resource('categories', CategoryController::class)
        ->except(['index', 'show']);

    // View all orders
    Route::get('/orders', [OrderController::class, 'adminIndex'])
        ->name('admin.orders.index');

    // View single order
    Route::get('/orders/{order}', [OrderController::class, 'adminShow'])
        ->name('admin.orders.show');

    // Update order status
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->name('admin.orders.updateStatus');
});


/*
|--------------------------------------------------------------------------
| Breeze Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
