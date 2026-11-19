<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal', date('Y-m-01'));
        $sampaiTanggal = $request->input('sampai_tanggal', date('Y-m-d'));

        $query = Booking::with(['pengguna', 'detailBookings.produk', 'pembayaran'])
            ->where('status_booking', 'selesai')
            ->whereDate('tgl_booking', '>=', $dariTanggal)
            ->whereDate('tgl_booking', '<=', $sampaiTanggal);

        $laporans = $query->orderBy('tgl_booking', 'asc')->get();

        $totalPendapatan = $laporans->sum('total_harga');
        $totalTransaksi = $laporans->count();

        return view('admin.laporan.index', compact(
            'laporans',
            'totalPendapatan',
            'totalTransaksi',
            'dariTanggal',
            'sampaiTanggal'
        ));
    }
}

