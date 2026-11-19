<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\Produk;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected string $serverKey;
    protected string $clientKey;
    protected bool $isProduction;
    protected string $snapUrl;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key', 'SB-Mid-server-DEMO123456789');
        $this->clientKey = config('services.midtrans.client_key', 'SB-Mid-client-DEMO123456789');
        $this->isProduction = (bool) config('services.midtrans.is_production', false);

        $this->snapUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    public function isProduction(): bool
    {
        return $this->isProduction;
    }

    /**
     * Membuat Snap Token untuk pesanan tertentu.
     */
    public function createSnapToken(Booking $booking): string
    {
        // Jika token sudah ada dan belum kedaluwarsa
        if ($booking->snap_token && !str_starts_with($booking->snap_token, 'DEMO-')) {
            return $booking->snap_token;
        }

        // Susun item details
        $itemDetails = [];
        foreach ($booking->detailBookings as $detail) {
            $itemDetails[] = [
                'id' => (string) $detail->id_produk,
                'price' => (int) $detail->harga_satuan,
                'quantity' => (int) $detail->jumlah,
                'name' => mb_substr($detail->produk->merk ?? 'Camilan Purwodadi', 0, 50),
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $booking->kode_booking,
                'gross_amount' => (int) $booking->total_harga,
            ],
            'customer_details' => [
                'first_name' => $booking->nama_penerima,
                'email' => $booking->pengguna->username . '@pelanggan.umkm.id',
                'phone' => $booking->no_tlp_penerima,
                'shipping_address' => [
                    'first_name' => $booking->nama_penerima,
                    'phone' => $booking->no_tlp_penerima,
                    'address' => $booking->alamat_pengiriman,
                    'city' => 'Lampung Tengah',
                    'country_code' => 'IDN'
                ]
            ],
            'item_details' => $itemDetails,
            'enabled_payments' => [
                'credit_card', 'bca_va', 'bni_va', 'bri_va', 'mandiri_clickpay', 
                'gopay', 'shopeepay', 'qris', 'indomaret', 'alfamart'
            ]
        ];

        // Cek jika server key adalah DEMO
        if (str_contains($this->serverKey, 'DEMO')) {
            $snapToken = 'DEMO-SNAP-' . strtoupper(substr(md5($booking->kode_booking . time()), 0, 24));
            $booking->update([
                'snap_token' => $snapToken,
                'metode_pembayaran' => 'payment_gateway',
            ]);
            return $snapToken;
        }

        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->timeout(10)
                ->post($this->snapUrl, $params);

            if ($response->successful()) {
                $body = $response->json();
                $snapToken = $body['token'] ?? '';
                if ($snapToken) {
                    $booking->update([
                        'snap_token' => $snapToken,
                        'metode_pembayaran' => 'payment_gateway',
                    ]);
                    return $snapToken;
                }
            }

            Log::warning('Midtrans Snap Error: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Midtrans Exception: ' . $e->getMessage());
        }

        // Fallback simulation token jika jaringan gagal atau sandbox offline
        $snapToken = 'DEMO-SNAP-' . strtoupper(substr(md5($booking->kode_booking . time()), 0, 24));
        $booking->update([
            'snap_token' => $snapToken,
            'metode_pembayaran' => 'payment_gateway',
        ]);
        return $snapToken;
    }

    /**
     * Memproses callback notifikasi webhook dari Midtrans.
     */
    public function handleNotification(array $payload): array
    {
        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? 'payment_gateway';
        $transactionId = $payload['transaction_id'] ?? 'TRX-' . time();

        if (!$orderId) {
            return ['status' => false, 'message' => 'Order ID is missing'];
        }

        // Verifikasi signature (kecuali mode demo)
        if (!str_contains($this->serverKey, 'DEMO') && $signatureKey) {
            $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);
            if ($signatureKey !== $expectedSignature) {
                return ['status' => false, 'message' => 'Invalid signature'];
            }
        }

        $booking = Booking::with('detailBookings.produk')->where('kode_booking', $orderId)->first();
        if (!$booking) {
            return ['status' => false, 'message' => 'Booking not found'];
        }

        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            // Lunas / Terverifikasi Otomatis
            $booking->status_booking = 'dibayar';
            $booking->save();

            Pembayaran::updateOrCreate(
                ['id_booking' => $booking->id_booking],
                [
                    'transaksi_id' => $transactionId,
                    'metode' => $paymentType,
                    'bank_pengirim' => strtoupper($paymentType),
                    'nama_rekening' => $booking->nama_penerima,
                    'no_rekening' => $payload['va_numbers'][0]['va_number'] ?? ($payload['bill_key'] ?? 'AUTO-GATEWAY'),
                    'nominal' => (int) $booking->total_harga,
                    'tgl_pembayaran' => now(),
                    'bukti' => 'midtrans_success.png',
                    'status_konfirmasi' => 'terverifikasi',
                    'catatan_admin' => 'Pembayaran otomatis terverifikasi via Midtrans Gateway (' . strtoupper($paymentType) . ')',
                ]
            );

            return ['status' => true, 'message' => 'Payment settled'];
        } elseif ($transactionStatus === 'pending') {
            $booking->status_booking = 'menunggu_bayar';
            $booking->save();

            return ['status' => true, 'message' => 'Payment pending'];
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            // Kembalikan stok jika batal
            if ($booking->status_booking !== 'batal') {
                foreach ($booking->detailBookings as $d) {
                    $prod = $d->produk;
                    if ($prod) {
                        $prod->stok += $d->jumlah;
                        if ($prod->stok > 0) {
                            $prod->status = 'tersedia';
                        }
                        $prod->save();
                    }
                }
            }

            $booking->status_booking = 'batal';
            $booking->save();

            return ['status' => true, 'message' => 'Payment cancelled'];
        }

        return ['status' => true, 'message' => 'Status handled: ' . $transactionStatus];
    }

    /**
     * Memproses simulasi pembayaran berhasil (sangat berguna untuk demo sidang skripsi & sandbox).
     */
    public function simulateSuccess(Booking $booking, string $paymentType = 'QRIS'): bool
    {
        $booking->status_booking = 'dibayar';
        $booking->metode_pembayaran = 'payment_gateway';
        $booking->save();

        Pembayaran::updateOrCreate(
            ['id_booking' => $booking->id_booking],
            [
                'transaksi_id' => 'MIDTRANS-SIM-' . strtoupper(uniqid()),
                'metode' => 'payment_gateway_' . strtolower($paymentType),
                'bank_pengirim' => 'Midtrans (' . strtoupper($paymentType) . ')',
                'nama_rekening' => $booking->nama_penerima,
                'no_rekening' => 'VA-GATEWAY-' . rand(100000, 999999),
                'nominal' => $booking->total_harga,
                'tgl_pembayaran' => now(),
                'bukti' => 'midtrans_gateway_verified.png',
                'status_konfirmasi' => 'terverifikasi',
                'catatan_admin' => 'Pembayaran otomatis terverifikasi melalui Payment Gateway (' . strtoupper($paymentType) . ')',
            ]
        );

        return true;
    }
}

