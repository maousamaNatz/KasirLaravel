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
        Route::resource('makanans', AdminController::class, [
            'names' => [
                'index' => 'makanans.index',
                'create' => 'makanans.create',
                'store' => 'makanans.store',
                'edit' => 'makanans.edit',
                'update' => 'makanans.update',
                'destroy' => 'makanans.destroy'
            ],
            'except' => ['show']
        ]);
        Route::get('/makanans/{makanan}/edit', [MakananController::class, 'edit'])->name('admin.makanans.edit');
        Route::put('/makanans/{makanan}', [MakananController::class, 'update'])->name('admin.makanans.update');
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

        // Orders routes
        Route::get('/orders', [KasirController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [KasirController::class, 'create'])->name('orders.create');
        Route::post('/orders', [KasirController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [KasirController::class, 'show'])->name('orders.show');
        Route::get('/orders/{id_order}/invoice', [KasirController::class, 'invoice'])->name('orders.invoice');
        Route::post('/orders/{order}/complete', [KasirController::class, 'completeOrder'])->name('orders.complete');
        Route::get('/riwayat-order', [KasirController::class, 'riwayatOrder'])->name('orders.riwayat');
        Route::get('/riwayat-transaksi', [KasirController::class, 'riwayatTransaksi'])->name('transaksi.riwayat');
        Route::post('/orders/{order}/pembayaran', [KasirController::class, 'prosesPembayaran'])
            ->name('orders.pembayaran');
        Route::get('/orders/{order}/pembayaran', [KasirController::class, 'showPembayaran'])
            ->name('orders.pembayaran.show');
    });

    Route::prefix('koki')->middleware('checklevel:3')->name('koki.')->group(function () {
        Route::get('/dashboard', [KokiController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [KokiController::class, 'orderList'])->name('orders.index');
        Route::patch('/orders/{detailOrder}/update', [KokiController::class, 'updateStatus'])->name('orders.update');
    });

    Route::get('/daftar/makanans', [makananController::class, 'index'])->name('daftar.makanans');
});
