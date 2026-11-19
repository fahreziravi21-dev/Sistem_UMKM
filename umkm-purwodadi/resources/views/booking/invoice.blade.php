@extends('layouts.app')

@section('title', 'Invoice Pesanan ' . $booking->kode_booking . ' - UMKM Purwodadi')

@section('styles')
<style>
    .payment-choice-card {
        transition: all 0.25s ease;
        border: 2px solid transparent;
        cursor: pointer;
    }
    .payment-choice-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
    }
    .payment-choice-card.border-qris {
        border-color: #dc2626 !important;
    }
    .payment-choice-card.border-primary {
        border-color: #0d6efd !important;
    }
    .payment-choice-card.border-warning {
        border-color: #ffc107 !important;
    }
    .qris-box {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 20px;
        display: inline-block;
        border: 2px solid #e2e8f0;
    }
    .qris-pulse {
        animation: pulseAnimation 2s infinite;
    }
    @keyframes pulseAnimation {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('booking.riwayat') }}">Pesanan Saya</a></li>
            <li class="breadcrumb-item active" aria-current="page">Invoice {{ $booking->kode_booking }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Invoice Card -->
            <div class="card shadow-sm border-0 mb-4" id="printableInvoice">
                <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 fw-bold"><i class="bi bi-receipt me-2"></i>INVOICE PEMESANAN</h4>
                        <small class="opacity-75">Sistem Informasi Pemasaran UMKM Dusun 01 Purwodadi</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-light text-dark fs-6 font-monospace">{{ $booking->kode_booking }}</span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Status Alert Banner -->
                    <div class="alert 
                        @if($booking->status_booking == 'menunggu_bayar') alert-warning
                        @elseif($booking->status_booking == 'dibayar') alert-info
                        @elseif($booking->status_booking == 'dikirim') alert-primary
                        @elseif($booking->status_booking == 'selesai') alert-success
                        @else alert-secondary @endif 
                        d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <strong>Status Pesanan:</strong>
                            <span class="badge 
                                @if($booking->status_booking == 'menunggu_bayar') bg-warning text-dark
                                @elseif($booking->status_booking == 'dibayar') bg-info text-dark
                                @elseif($booking->status_booking == 'dikirim') bg-primary
                                @elseif($booking->status_booking == 'selesai') bg-success
                                @else bg-danger @endif ms-2 fs-6">
                                {{ strtoupper(str_replace('_', ' ', $booking->status_booking)) }}
                            </span>
                        </div>
                        <div>
                            <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ date('d M Y, H:i', strtotime($booking->tgl_booking)) }} WIB</small>
                        </div>
                    </div>

                    <!-- Customer & Shipping Info -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-success mb-2"><i class="bi bi-geo-alt me-1"></i>Tujuan Pengiriman</h6>
                                <p class="mb-1"><strong>Penerima:</strong> {{ $booking->nama_penerima }}</p>
                                <p class="mb-1"><strong>No. Telepon / WA:</strong> {{ $booking->no_tlp_penerima }}</p>
                                <p class="mb-0"><strong>Alamat Lengkap:</strong><br>{{ $booking->alamat_pengiriman }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-success mb-2"><i class="bi bi-shop me-1"></i>Penjual</h6>
                                <p class="mb-1"><strong>{{ $infoweb->nama_web ?? 'UMKM Dusun 01 Purwodadi' }}</strong></p>
                                <p class="mb-1"><strong>Kontak:</strong> {{ $infoweb->telp ?? '081377889900' }}</p>
                                <p class="mb-0"><strong>Lokasi:</strong> {{ $infoweb->alamat ?? 'Dusun 01 Purwodadi, Trimurjo, Lampung Tengah' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Products Table -->
                    <h6 class="fw-bold mb-3"><i class="bi bi-basket2 me-1"></i>Rincian Produk yang Dipesan</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">No</th>
                                    <th>Nama Produk Camilan</th>
                                    <th class="text-end" style="width: 140px;">Harga Satuan</th>
                                    <th class="text-center" style="width: 90px;">Jumlah</th>
                                    <th class="text-end" style="width: 150px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->detailBookings as $index => $detail)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $detail->produk->merk ?? 'Produk' }}</strong>
                                        </td>
                                        <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $detail->jumlah }} pcs</td>
                                        <td class="text-end fw-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-success">
                                    <td colspan="4" class="text-end fw-bold fs-6">TOTAL PEMBAYARAN:</td>
                                    <td class="text-end fw-bold fs-5 text-success">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- ==================== PILIHAN METODE PEMBAYARAN ==================== --}}
                    @if($booking->status_booking == 'menunggu_bayar')
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="fw-bold text-dark mb-0">
                                    <i class="bi bi-credit-card-2-front me-2 text-success"></i>Pilih Metode Pembayaran
                                </h5>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                    <i class="bi bi-shield-check me-1"></i>Aman &amp; Terverifikasi
                                </span>
                            </div>

                            <div class="row g-3">
                                {{-- METODE 1 (UTAMA): QRIS DINAMIS (BUATQRIS.SITE) --}}
                                <div class="col-md-4">
                                    <div class="card h-100 shadow-sm payment-choice-card border-qris p-3 bg-danger bg-opacity-10 position-relative">
                                        <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-danger shadow-sm">
                                            INSTAN 🔥
                                        </span>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="fs-4">📱</span>
                                            <div>
                                                <h6 class="fw-bold text-danger mb-0">QRIS Dinamis</h6>
                                                <small class="text-muted">buatqris.site Engine</small>
                                            </div>
                                        </div>
                                        <p class="small text-muted mb-2">
                                            Scan via <strong>GoPay, OVO, DANA, ShopeePay, BCA, BRImo, Livin'</strong>. Nominal tagihan otomatis terkunci persis!
                                        </p>
                                        <div class="d-flex align-items-center gap-1 mb-3 text-muted small">
                                            <span class="badge bg-white text-dark border">BCA</span>
                                            <span class="badge bg-white text-dark border">BRImo</span>
                                            <span class="badge bg-white text-dark border">GoPay</span>
                                            <span class="badge bg-white text-dark border">DANA</span>
                                        </div>

                                        <div class="mt-auto">
                                            <button type="button" id="btnOpenQrisModal" class="btn btn-danger fw-bold w-100 py-2 shadow-sm mb-2">
                                                <i class="bi bi-qr-code-scan me-1"></i>Tampilkan QRIS
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm w-100 btn-simulate-qris" title="Simulasi demo ujian sidang">
                                                <i class="bi bi-play-circle me-1"></i>Demo Cepat QRIS
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- METODE 2: PAYMENT GATEWAY (MIDTRANS SNAP) --}}
                                <div class="col-md-4">
                                    <div class="card h-100 shadow-sm payment-choice-card border-primary p-3 bg-primary bg-opacity-10">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="fs-4">⚡</span>
                                            <div>
                                                <h6 class="fw-bold text-primary mb-0">Payment Gateway</h6>
                                                <small class="text-muted">Virtual Account &amp; Multi-Bank</small>
                                            </div>
                                        </div>
                                        <p class="small text-muted mb-3">
                                            Bayar via Virtual Account resmi (BCA, BRI, Mandiri, BNI, Permata) atau kartu kredit.
                                        </p>

                                        <div class="mt-auto">
                                            <button type="button" id="payButtonSnap" class="btn btn-primary fw-bold w-100 py-2 shadow-sm mb-2">
                                                <i class="bi bi-lightning-charge-fill me-1"></i>Bayar Gateway
                                            </button>
                                            <form action="{{ route('payment.gateway.simulate', $booking->kode_booking) }}" method="POST" class="d-inline w-100">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-primary btn-sm w-100" onclick="return confirm('Jalankan simulasi gateway?')">
                                                    <i class="bi bi-play-circle me-1"></i>Demo Gateway
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- METODE 3: TRANSFER MANUAL --}}
                                <div class="col-md-4">
                                    <div class="card h-100 shadow-sm payment-choice-card border-warning p-3 bg-warning bg-opacity-10">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="fs-4">🏦</span>
                                            <div>
                                                <h6 class="fw-bold text-dark mb-0">Transfer Bank Manual</h6>
                                                <small class="text-muted">Verifikasi Bukti oleh Admin</small>
                                            </div>
                                        </div>
                                        <div class="p-2 bg-white rounded border small mb-3">
                                            <strong>{{ $infoweb->bank_toko ?? 'Bank BRI' }}:</strong> <code class="fw-bold">{{ $infoweb->no_rek_toko ?? '1234-01-000567-53-8' }}</code><br>
                                            <span class="text-muted small">A.n: {{ $infoweb->atas_nama_toko ?? 'UMKM Purwodadi' }}</span>
                                        </div>

                                        <div class="mt-auto">
                                            <a href="{{ route('pembayaran.create', $booking->id_booking) }}" class="btn btn-warning fw-bold w-100 py-2 shadow-sm">
                                                <i class="bi bi-upload me-1"></i>Upload Bukti
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Payment Details (if payment exists) -->
                    @if($booking->pembayaran)
                        <div class="card border-info mb-4">
                            <div class="card-header bg-info bg-opacity-10 text-dark fw-bold d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-credit-card-2-front me-2"></i>Status Pembayaran Anda</span>
                                <span class="badge bg-secondary font-monospace">{{ strtoupper($booking->pembayaran->metode ?? 'MANUAL') }}</span>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        @if($booking->pembayaran->transaksi_id)
                                            <p class="mb-1"><strong>ID Transaksi Gateway:</strong> <code class="text-primary">{{ $booking->pembayaran->transaksi_id }}</code></p>
                                        @endif
                                        <p class="mb-1"><strong>Metode / Saluran:</strong> {{ $booking->pembayaran->bank_pengirim ?? 'Payment Gateway' }}</p>
                                        <p class="mb-1"><strong>Pengirim:</strong> {{ $booking->pembayaran->nama_rekening ?? $booking->nama_penerima }} ({{ $booking->pembayaran->no_rekening ?? '-' }})</p>
                                        <p class="mb-1"><strong>Nominal:</strong> Rp {{ number_format($booking->pembayaran->nominal, 0, ',', '.') }}</p>
                                        <p class="mb-1"><strong>Waktu Pembayaran:</strong> {{ date('d M Y H:i', strtotime($booking->pembayaran->tgl_pembayaran)) }} WIB</p>
                                        <p class="mb-0">
                                            <strong>Status Verifikasi:</strong>
                                            @if($booking->pembayaran->status_konfirmasi == 'menunggu')
                                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Menunggu Verifikasi Admin</span>
                                            @elseif($booking->pembayaran->status_konfirmasi == 'terverifikasi')
                                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Terverifikasi (Pembayaran Diterima)</span>
                                            @else
                                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                                <span class="text-danger d-block mt-1">Alasan: {{ $booking->pembayaran->catatan_admin ?? 'Bukti transfer tidak valid' }}</span>
                                                <a href="{{ route('pembayaran.create', $booking->id_booking) }}" class="btn btn-sm btn-outline-danger mt-2">Upload Ulang Bukti</a>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-5 text-center mt-3 mt-md-0">
                                        @if($booking->pembayaran->bukti && file_exists(public_path('uploads/bukti/' . $booking->pembayaran->bukti)))
                                            <small class="text-muted d-block mb-1">Bukti Transfer:</small>
                                            <a href="{{ asset('uploads/bukti/' . $booking->pembayaran->bukti) }}" target="_blank">
                                                <img src="{{ asset('uploads/bukti/' . $booking->pembayaran->bukti) }}" alt="Bukti Transfer" class="img-thumbnail" style="max-height: 120px;">
                                            </a>
                                            <small class="d-block text-muted mt-1">(Klik foto untuk memperbesar)</small>
                                        @else
                                            <div class="p-3 bg-light rounded border">
                                                <i class="bi bi-shield-check text-success fs-1 d-block mb-1"></i>
                                                <strong class="text-success small d-block">Terverifikasi Digital</strong>
                                                <small class="text-muted">{{ $booking->pembayaran->catatan_admin ?? 'Otomatis via Gateway / QRIS' }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap gap-2 justify-content-between pt-3 border-top">
                        <div>
                            <a href="{{ route('beranda') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-house me-1"></i>Ke Beranda
                            </a>
                            <a href="{{ route('katalog.index') }}" class="btn btn-outline-success ms-1">
                                <i class="bi bi-shop me-1"></i>Belanja Lagi
                            </a>
                        </div>
                        <div>
                            <button onclick="window.print()" class="btn btn-outline-primary">
                                <i class="bi bi-printer me-1"></i>Cetak Invoice
                            </button>
                            <a href="{{ route('booking.tracking', ['kode_booking' => $booking->kode_booking]) }}" class="btn btn-info text-white ms-1">
                                <i class="bi bi-search me-1"></i>Lacak Pesanan
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- ==================== MODAL DYNAMIC QRIS (BUATQRIS.SITE) ==================== --}}
<div class="modal fade" id="qrisModal" tabindex="-1" aria-labelledby="qrisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white py-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-white text-danger fw-bold fs-6">QRIS</span>
                    <h5 class="modal-title fw-bold mb-0" id="qrisModalLabel">Pembayaran QRIS Nasional</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <!-- Merchant Banner -->
                <div class="mb-3">
                    <h6 class="fw-bold mb-0 text-dark">{{ $infoweb->nama_web ?? 'UMKM PURWODADI' }}</h6>
                    <small class="text-muted">NMID: ID1020022304851 &bull; Dusun 01 Purwodadi, Trimurjo</small>
                </div>

                <!-- Total Amount Card -->
                <div class="p-2 bg-light rounded border mb-3">
                    <small class="text-muted d-block">Total Nominal Tagihan:</small>
                    <span class="fs-3 fw-bold text-danger" id="qrisTotalAmount">
                        Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                    </span>
                    <small class="d-block text-success mt-1">
                        <i class="bi bi-check2-circle me-1"></i>Nominal Otomatis Terkunci (Dynamic QR)
                    </small>
                </div>

                <!-- QR Container with Loading Spinner -->
                <div class="position-relative my-3" style="min-height: 280px;">
                    <div id="qrisLoading" class="position-absolute top-50 start-50 translate-middle text-center w-100">
                        <div class="spinner-border text-danger mb-2" role="status"></div>
                        <p class="small text-muted mb-0">Membuat QRIS Dinamis via buatqris.site...</p>
                    </div>

                    <div id="qrisImageWrapper" class="d-none">
                        <div class="qris-box">
                            <img id="qrisImage" src="" alt="Dynamic QRIS Code" class="img-fluid" style="width: 250px; height: 250px; object-fit: contain;">
                        </div>
                    </div>
                </div>

                <!-- Countdown Timer -->
                <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                    <span class="badge bg-warning text-dark px-3 py-2 fs-6">
                        <i class="bi bi-clock me-1"></i>Sisa Waktu: <span id="qrisTimer" class="fw-bold font-monospace">30:00</span>
                    </span>
                    <span class="badge bg-success px-3 py-2 fs-6 qris-pulse" id="qrisStatusBadge">
                        <i class="bi bi-broadcast me-1"></i>Menunggu Pembayaran...
                    </span>
                </div>

                <p class="small text-muted mb-3">
                    Buka aplikasi <strong>BCA, BRImo, Livin', GoPay, OVO, DANA, ShopeePay</strong> atau m-Banking Anda, lalu <strong>Pindai / Scan QR</strong> di atas.
                </p>

                <!-- Actions -->
                <div class="d-grid gap-2">
                    <button type="button" id="btnCheckQrisStatus" class="btn btn-outline-success">
                        <i class="bi bi-arrow-clockwise me-1"></i>Cek Status Pembayaran Sekarang
                    </button>
                    <button type="button" id="btnCopyQrisString" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-clipboard me-1"></i>Salin String QRIS EMVCo
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-simulate-qris">
                        <i class="bi bi-play-circle me-1"></i>Demo Sidang: Simulasi Pembayaran Berhasil
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL SIMULATOR GATEWAY (MIDTRANS) --}}
<div class="modal fade" id="gatewayDemoModal" tabindex="-1" aria-labelledby="gatewayDemoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="gatewayDemoModalLabel">
                    <i class="bi bi-credit-card-2-front me-2"></i>Midtrans Payment Gateway (Demo)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span class="fs-1">💳</span>
                    <h5 class="fw-bold mt-2">Pilih Saluran Pembayaran Gateway</h5>
                    <p class="text-muted small">Total Tagihan: <strong class="text-success fs-6">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</strong></p>
                </div>

                <div class="list-group text-start mb-3">
                    <a href="{{ route('payment.gateway.simulate', [$booking->kode_booking, 'payment_type' => 'BCA Virtual Account']) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div>
                            <strong class="d-block">🏦 BCA Virtual Account</strong>
                            <small class="text-muted">Transfer via m-BCA / ATM BCA</small>
                        </div>
                        <span class="badge bg-primary">VA</span>
                    </a>
                    <a href="{{ route('payment.gateway.simulate', [$booking->kode_booking, 'payment_type' => 'BRI Virtual Account']) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div>
                            <strong class="d-block">🏦 BRI Virtual Account (BRIVA)</strong>
                            <small class="text-muted">Transfer via BRImo / ATM BRI</small>
                        </div>
                        <span class="badge bg-primary">VA</span>
                    </a>
                    <a href="{{ route('payment.gateway.simulate', [$booking->kode_booking, 'payment_type' => 'Mandiri Virtual Account']) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div>
                            <strong class="d-block">🏦 Mandiri Livin\' / Virtual Account</strong>
                            <small class="text-muted">Transfer via Livin\' by Mandiri</small>
                        </div>
                        <span class="badge bg-primary">VA</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Midtrans Snap SDK JS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kodeBooking = '{{ $booking->kode_booking }}';
    let qrisModalInstance = null;
    let timerInterval = null;
    let pollingInterval = null;
    let qrisStringData = '';

    // ==========================================
    // 1. HANDLER PEMBAYARAN QRIS (BUATQRIS.SITE)
    // ==========================================
    const btnOpenQris = document.getElementById('btnOpenQrisModal');
    if (btnOpenQris) {
        btnOpenQris.addEventListener('click', function () {
            if (!qrisModalInstance) {
                qrisModalInstance = new bootstrap.Modal(document.getElementById('qrisModal'));
            }
            qrisModalInstance.show();

            // Reset view
            document.getElementById('qrisLoading').classList.remove('d-none');
            document.getElementById('qrisImageWrapper').classList.add('d-none');

            // Ambil data Dynamic QRIS via AJAX
            fetch('{{ route("payment.gateway.qris", $booking->kode_booking) }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message || 'Gagal memuat QRIS.');
                    return;
                }

                qrisStringData = data.qris_string;
                const img = document.getElementById('qrisImage');
                img.src = data.qr_image_url;

                img.onload = function () {
                    document.getElementById('qrisLoading').classList.add('d-none');
                    document.getElementById('qrisImageWrapper').classList.remove('d-none');
                };

                // Fallback jika image CDN bermasalah
                img.onerror = function () {
                    if (data.qr_image_url_alt) {
                        img.src = data.qr_image_url_alt;
                    }
                    document.getElementById('qrisLoading').classList.add('d-none');
                    document.getElementById('qrisImageWrapper').classList.remove('d-none');
                };

                // Mulai Countdown Timer 30 Menit
                startCountdown(30 * 60);

                // Mulai Auto-Polling Status Pembayaran setiap 3.5 detik
                startStatusPolling();
            })
            .catch(err => {
                alert('Terjadi kesalahan saat menghubungi server QRIS.');
                document.getElementById('qrisLoading').classList.add('d-none');
            });
        });
    }

    // Countdown Timer Function
    function startCountdown(duration) {
        if (timerInterval) clearInterval(timerInterval);
        let timer = duration;
        const display = document.getElementById('qrisTimer');

        timerInterval = setInterval(function () {
            const minutes = parseInt(timer / 60, 10);
            const seconds = parseInt(timer % 60, 10);

            const mStr = minutes < 10 ? '0' + minutes : minutes;
            const sStr = seconds < 10 ? '0' + seconds : seconds;
            display.textContent = mStr + ':' + sStr;

            if (--timer < 0) {
                clearInterval(timerInterval);
                display.textContent = '00:00 (KEDALUWARSA)';
                alert('Waktu pembayaran QRIS telah habis. Silakan buat ulang.');
            }
        }, 1000);
    }

    // Auto Polling Status Pembayaran
    function startStatusPolling() {
        if (pollingInterval) clearInterval(pollingInterval);

        pollingInterval = setInterval(function () {
            fetch('{{ route("payment.gateway.check-status", $booking->kode_booking) }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.is_paid) {
                    clearInterval(pollingInterval);
                    clearInterval(timerInterval);
                    document.getElementById('qrisStatusBadge').className = 'badge bg-success px-3 py-2 fs-6';
                    document.getElementById('qrisStatusBadge').innerHTML = '<i class="bi bi-check-circle me-1"></i>Pembayaran Berhasil Diterima!';
                    setTimeout(function () {
                        window.location.reload();
                    }, 1200);
                }
            })
            .catch(() => {});
        }, 3500);
    }

    // Tombol Manual Cek Status
    const btnCheckStatus = document.getElementById('btnCheckQrisStatus');
    if (btnCheckStatus) {
        btnCheckStatus.addEventListener('click', function () {
            btnCheckStatus.disabled = true;
            btnCheckStatus.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memeriksa...';

            fetch('{{ route("payment.gateway.check-status", $booking->kode_booking) }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                btnCheckStatus.disabled = false;
                btnCheckStatus.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Cek Status Pembayaran Sekarang';

                if (data.is_paid) {
                    alert('Pembayaran Anda sudah lunas terverifikasi!');
                    window.location.reload();
                } else {
                    alert('Pembayaran belum terdeteksi. Silakan selesaikan scan di aplikasi m-Banking/E-Wallet Anda.');
                }
            })
            .catch(() => {
                btnCheckStatus.disabled = false;
                btnCheckStatus.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Cek Status Pembayaran Sekarang';
            });
        });
    }

    // Salin String QRIS
    const btnCopy = document.getElementById('btnCopyQrisString');
    if (btnCopy) {
        btnCopy.addEventListener('click', function () {
            if (!qrisStringData) return;
            navigator.clipboard.writeText(qrisStringData).then(() => {
                const old = btnCopy.innerHTML;
                btnCopy.innerHTML = '<i class="bi bi-check2 me-1"></i>Tersalin ke Clipboard!';
                setTimeout(() => btnCopy.innerHTML = old, 2000);
            });
        });
    }

    // Tombol Simulasi QRIS Berhasil (Untuk Sidang Skripsi Demo)
    document.querySelectorAll('.btn-simulate-qris').forEach(btn => {
        btn.addEventListener('click', function () {
            if (confirm('Jalankan simulasi pembayaran QRIS sukses (untuk pengujian sidang skripsi)?')) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

                fetch('{{ route("payment.gateway.simulate-qris", $booking->kode_booking) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    }
                })
                .catch(() => {
                    window.location.href = '{{ route("payment.gateway.simulate-qris", $booking->kode_booking) }}';
                });
            }
        });
    });

    // ==========================================
    // 2. HANDLER MIDTRANS SNAP GATEWAY
    // ==========================================
    const payBtnSnap = document.getElementById('payButtonSnap');
    if (payBtnSnap) {
        payBtnSnap.addEventListener('click', function () {
            payBtnSnap.disabled = true;
            payBtnSnap.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Menghubungkan Gateway...';

            fetch('{{ route("payment.gateway.token", $booking->kode_booking) }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                payBtnSnap.disabled = false;
                payBtnSnap.innerHTML = '<i class="bi bi-lightning-charge-fill me-1"></i>Bayar Gateway';

                if (!data.success) {
                    alert(data.message || 'Gagal memproses pembayaran gateway.');
                    return;
                }

                if (data.is_demo || !window.snap) {
                    const modal = new bootstrap.Modal(document.getElementById('gatewayDemoModal'));
                    modal.show();
                    return;
                }

                window.snap.pay(data.snap_token, {
                    onSuccess: function (result) {
                        window.location.href = '{{ route("payment.gateway.finish") }}?order_id=' + result.order_id + '&payment_type=' + (result.payment_type || 'gateway');
                    },
                    onPending: function (result) {
                        alert('Pembayaran sedang diproses / menunggu transfer.');
                        window.location.reload();
                    },
                    onError: function (result) {
                        alert('Pembayaran gagal: ' + (result.status_message || 'Terjadi kesalahan'));
                    }
                });
            })
            .catch(() => {
                payBtnSnap.disabled = false;
                payBtnSnap.innerHTML = '<i class="bi bi-lightning-charge-fill me-1"></i>Bayar Gateway';
                const modal = new bootstrap.Modal(document.getElementById('gatewayDemoModal'));
                modal.show();
            });
        });
    }
});
</script>
@endpush
