<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Infoweb;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * =======================================================================
 * QrisService - Payment Gateway Dynamic QRIS & buatqris.site Integration
 * =======================================================================
 *
 * Mendukung 2 mode:
 * 1. Mode API External (buatqris.site / KuroNeko API) jika API KEY tersedia.
 * 2. Mode Native Dynamic QRIS Generator (Standar EMVCo Bank Indonesia):
 *    Mengonversi QRIS Statis toko menjadi QRIS Dinamis ber-nominal otomatis,
 *    dapat dipindai langsung oleh BCA Mobile, BRImo, Livin', GoPay, OVO,
 *    DANA, ShopeePay, LinkAja, dan seluruh aplikasi perbankan/e-wallet Indonesia.
 * =======================================================================
 */
class QrisService
{
    protected string $staticQris;
    protected string $merchantName;
    protected string $merchantCity;
    protected ?string $buatqrisApiKey;
    protected string $buatqrisApiUrl;

    // QRIS Statis default resmi UMKM Purwodadi (LinkAja/GPN/NMID)
    protected const DEFAULT_STATIC_QRIS = '00020101021126580014ID.LINKAJA.WWW0118936009110022304896020910022304851450015ID.OR.GPNQR.WWW02150000000000000005204581253033605802ID5914UMKM PURWODADI6014LAMPUNG TENGAH6105341516304A1B2';

    public function __construct()
    {
        $infoweb = null;
        try {
            $infoweb = Infoweb::first();
        } catch (\Exception $e) {
            // ignore if database not loaded yet
        }

        // Prioritas QRIS Statis:
        // 1. Dari tabel infoweb (admin settings)
        // 2. Dari .env (QRIS_STATIC_CODE)
        // 3. Default fallback sample
        $this->staticQris = !empty($infoweb->qris_statis)
            ? trim($infoweb->qris_statis)
            : config('services.qris.static_code', self::DEFAULT_STATIC_QRIS);

        $this->merchantName = $infoweb->nama_web ?? config('services.qris.merchant_name', 'UMKM PURWODADI');
        $this->merchantCity = config('services.qris.merchant_city', 'LAMPUNG TENGAH');

        $this->buatqrisApiKey = config('services.buatqris.api_key', env('BUATQRIS_API_KEY'));
        $this->buatqrisApiUrl = config('services.buatqris.api_url', env('BUATQRIS_API_URL', 'https://app.buatqris.site/api'));
    }

    /**
     * Generate Dynamic QRIS dengan nominal otomatis untuk Booking tertentu.
     *
     * @param int|float $amount
     * @param string $invoiceId
     * @return array
     */
    public function generateDynamicQris($amount, string $invoiceId = ''): array
    {
        $amountInt = (int) round($amount);

        // 1. Coba panggil API buatqris.site jika API Key telah dikonfigurasi
        if (!empty($this->buatqrisApiKey)) {
            $externalResult = $this->callBuatQrisApi($amountInt, $invoiceId);
            if ($externalResult['success']) {
                return $externalResult;
            }
            Log::info('buatqris.site API call bypassed or failed, using native Dynamic QRIS engine.');
        }

        // 2. Gunakan Native Engine EMVCo TLV Generator (100% Offline & Standar Bank Indonesia)
        return $this->generateNativeDynamicQris($amountInt, $invoiceId);
    }

