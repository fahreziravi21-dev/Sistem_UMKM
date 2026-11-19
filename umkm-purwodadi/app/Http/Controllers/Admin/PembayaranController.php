<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Booking;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with(['booking.pengguna']);

        if ($request->filled('status') && in_array($request->status, ['menunggu', 'terverifikasi', 'ditolak'])) {
            $query->where('status_konfirmasi', $request->status);
        }

        $pembayarans = $query->orderBy('id_pembayaran', 'desc')->paginate(10)->withQueryString();

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with(['booking.pengguna', 'booking.detailBookings.produk'])->findOrFail($id);

        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status_konfirmasi' => 'required|in:terverifikasi,ditolak',
            'catatan_admin' => 'nullable|string',
        ]);

        $pembayaran = Pembayaran::with('booking')->findOrFail($id);
        $pembayaran->status_konfirmasi = $request->status_konfirmasi;
        $pembayaran->catatan_admin = $request->catatan_admin;
        $pembayaran->save();

        // Update related booking status
        $booking = $pembayaran->booking;
        if ($booking) {
            if ($request->status_konfirmasi === 'terverifikasi') {
                $booking->status_booking = 'dibayar';
            } elseif ($request->status_konfirmasi === 'ditolak') {
                $booking->status_booking = 'menunggu_bayar';
            }
            $booking->save();
        }

        return redirect()->route('admin.pembayaran.index')
            ->with('success', "Status konfirmasi pembayaran berhasil diperbarui menjadi {$request->status_konfirmasi}.");
    }
}

