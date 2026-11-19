<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\Infoweb;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function create($id_booking)
    {
        if (!session()->has('pengguna')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $booking = Booking::with('pembayaran')->findOrFail($id_booking);
        $pengguna = session('pengguna');

        // Check ownership unless admin
        if ($pengguna['level'] !== 'admin' && $booking->id_pengguna != $pengguna['id_pengguna']) {
            return redirect()->route('beranda')->with('error', 'Akses tidak sah ke pesanan ini.');
        }

        $infoweb = Infoweb::first();

        return view('pembayaran.create', compact('booking', 'infoweb'));
    }

    public function store(Request $request)
    {
        if (!session()->has('pengguna')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'id_booking' => 'required|exists:bookings,id_booking',
            'no_rekening' => 'required|string|max:30',
            'nama_rekening' => 'required|string|max:100',
            'bank_pengirim' => 'required|string|max:50',
            'nominal' => 'required|numeric|min:1000',
            'bukti' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'bukti.required' => 'File bukti transfer wajib diunggah.',
            'bukti.image' => 'File bukti transfer harus berupa gambar (JPG, PNG, atau WEBP).',
            'bukti.max' => 'Ukuran file bukti transfer maksimal 2MB.',
        ]);

        $booking = Booking::findOrFail($request->id_booking);

        // Upload file
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $fileName = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/bukti');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $fileName);
        } else {
            return back()->with('error', 'Gagal mengunggah foto bukti pembayaran.');
        }

        // Save or update pembayaran
        $pembayaran = Pembayaran::updateOrCreate(
            ['id_booking' => $booking->id_booking],
            [
                'no_rekening' => $request->no_rekening,
                'nama_rekening' => $request->nama_rekening,
                'bank_pengirim' => $request->bank_pengirim,
                'nominal' => $request->nominal,
                'tgl_pembayaran' => now(),
                'bukti' => $fileName,
                'status_konfirmasi' => 'menunggu',
                'catatan_admin' => null,
            ]
        );

        return redirect()->route('booking.invoice', $booking->kode_booking)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Mohon tunggu verifikasi oleh pihak admin.');
    }
}