    /**
     * Panggil API eksternal buatqris.site
     */
    protected function callBuatQrisApi(int $amount, string $invoiceId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->buatqrisApiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->timeout(8)->post($this->buatqrisApiUrl . '/create-qris', [
                'api_key' => $this->buatqrisApiKey,
                'amount' => $amount,
                'invoice_id' => $invoiceId,
                'merchant_name' => $this->merchantName,
            ]);

            if ($response->successful()) {
                $json = $response->json();
                $qrisString = $json['data']['qris_string'] ?? $json['qris_string'] ?? null;
                $qrImageUrl = $json['data']['qr_image_url'] ?? $json['qr_image_url'] ?? null;

                if ($qrisString) {
                    if (!$qrImageUrl) {
                        $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=' . urlencode($qrisString);
                    }

                    return [
                        'success' => true,
                        'source' => 'buatqris.site',
                        'qris_string' => $qrisString,
                        'qr_image_url' => $qrImageUrl,
                        'amount' => $amount,
                        'invoice_id' => $invoiceId,
                        'merchant_name' => $this->merchantName,
                        'expired_minutes' => 30,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('buatqris.site API Exception: ' . $e->getMessage());
        }

        return ['success' => false];
    }

    /**
     * Generate Dynamic QRIS menggunakan standar algoritma EMVCo TLV + CRC16-CCITT.
     */
    public function generateNativeDynamicQris(int $amount, string $invoiceId = ''): array
    {
        try {
            $staticQris = trim($this->staticQris);
            if (empty($staticQris)) {
                $staticQris = self::DEFAULT_STATIC_QRIS;
            }

            // 1. Buang CRC lama di akhir string QRIS (4 karakter setelah '6304')
            $pos63 = strrpos($staticQris, '6304');
            if ($pos63 !== false) {
                $qrisWithoutCRC = substr($staticQris, 0, $pos63);
            } else {
                $qrisWithoutCRC = substr($staticQris, 0, -4);
            }

            // 2. Ubah Point of Initiation dari Static (010211) menjadi Dynamic (010212)
            $qrisWithoutCRC = preg_replace('/010211/', '010212', $qrisWithoutCRC, 1);

            // 3. Format Tag 54 (Transaction Amount)
            $amountStr = (string) $amount;
            $tag54 = '54' . str_pad(strlen($amountStr), 2, '0', STR_PAD_LEFT) . $amountStr;

            // 4. Sisipkan Tag 54 sebelum Tag 58 (Country Code ID)
            $parts = explode('5802ID', $qrisWithoutCRC);
            if (count($parts) >= 2) {
                $rebuilt = $parts[0] . $tag54 . '5802ID' . $parts[1];
            } else {
                $rebuilt = $qrisWithoutCRC . $tag54;
            }

            // 5. Tambahkan Tag 62 (Additional Data / Invoice Reference) jika ada invoiceId
            if (!empty($invoiceId)) {
                $ref = mb_substr($invoiceId, 0, 20);
                $sub05 = '05' . str_pad(strlen($ref), 2, '0', STR_PAD_LEFT) . $ref;
                $tag62 = '62' . str_pad(strlen($sub05), 2, '0', STR_PAD_LEFT) . $sub05;
                $rebuilt .= $tag62;
            }

            // 6. Siapkan string untuk kalkulasi CRC16
            $toHash = $rebuilt . '6304';

            // 7. Kalkulasi CRC16-CCITT (Polynomial 0x1021, Initial 0xFFFF)
            $crc = 0xFFFF;
            for ($i = 0; $i < strlen($toHash); $i++) {
                $crc ^= (ord($toHash[$i]) << 8);
                for ($j = 0; $j < 8; $j++) {
                    if ($crc & 0x8000) {
                        $crc = (($crc << 1) ^ 0x1021) & 0xFFFF;
                    } else {
                        $crc = ($crc << 1) & 0xFFFF;
                    }
                }
            }
            $hexCRC = strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));

            $dynamicQris = $toHash . $hexCRC;

            // 8. Generate QR Code Image URL (menggunakan QR Server CDN yang cepat & bebas CORS)
            $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&margin=10&data=' . urlencode($dynamicQris);
            $qrImageUrlAlt = 'https://chart.googleapis.com/chart?cht=qr&chs=400x400&chl=' . urlencode($dynamicQris);

            return [
                'success' => true,
                'source' => 'native_emvco',
                'qris_string' => $dynamicQris,
                'qr_image_url' => $qrImageUrl,
                'qr_image_url_alt' => $qrImageUrlAlt,
                'amount' => $amount,
                'invoice_id' => $invoiceId,
                'merchant_name' => $this->merchantName,
                'expired_minutes' => 30,
            ];
        } catch (\Exception $e) {
            Log::error('Native QRIS Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal membuat QRIS dinamis: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Memproses simulasi pembayaran QRIS sukses (untuk demo sidang / sandbox testing).
     */
    public function simulateSuccess(Booking $booking): bool
    {
        $booking->status_booking = 'dibayar';
        $booking->metode_pembayaran = 'payment_gateway';
        $booking->save();

        Pembayaran::updateOrCreate(
            ['id_booking' => $booking->id_booking],
            [
                'transaksi_id' => 'QRIS-' . strtoupper(uniqid()),
                'metode' => 'qris_buatqris',
                'bank_pengirim' => 'QRIS (buatqris.site / GoPay / OVO / DANA)',
                'nama_rekening' => $booking->nama_penerima,
                'no_rekening' => 'QRIS-NATIONAL-ID',
                'nominal' => $booking->total_harga,
                'tgl_pembayaran' => now(),
                'bukti' => 'qris_payment_verified.png',
                'status_konfirmasi' => 'terverifikasi',
                'catatan_admin' => 'Pembayaran otomatis terverifikasi via QRIS Dinamis (buatqris.site)',
            ]
        );

        return true;
    }

    /**
     * Handle Webhook notifikasi jika buatqris.site atau payment gateway mengirimkan status lunas.
     */
    public function handleWebhook(array $payload): array
    {
        $invoiceId = $payload['invoice_id'] ?? $payload['order_id'] ?? $payload['partner_trx_id'] ?? null;
        $status = strtolower($payload['status'] ?? $payload['transaction_status'] ?? '');

        if (!$invoiceId) {
            return ['status' => false, 'message' => 'Invoice ID tidak ditemukan dalam payload callback'];
        }

        $booking = Booking::where('kode_booking', $invoiceId)->first();
        if (!$booking) {
            return ['status' => false, 'message' => 'Booking tidak ditemukan'];
        }

        if (in_array($status, ['success', 'paid', 'settlement', 'lunas'])) {
            $this->simulateSuccess($booking);
            return ['status' => true, 'message' => 'Pembayaran berhasil diselesaikan'];
        }

        return ['status' => true, 'message' => 'Status diterima: ' . $status];
    }
}
