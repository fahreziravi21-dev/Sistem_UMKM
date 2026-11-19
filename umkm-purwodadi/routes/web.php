<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\InfowebController as AdminInfowebController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('beranda');

// Katalog & Pencarian AJAX
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
Route::get('/katalog/ajax-search', [KatalogController::class, 'ajaxSearch'])->name('katalog.ajax-search');
Route::get('/katalog/{id}', [KatalogController::class, 'detail'])->name('katalog.detail');

// Keranjang
Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
Route::match(['post', 'patch'], '/keranjang/update', [KeranjangController::class, 'update'])->name('keranjang.update');
Route::get('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
Route::get('/keranjang/kosongkan', [KeranjangController::class, 'kosongkan'])->name('keranjang.kosongkan');

// Tracking & Invoice (Akses publik via kode booking)
Route::get('/tracking', [BookingController::class, 'tracking'])->name('booking.tracking');
Route::get('/invoice/{kode_booking}', [BookingController::class, 'invoice'])->name('booking.invoice');

// Payment Gateway (QRIS buatqris.site & Midtrans Snap)
Route::get('/payment-gateway/qris/{kode_booking}', [PaymentGatewayController::class, 'getQrisData'])->name('payment.gateway.qris');
Route::get('/payment-gateway/check-status/{kode_booking}', [PaymentGatewayController::class, 'checkStatus'])->name('payment.gateway.check-status');
Route::match(['get', 'post'], '/payment-gateway/simulate-qris/{kode_booking}', [PaymentGatewayController::class, 'simulateQris'])->name('payment.gateway.simulate-qris');
Route::post('/payment-gateway/qris-webhook', [PaymentGatewayController::class, 'qrisWebhook'])->name('payment.gateway.qris-webhook');
Route::get('/payment-gateway/token/{kode_booking}', [PaymentGatewayController::class, 'getSnapToken'])->name('payment.gateway.token');
Route::match(['get', 'post'], '/payment-gateway/finish', [PaymentGatewayController::class, 'finish'])->name('payment.gateway.finish');
Route::match(['get', 'post'], '/payment-gateway/simulate/{kode_booking}', [PaymentGatewayController::class, 'simulate'])->name('payment.gateway.simulate');
Route::post('/payment-gateway/notification', [PaymentGatewayController::class, 'notification'])->name('payment.gateway.notification');

// Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout.post');

/*
|--------------------------------------------------------------------------
| Customer Authenticated Routes (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth.custom'])->group(function () {
    Route::get('/checkout', [KeranjangController::class, 'checkout'])->name('keranjang.checkout');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/pesanan-saya', [BookingController::class, 'riwayat'])->name('booking.riwayat');

    Route::get('/pembayaran/{id_booking}', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
});

/*
|--------------------------------------------------------------------------
| Admin Area Routes (Level: 'admin')
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD Produk
    Route::resource('produk', AdminProdukController::class)->except(['show']);

    // Kelola Booking/Pesanan
    Route::get('/booking', [AdminBookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{id}', [AdminBookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/{id}/status', [AdminBookingController::class, 'updateStatus'])->name('booking.status');

    // Verifikasi Pembayaran
    Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/{id}', [AdminPembayaranController::class, 'show'])->name('pembayaran.show');
    Route::post('/pembayaran/{id}/verifikasi', [AdminPembayaranController::class, 'verifikasi'])->name('pembayaran.verifikasi');

    // Pengaturan Web & Rekening UMKM
    Route::get('/infoweb', [AdminInfowebController::class, 'edit'])->name('infoweb.edit');
    Route::put('/infoweb', [AdminInfowebController::class, 'update'])->name('infoweb.update');

    // Laporan Penjualan
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
});
