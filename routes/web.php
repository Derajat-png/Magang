<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Owner;
use App\Http\Controllers\Staff;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Client / Guest Routes
Route::get('/', [PublicController::class, 'index'])->name('landing');
Route::get('/public/umkm/{umkm}/catalog', [PublicController::class, 'catalog'])->name('public.umkm.catalog');
Route::post('/public/umkm/{umkm}/order', [PublicController::class, 'placeOrder'])->name('public.umkm.order');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register-umkm', [AuthController::class, 'showRegisterUmkm'])->name('register-umkm');
Route::post('/register-umkm', [AuthController::class, 'registerUmkm']);

// Super Admin Area
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // CRUD UMKM
    Route::resource('umkms', Admin\UmkmController::class);
    
    // Manage Users
    Route::resource('users', Admin\UserController::class);
    Route::patch('users/{user}/toggle-status', [Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
});

// Owner Area
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [Owner\DashboardController::class, 'index'])->name('dashboard');
    
    // UMKM Profile Edit
    Route::get('/profile', [Owner\UmkmProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [Owner\UmkmProfileController::class, 'update'])->name('profile.update');
    
    // CRUD Categories
    Route::resource('categories', Owner\CategoryController::class)->except(['create', 'show']);
    
    // CRUD Products
    Route::resource('products', Owner\ProductController::class)->except(['show']);
    
    // CRUD Staff
    Route::resource('staff', Owner\StaffController::class)->except(['show']);
    Route::patch('staff/{user}/toggle-status', [Owner\StaffController::class, 'toggleStatus'])->name('staff.toggle-status');
    
    // Orders View & Status Update
    Route::get('/orders', [Owner\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [Owner\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [Owner\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/orders/export/csv', [Owner\OrderController::class, 'exportCsv'])->name('orders.export');

    // Payments View
    Route::get('/payments', [Owner\PaymentController::class, 'index'])->name('payments.index');
});

// Staff/Kasir Area
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [Staff\DashboardController::class, 'index'])->name('dashboard');
    
    // Manage Orders
    Route::get('/orders', [Staff\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [Staff\OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [Staff\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [Staff\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [Staff\OrderController::class, 'updateStatus'])->name('orders.status');
    
    // Payment Process
    Route::post('/orders/{order}/payment', [Staff\PaymentController::class, 'store'])->name('orders.payment');
    Route::put('/payments/{payment}', [Staff\PaymentController::class, 'update'])->name('payments.update');
});
