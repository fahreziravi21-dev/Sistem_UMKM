<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\DetailBooking;
use App\Models\Produk;
use App\Models\Infoweb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        if (!session()->has('pengguna')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $keranjang = session()->get('keranjang', []);
        if (empty($keranjang)) {
            return redirect()->route('katalog.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        $request->validate([
            'nama_penerima' => 'required|string|max:100',
            'no_tlp_penerima' => 'required|string|max:15',
            'alamat_pengiriman' => 'required|string',
        ]);

        $pengguna = session()->get('pengguna');

        DB::beginTransaction();
        try {
            // Verify stock first
            $totalHarga = 0;
            foreach ($keranjang as $item) {
                $prod = Produk::lockForUpdate()->find($item['id_produk']);
                if (!$prod || $prod->stok < $item['jumlah']) {
                    DB::rollBack();
                    return back()->with('error', "Stok untuk {$item['merk']} tidak mencukupi (tersedia: " . ($prod ? $prod->stok : 0) . ").");
                }
                $totalHarga += $item['subtotal'];
            }

            // Create booking
            $kodeBooking = Booking::generateKodeBooking();
            $booking = Booking::create([
                'kode_booking' => $kodeBooking,
                'id_pengguna' => $pengguna['id_pengguna'],
                'nama_penerima' => $request->nama_penerima,
                'no_tlp_penerima' => $request->no_tlp_penerima,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'total_harga' => $totalHarga,
                'status_booking' => 'menunggu_bayar',
                'tgl_booking' => now(),
            ]);

            // Create detail_bookings and reduce stock
            foreach ($keranjang as $item) {
                DetailBooking::create([
                    'id_booking' => $booking->id_booking,
                    'id_produk' => $item['id_produk'],
                    'harga_satuan' => $item['harga'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                ]);

                $prod = Produk::find($item['id_produk']);
                $prod->stok -= $item['jumlah'];
                if ($prod->stok <= 0) {
                    $prod->status = 'habis';
                }
                $prod->save();
            }

            DB::commit();

            // Clear cart
            session()->forget('keranjang');

            return redirect()->route('booking.invoice', $booking->kode_booking)
                ->with('success', 'Pesanan Anda berhasil dibuat! Silakan lakukan pembayaran sesuai petunjuk.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
    }

    public function invoice($kode_booking)
    {
        $booking = Booking::with(['detailBookings.produk', 'pembayaran', 'pengguna'])
            ->where('kode_booking', $kode_booking)
            ->firstOrFail();

        $infoweb = Infoweb::first();

        return view('booking.invoice', compact('booking', 'infoweb'));
    }

    public function tracking(Request $request)
    {
        $booking = null;

        if ($request->filled('kode_booking')) {
            $kode = trim($request->kode_booking);
            $booking = Booking::with(['detailBookings.produk', 'pembayaran'])
                ->where('kode_booking', $kode)
                ->first();

            if (!$booking) {
                session()->flash('error', "Pesanan dengan kode booking '{$kode}' tidak ditemukan.");
            }
        }

        return view('booking.tracking', compact('booking'));
    }

    public function riwayat()
    {
        if (!session()->has('pengguna')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $idPengguna = session('pengguna')['id_pengguna'];
        $bookings = Booking::with(['detailBookings.produk', 'pembayaran'])
            ->where('id_pengguna', $idPengguna)
            ->orderBy('id_booking', 'desc')
            ->paginate(10);

        return view('booking.riwayat', compact('bookings'));
    }
}

