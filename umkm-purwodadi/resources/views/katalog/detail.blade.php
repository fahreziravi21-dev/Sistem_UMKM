@extends('layouts.app')

@section('title', ($produk->merk ?? $produk->nama_produk ?? 'Detail Produk') . ' - UMKM Purwodadi')

@section('styles')
<style>
    .product-image-lg {
        height: 320px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8rem;
        background: #f8f9fa;
        border-radius: 0.5rem;
    }
    .quantity-control input[type=number]::-webkit-inner-spin-button,
    .quantity-control input[type=number]::-webkit-outer-spin-button {
        opacity: 1;
    }
</style>
@endsection

@section('content')
<div class="container py-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('beranda') }}" class="text-decoration-none text-success">
                    <i class="bi bi-house me-1"></i>Beranda
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('katalog.index') }}" class="text-decoration-none text-success">Katalog</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ $produk->merk ?? $produk->nama_produk ?? 'Detail Produk' }}
            </li>
        </ol>
    </nav>

    <div class="row g-5">

        {{-- Product Image Column --}}
        <div class="col-md-5">
            <div class="product-image-lg shadow-sm border">
                @php
                    $nama = strtolower($produk->merk ?? $produk->nama_produk ?? '');
                    if (str_contains($nama, 'kelanting')) {
                        echo '🥨';
                    } elseif (str_contains($nama, 'untir')) {
                        echo '🍩';
                    } elseif (str_contains($nama, 'keripik') || str_contains($nama, 'singkong')) {
                        echo '🥔';
                    } else {
                        echo '🍿';
                    }
                @endphp
            </div>

            {{-- Back to Catalog --}}
            <a href="{{ route('katalog.index') }}" class="btn btn-outline-secondary w-100 mt-3">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Katalog
            </a>
        </div>

        {{-- Product Info Column --}}
        <div class="col-md-7">

            {{-- Status Badge --}}
            @php
                $stok = $produk->stok ?? 0;
                $isHabis = $stok <= 0;
            @endphp
            <div class="mb-2">
                <span class="badge {{ $isHabis ? 'bg-danger' : 'bg-success' }} rounded-pill px-3 py-2">
                    <i class="bi bi-{{ $isHabis ? 'x-circle' : 'check-circle' }} me-1"></i>
                    {{ $isHabis ? 'Stok Habis' : 'Tersedia' }}
                </span>
                @if(isset($produk->kategori) || isset($produk->jenis))
                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 ms-1">
                        <i class="bi bi-tag me-1"></i>{{ $produk->kategori ?? $produk->jenis }}
                    </span>
                @endif
            </div>

            {{-- Product Name --}}
            <h2 class="fw-bold mb-1">{{ $produk->merk ?? $produk->nama_produk ?? 'Produk' }}</h2>

            {{-- Price --}}
            <div class="mb-3">
                <span class="display-6 fw-bold text-success">
                    Rp {{ number_format($produk->harga ?? 0, 0, ',', '.') }}
                </span>
                @if(isset($produk->satuan))
                    <span class="text-muted fs-6">/ {{ $produk->satuan }}</span>
                @endif
            </div>

            {{-- Stock Info --}}
            <div class="mb-4 p-3 bg-light rounded">
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="fw-bold fs-4 {{ $isHabis ? 'text-danger' : 'text-success' }}">
                            {{ $stok }}
                        </div>
                        <small class="text-muted">Stok Tersisa</small>
                    </div>
                    @if(isset($produk->berat))
                        <div class="col-6">
                            <div class="fw-bold fs-4 text-primary">{{ $produk->berat }}</div>
                            <small class="text-muted">Berat (gram)</small>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <h6 class="fw-bold mb-2 text-muted text-uppercase" style="letter-spacing: 0.05em;">Deskripsi Produk</h6>
                <p class="text-dark lh-lg">
                    {{ $produk->deskripsi ?? 'Makanan ringan tradisional khas Dusun 01 Purwodadi, Trimurjo, Lampung Tengah. Dibuat dari bahan alami pilihan tanpa bahan pengawet.' }}
                </p>
            </div>

            {{-- Add to Cart Form --}}
            @if(!$isHabis)
                @if(session('pengguna'))
                    <form action="{{ route('keranjang.tambah') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_produk" value="{{ $produk->id_produk ?? $produk->id }}">

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Jumlah</label>
                            <div class="d-flex align-items-center gap-3 quantity-control">
                                <div class="input-group" style="max-width: 160px;">
                                    <button type="button" class="btn btn-outline-secondary" id="btnMinus">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" name="jumlah" id="jumlahInput"
                                        class="form-control text-center fw-bold"
                                        value="1" min="1" max="{{ $stok }}"
                                        style="width: 70px;">
                                    <button type="button" class="btn btn-outline-secondary" id="btnPlus">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Maks. {{ $stok }} pcs</small>
                            </div>
                        </div>

                        {{-- Subtotal Preview --}}
                        <div class="alert alert-success-subtle border-success border mb-4 py-2 px-3 rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Subtotal:</span>
                                <span class="fw-bold text-success fs-5" id="subtotalDisplay">
                                    Rp {{ number_format($produk->harga ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-success btn-lg flex-fill fw-semibold">
                                <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                            </button>
                            <a href="{{ route('keranjang.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-cart3"></i>
                            </a>
                        </div>
                    </form>
                @else
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <div>
                            Silakan <a href="{{ route('login') }}" class="fw-bold">login</a> atau
                            <a href="{{ route('register') }}" class="fw-bold">daftar</a> terlebih dahulu untuk memesan.
                        </div>
                    </div>
                    <a href="{{ route('login') }}" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login untuk Memesan
                    </a>
                @endif
            @else
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>Stok produk ini sedang habis. Silakan cek kembali nanti atau hubungi kami.</div>
                </div>
                <a href="{{ route('beranda') }}#cta" class="btn btn-outline-success btn-lg w-100">
                    <i class="bi bi-whatsapp me-2"></i>Hubungi via WhatsApp
                </a>
            @endif

            {{-- Product Notes --}}
            <div class="mt-4 p-3 bg-light rounded">
                <h6 class="fw-semibold mb-2 text-muted"><i class="bi bi-info-circle me-2"></i>Informasi</h6>
                <ul class="list-unstyled mb-0 small text-muted">
                    <li class="mb-1"><i class="bi bi-check2 text-success me-2"></i>Produk alami tanpa bahan pengawet berbahaya</li>
                    <li class="mb-1"><i class="bi bi-check2 text-success me-2"></i>Dikemas higienis dan aman untuk konsumsi</li>
                    <li class="mb-1"><i class="bi bi-check2 text-success me-2"></i>Pembayaran melalui transfer bank</li>
                    <li><i class="bi bi-check2 text-success me-2"></i>Pengiriman setelah pembayaran dikonfirmasi</li>
                </ul>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const harga = {{ $produk->harga ?? 0 }};
        const stokMax = {{ $stok }};
        const jumlahInput = document.getElementById('jumlahInput');
        const subtotalDisplay = document.getElementById('subtotalDisplay');
        const btnMinus = document.getElementById('btnMinus');
        const btnPlus = document.getElementById('btnPlus');

        function formatRupiah(angka) {
            return 'Rp ' + angka.toLocaleString('id-ID');
        }

        function updateSubtotal() {
            let jumlah = parseInt(jumlahInput.value) || 1;
            if (jumlah < 1) jumlah = 1;
            if (jumlah > stokMax) jumlah = stokMax;
            jumlahInput.value = jumlah;
            if (subtotalDisplay) {
                subtotalDisplay.textContent = formatRupiah(harga * jumlah);
            }
        }

        if (btnMinus) {
            btnMinus.addEventListener('click', function () {
                let v = parseInt(jumlahInput.value) || 1;
                if (v > 1) { jumlahInput.value = v - 1; updateSubtotal(); }
            });
        }

        if (btnPlus) {
            btnPlus.addEventListener('click', function () {
                let v = parseInt(jumlahInput.value) || 1;
                if (v < stokMax) { jumlahInput.value = v + 1; updateSubtotal(); }
            });
        }

        if (jumlahInput) {
            jumlahInput.addEventListener('input', updateSubtotal);
        }
    });
</script>
@endpush
