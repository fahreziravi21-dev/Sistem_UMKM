<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Produk;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['pengguna', 'pembayaran']);

        if ($request->filled('status') && in_array($request->status, ['menunggu_bayar', 'dibayar', 'dikirim', 'selesai', 'batal'])) {
            $query->where('status_booking', $request->status);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('kode_booking', 'like', "%{$cari}%")
                  ->orWhere('nama_penerima', 'like', "%{$cari}%");
            });
        }

        $bookings = $query->orderBy('id_booking', 'desc')->paginate(10)->withQueryString();

        return view('admin.booking.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['pengguna', 'detailBookings.produk', 'pembayaran'])->findOrFail($id);

        return view('admin.booking.show', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_booking' => 'required|in:menunggu_bayar,dibayar,dikirim,selesai,batal',
        ]);

        $booking = Booking::with('detailBookings.produk')->findOrFail($id);
        $oldStatus = $booking->status_booking;
        $newStatus = $request->status_booking;

        // If booking is changed to batal and wasn't batal before, restore stock
        if ($newStatus === 'batal' && $oldStatus !== 'batal') {
            foreach ($booking->detailBookings as $detail) {
                $produk = $detail->produk;
                if ($produk) {
                    $produk->stok += $detail->jumlah;
                    if ($produk->stok > 0) {
                        $produk->status = 'tersedia';
                    }
                    $produk->save();
                }
            }
        }

        $booking->status_booking = $newStatus;
        $booking->save();

        return back()->with('success', "Status pesanan {$booking->kode_booking} berhasil diperbarui menjadi " . ucfirst(str_replace('_', ' ', $newStatus)) . ".");
    }
}

