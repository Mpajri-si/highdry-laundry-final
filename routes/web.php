<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPromoController;
use App\Http\Controllers\PublicServiceController;
use Illuminate\Support\Facades\Route;

// Publik (Guest)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/layanan', [PublicServiceController::class, 'index'])->name('services.index');
Route::get('/promo', [PublicPromoController::class, 'index'])->name('promos.index');
Route::get('/layanan/{service}/pilih', [PublicServiceController::class, 'choose'])->name('services.choose');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Pelanggan
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/buat', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/pesanan', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/pesanan/{order}/batal', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/pesanan/{order}/nota', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [ProfileController::class, 'password'])->name('profile.password');
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('layanan', Admin\ServiceController::class)->parameters(['layanan' => 'service'])->names('services');
    Route::resource('promo', Admin\PromoController::class)->parameters(['promo' => 'promo'])->names('promos');
    Route::get('/pesanan', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/pesanan/{order}/verifikasi', [Admin\OrderController::class, 'verify'])->name('orders.verify');
    Route::patch('/pesanan/{order}/tolak', [Admin\OrderController::class, 'reject'])->name('orders.reject');
    Route::patch('/pesanan/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::resource('pelanggan', Admin\CustomerController::class)->parameters(['pelanggan' => 'customer'])->names('customers')->except(['create', 'store']);
    Route::get('/laporan', [Admin\ReportController::class, 'index'])->name('reports.index');
});
