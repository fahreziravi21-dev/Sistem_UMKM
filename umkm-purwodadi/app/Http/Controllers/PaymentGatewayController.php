<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\MidtransService;
use App\Services\QrisService;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    protected MidtransService $midtransService;
    protected QrisService $qrisService;

    public function __construct(MidtransService $midtransService, QrisService $qrisService)
    {
        $this->midtransService = $midtransService;
        $this->qrisService = $qrisService;
    }

    /**
     * Mengambil data Dynamic QRIS untuk tagihan pemesanan (buatqris.site / EMVCo).
     */
    public function getQrisData($kode_booking)
    {
        $booking = Booking::with(['detailBookings.produk', 'pengguna'])
            ->where('kode_booking', $kode_booking)
            ->firstOrFail();

        if ($booking->status_booking !== 'menunggu_bayar') {
            return response()->json([
                'success' => false,
                'status_booking' => $booking->status_booking,
                'message' => 'Pesanan ini sudah tidak dalam status menunggu pembayaran.'
            ], 400);
        }

        $qrisResult = $this->qrisService->generateDynamicQris($booking->total_harga, $booking->kode_booking);

        if (!$qrisResult['success']) {
            return response()->json([
                'success' => false,
                'message' => $qrisResult['message'] ?? 'Gagal membuat QRIS dinamis.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'source' => $qrisResult['source'] ?? 'buatqris.site',
            'qris_string' => $qrisResult['qris_string'],
            'qr_image_url' => $qrisResult['qr_image_url'],
            'qr_image_url_alt' => $qrisResult['qr_image_url_alt'] ?? null,
            'amount' => (int) $booking->total_harga,
            'amount_formatted' => 'Rp ' . number_format($booking->total_harga, 0, ',', '.'),
            'kode_booking' => $booking->kode_booking,
            'nama_penerima' => $booking->nama_penerima,
            'expired_minutes' => 30,
        ]);
    }

    /**
     * Mengecek status pembayaran pesanan secara real-time (AJAX Polling dari Frontend).
     */
    public function checkStatus($kode_booking)
    {
        $booking = Booking::where('kode_booking', $kode_booking)->firstOrFail();

        return response()->json([
            'success' => true,
            'status_booking' => $booking->status_booking,
            'is_paid' => in_array($booking->status_booking, ['dibayar', 'dikirim', 'selesai']),
        ]);
    }

    /**
     * Simulasi pembayaran QRIS sukses (untuk sidang skripsi / offline testing).
     */
    public function simulateQris(Request $request, $kode_booking)
    {
        $booking = Booking::where('kode_booking', $kode_booking)->firstOrFail();
        $this->qrisService->simulateSuccess($booking);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Simulasi pembayaran QRIS berhasil! Status pesanan kini menjadi Dibayar.',
                'redirect_url' => route('booking.invoice', $booking->kode_booking),
            ]);
        }

        return redirect()->route('booking.invoice', $booking->kode_booking)
            ->with('success', 'Simulasi pembayaran via QRIS berhasil! Pesanan Anda telah terverifikasi secara otomatis.');
    }

    /**
     * Webhook notifikasi dari buatqris.site callback API.
     */
    public function qrisWebhook(Request $request)
    {
        $payload = $request->all();
        $result = $this->qrisService->handleWebhook($payload);

        return response()->json($result);
    }

    /**
     * Mengambil Snap Token untuk dipanggil oleh Snap Popup di antarmuka web (Midtrans).
     */
    public function getSnapToken($kode_booking)
    {
        $booking = Booking::with(['detailBookings.produk', 'pengguna'])
            ->where('kode_booking', $kode_booking)
            ->firstOrFail();

        if ($booking->status_booking !== 'menunggu_bayar') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini sudah tidak dalam status menunggu pembayaran.'
            ], 400);
        }

        $snapToken = $this->midtransService->createSnapToken($booking);

        return response()->json([
            'success' => true,
            'snap_token' => $snapToken,
            'client_key' => $this->midtransService->getClientKey(),
            'is_production' => $this->midtransService->isProduction(),
            'is_demo' => str_starts_with($snapToken, 'DEMO-'),
            'booking' => [
                'kode_booking' => $booking->kode_booking,
                'total_harga' => $booking->total_harga,
                'total_harga_formatted' => 'Rp ' . number_format($booking->total_harga, 0, ',', '.'),
                'nama_penerima' => $booking->nama_penerima,
            ]
        ]);
    }

    /**
     * Handler saat pembayaran selesai di frontend (Snap onSuccess).
     */
    public function finish(Request $request)
    {
        $orderId = $request->query('order_id') ?? $request->input('order_id');
        $booking = Booking::where('kode_booking', $orderId)->first();

        if ($booking) {
            $paymentType = $request->input('payment_type', 'QRIS / Virtual Account');
            $this->midtransService->simulateSuccess($booking, $paymentType);

            return redirect()->route('booking.invoice', $booking->kode_booking)
                ->with('success', 'Pembayaran melalui Payment Gateway berhasil diselesaikan! Pesanan Anda segera diproses.');
        }

        return redirect()->route('beranda')->with('info', 'Status pembayaran telah diperbarui.');
    }

    /**
     * Simulasi pembayaran gateway sukses (Midtrans).
     */
    public function simulate(Request $request, $kode_booking)
    {
        $booking = Booking::where('kode_booking', $kode_booking)->firstOrFail();

        $paymentType = $request->input('payment_type', 'QRIS');
        $this->midtransService->simulateSuccess($booking, $paymentType);

        return redirect()->route('booking.invoice', $booking->kode_booking)
            ->with('success', "Simulasi pembayaran otomatis via Payment Gateway ({$paymentType}) berhasil! Pesanan Anda kini berstatus 'Dibayar'.");
    }

    /**
     * Webhook HTTP Notification dari Midtrans Server.
     */
    public function notification(Request $request)
    {
        $payload = $request->all();
        $result = $this->midtransService->handleNotification($payload);

        return response()->json($result);
    }
}
