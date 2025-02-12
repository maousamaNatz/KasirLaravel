<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KokiController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\MakananController;

// Rute utama untuk redirect berdasarkan status autentikasi dan level user
Route::get('/', function () {
    return auth()->check() ? redirect()->intended('/'.auth()->user()->level->nama_level)
                          : redirect()->route('login');
});

// Rute autentikasi
Route::get('/login', [AuthController::class, 'index'])->name('login'); // Menampilkan form login
Route::post('/login', [AuthController::class, 'login']); // Memproses data login
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Memproses logout

// Grup rute yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {
    // Grup rute admin dengan middleware pengecekan role
    Route::prefix('admin')->middleware(['auth', 'checkroles:admin'])->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('users', AdminController::class); // CRUD manajemen user
        Route::resource('makanans', MakananController::class)->except(['show']); // CRUD menu makanan
    });

    // Grup rute kasir dengan middleware pengecekan role
    Route::prefix('kasir')->middleware(['auth', 'checkroles:kasir'])->name('kasir.')->group(function () {
        Route::get('/dashboard', [KasirController::class, 'index'])->name('dashboard'); // Dashboard kasir
        Route::resource('orders', KasirController::class)->except(['edit', 'update', 'destroy']); // Manajemen order

        // Rute khusus proses transaksi
        Route::post('/transaksi/{order}', [KasirController::class, 'createTransaksi'])->name('transaksi.create'); // Membuat transaksi
        Route::get('/riwayat-order', [KasirController::class, 'riwayatOrder'])->name('orders.riwayat'); // Riwayat order
        Route::get('/riwayat-transaksi', [KasirController::class, 'riwayatTransaksi'])->name('transaksi.riwayat'); // Riwayat transaksi
        Route::post('/orders/{order}/complete', [KasirController::class, 'completeOrder'])->name('orders.complete'); // Menyelesaikan order
        Route::get('/orders/{id_order}/invoice', [KasirController::class, 'invoice'])->name('orders.invoice'); // Cetak invoice
        Route::match(['get', 'post'], '/orders/{order}/pembayaran', [KasirController::class, 'prosesPembayaran'])
            ->name('orders.pembayaran'); // Proses pembayaran
    });

    // Grup rute koki dengan middleware pengecekan role
    Route::prefix('koki')->middleware(['auth', 'checkroles:koki'])->name('koki.')->group(function () {
        Route::get('/dashboard', [KokiController::class, 'dashboard'])->name('dashboard'); // Dashboard koki
        Route::get('/orders', [KokiController::class, 'orderList'])->name('orders.index'); // Daftar order masuk
        Route::patch('/orders/{detailOrder}/update', [KokiController::class, 'updateStatus'])->name('orders.update'); // Update status order
    });

    // Rute umum yang bisa diakses semua role terautentikasi
    Route::get('/daftar/makanans', [MakananController::class, 'index'])->name('daftar.makanans'); // Daftar menu makanan
});
