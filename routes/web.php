<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KokiController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\makananController;

Route::get('/', function () {
    if(!AuthController::checkAuth()){
        return to_route('login');
    }
});

// Rute autentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Contoh rute terproteksi
Route::middleware(['auth'])->group(function () {

    Route::prefix('admin')->middleware('checklevel:1')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('users', AdminController::class);
        Route::get('/makanans/create', [makananController::class, 'create'])->name('makanans.create');
        Route::post('/makanans', [makananController::class, 'store'])->name('makanans.store');
        Route::get('/makanans/{makanan}/edit', [makananController::class, 'edit'])->name('makanans.edit');
        Route::put('/makanans/{makanan}', [makananController::class, 'update'])->name('makanans.update');
        Route::delete('/makanans/{makanan}', [makananController::class, 'destroy'])->name('makanans.destroy');
    });

    Route::prefix('kasir')->name('kasir.')->middleware(['auth', 'checklevel:2'])->group(function () {
        Route::get('/dashboard', [KasirController::class, 'dashboard'])->name('dashboard');
        Route::resource('orders', \App\Http\Controllers\KasirController::class)
            ->except(['edit', 'update', 'destroy'])
            ->names([
                'index' => 'orders.index',
                'create' => 'orders.create',
                'store' => 'orders.store',
                'show' => 'orders.show'
            ]);
        Route::post('/transaksi/{order}', [KasirController::class, 'createTransaksi'])->name('transaksi.create');
        Route::get('/orders/riwayat', [KasirController::class, 'riwayatOrder'])->name('orders.riwayats');
        Route::post('orders/{order}/complete', [KasirController::class, 'completeOrder'])
            ->name('orders.complete');
    });

    Route::prefix('koki')->middleware('checklevel:3')->name('koki.')->group(function () {
        Route::get('/dashboard', [KokiController::class, 'dashboard'])->name('koki.dashboard');
        Route::get('/orders', [KokiController::class, 'orderList'])->name('koki.orders');
        Route::patch('/orders/{detailOrder}', [KokiController::class, 'updateStatus'])->name('koki.orders.update');
    });

    Route::get('/daftar/makanans', [makananController::class, 'index'])->name('daftar.makanans');
});
