<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Produk;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Pendapatan dari booking yang selesai atau terverifikasi
        $totalPendapatan = Booking::whereIn('status_booking', ['dibayar', 'dikirim', 'selesai'])
            ->sum('total_harga');

        // Pesanan baru menunggu bayar
        $bookingBaru = Booking::where('status_booking', 'menunggu_bayar')->count();

        // Pembayaran menunggu verifikasi
        $pembayaranMenunggu = Pembayaran::where('status_konfirmasi', 'menunggu')->count();

        // Stok habis atau menipis
        $stokHabis = Produk::where('status', 'habis')->orWhere('stok', '<=', 0)->count();

        // Total seluruh produk
        $totalProduk = Produk::count();

        // 10 Pesanan terbaru
        $bookingTerbaru = Booking::with(['pengguna', 'pembayaran'])
            ->orderBy('id_booking', 'desc')
            ->take(10)
            ->get();

        // Produk dengan stok menipis (<= 10)
        $stokMenipis = Produk::where('stok', '<=', 10)
            ->orderBy('stok', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'totalPendapatan',
            'bookingBaru',
            'pembayaranMenunggu',
            'stokHabis',
            'totalProduk',
            'bookingTerbaru',
            'stokMenipis'
        ));
    }
}

