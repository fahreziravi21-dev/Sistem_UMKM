@extends('layouts.app')

@section('title', 'Checkout - UMKM Purwodadi')

@section('styles')
<style>
    .checkout-step {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    .checkout-step-num {
        width: 36px;
        height: 36px;
        background: #198754;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }
    .item-thumb {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        background: #f8f9fa;
        border-radius: 0.375rem;
        flex-shrink: 0;
    }
</style>
@endsection

@section('content')
<div class="container py-5">

    {{-- Page Header --}}
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold mb-1">
                <i class="bi bi-bag-check text-success me-2"></i>Checkout
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('beranda') }}" class="text-decoration-none text-success">Beranda</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('keranjang.index') }}" class="text-decoration-none text-success">Keranjang</a>
                    </li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </nav>
        </div>
    </div>

    @php
        $keranjang = session('keranjang', []);
        $totalHarga = 0;
        foreach ($keranjang as $item) {
            $totalHarga += ($item['harga'] ?? 0) * ($item['jumlah'] ?? 1);
        }

        // Session pengguna data
        $userSession = session('pengguna', []);
        $namaPengguna = '';
        $noTlp = '';
        $alamat = '';
        if (is_array($userSession)) {
            $namaPengguna = $userSession['nama_pengguna'] ?? $userSession['nama'] ?? '';
            $noTlp = $userSession['no_tlp'] ?? $userSession['telepon'] ?? '';
            $alamat = $userSession['alamat'] ?? '';
        }
    @endphp

    @if(empty($keranjang))
        <div class="alert alert-warning d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>
                Keranjang Anda kosong. <a href="{{ route('katalog.index') }}" class="fw-bold">Lihat katalog</a> dan tambahkan produk terlebih dahulu.
            </div>
        </div>
    @else
        <div class="row g-4">

            {{-- Left Column: Order Summary --}}
            <div class="col-lg-5 order-lg-2">
                <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-receipt me-2"></i>Ringkasan Pesanan
                        </h5>
                    </div>
                    <div class="card-body">

                        {{-- Items List --}}
                        <div class="mb-3">
                            @foreach($keranjang as $key => $item)
                                @php
                                    $namaItem = strtolower($item['nama'] ?? $item['merk'] ?? '');
                                    $subtotalItem = ($item['harga'] ?? 0) * ($item['jumlah'] ?? 1);
                                @endphp
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="item-thumb">
                                        @if(str_contains($namaItem, 'kelanting'))🥨
                                        @elseif(str_contains($namaItem, 'untir'))🍩
                                        @elseif(str_contains($namaItem, 'keripik') || str_contains($namaItem, 'singkong'))🥔
                                        @else🍿
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold small">{{ $item['nama'] ?? $item['merk'] ?? 'Produk' }}</div>
                                        <div class="text-muted small">
                                            Rp {{ number_format($item['harga'] ?? 0, 0, ',', '.') }} × {{ $item['jumlah'] ?? 1 }}
                                        </div>
                                    </div>
                                    <div class="fw-bold small text-end">
                                        Rp {{ number_format($subtotalItem, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal ({{ count($keranjang) }} produk)</span>
                            <span class="fw-semibold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-muted small">
                            <span>Ongkos Kirim</span>
                            <span>Gratis / Negosiasi</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold fs-5">Total Pembayaran</span>
                            <span class="fw-bold fs-5 text-success">
                                Rp {{ number_format($totalHarga, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="mt-3 p-3 bg-warning bg-opacity-10 border border-warning rounded">
                            <p class="small mb-1 fw-semibold text-warning-emphasis">
                                <i class="bi bi-info-circle me-1"></i>Informasi Pembayaran
                            </p>
                            <p class="small text-muted mb-0">
                                Pembayaran dilakukan melalui transfer bank setelah pesanan dibuat.
                                Bukti transfer harus diunggah dalam 1×24 jam.
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Right Column: Checkout Form --}}
            <div class="col-lg-7 order-lg-1">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-person-lines-fill text-success me-2"></i>Data Pengiriman
                        </h5>
                    </div>
                    <div class="card-body p-4">

                        <form action="{{ route('booking.store') }}" method="POST" id="checkoutForm">
                            @csrf

                            {{-- Nama Penerima --}}
                            <div class="mb-4">
                                <label for="nama_penerima" class="form-label fw-semibold">
                                    <i class="bi bi-person me-1 text-success"></i>Nama Penerima
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control form-control-lg @error('nama_penerima') is-invalid @enderror"
                                    id="nama_penerima"
                                    name="nama_penerima"
                                    value="{{ old('nama_penerima', $namaPengguna) }}"
                                    placeholder="Masukkan nama lengkap penerima"
                                    required>
                                @error('nama_penerima')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted">
                                    <i class="bi bi-info-circle me-1"></i>Nama yang tertera di bungkus paket pengiriman.
                                </div>
                            </div>

                            {{-- No. Telepon --}}
                            <div class="mb-4">
                                <label for="no_tlp_penerima" class="form-label fw-semibold">
                                    <i class="bi bi-telephone me-1 text-success"></i>No. Telepon Penerima
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">
                                        <i class="bi bi-phone"></i>
                                    </span>
                                    <input type="tel"
                                        class="form-control @error('no_tlp_penerima') is-invalid @enderror"
                                        id="no_tlp_penerima"
                                        name="no_tlp_penerima"
                                        value="{{ old('no_tlp_penerima', $noTlp) }}"
                                        placeholder="Contoh: 081234567890"
                                        required>
                                    @error('no_tlp_penerima')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text text-muted">
                                    <i class="bi bi-info-circle me-1"></i>Nomor yang bisa dihubungi untuk koordinasi pengiriman.
                                </div>
                            </div>

                            {{-- Alamat Pengiriman --}}
                            <div class="mb-4">
                                <label for="alamat_pengiriman" class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt me-1 text-success"></i>Alamat Pengiriman
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea
                                    class="form-control @error('alamat_pengiriman') is-invalid @enderror"
                                    id="alamat_pengiriman"
                                    name="alamat_pengiriman"
                                    rows="4"
                                    placeholder="Masukkan alamat lengkap pengiriman termasuk RT/RW, Kelurahan, Kecamatan, Kota, dan Kode Pos"
                                    required>{{ old('alamat_pengiriman', $alamat) }}</textarea>
                                @error('alamat_pengiriman')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted">
                                    <i class="bi bi-info-circle me-1"></i>Tuliskan alamat lengkap agar pengiriman tepat sasaran.
                                </div>
                            </div>

                            {{-- Catatan Tambahan --}}
                            <div class="mb-4">
                                <label for="catatan" class="form-label fw-semibold">
                                    <i class="bi bi-chat-text me-1 text-muted"></i>Catatan Pesanan
                                    <span class="text-muted small fw-normal">(Opsional)</span>
                                </label>
                                <textarea
                                    class="form-control"
                                    id="catatan"
                                    name="catatan"
                                    rows="2"
                                    placeholder="Misal: Jangan dibunyikan bel, titip di tetangga, dll.">{{ old('catatan') }}</textarea>
                            </div>

                            {{-- Total Summary --}}
                            <div class="p-3 bg-success bg-opacity-10 border border-success rounded mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold text-success fs-5">Total Pembayaran</div>
                                        <div class="text-muted small">{{ count($keranjang) }} produk</div>
                                    </div>
                                    <div class="fw-bold fs-4 text-success">
                                        Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            {{-- Agreement --}}
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="agreement" required>
                                <label class="form-check-label text-muted small" for="agreement">
                                    Saya menyetujui bahwa data pengiriman yang diisi sudah benar dan saya bersedia melakukan pembayaran setelah pesanan dibuat.
                                </label>
                            </div>

                            {{-- Submit --}}
                            <div class="d-flex gap-3">
                                <a href="{{ route('keranjang.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-success btn-lg flex-fill fw-bold" id="btnCheckout">
                                    <i class="bi bi-bag-check me-2"></i>Buat Pesanan
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('checkoutForm');
        const btnCheckout = document.getElementById('btnCheckout');

        if (form && btnCheckout) {
            form.addEventListener('submit', function () {
                btnCheckout.disabled = true;
                btnCheckout.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Memproses...';
            });
        }
    });
</script>
@endpush
