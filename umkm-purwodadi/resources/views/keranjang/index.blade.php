@extends('layouts.app')

@section('title', 'Keranjang Belanja - UMKM Purwodadi')

@section('styles')
<style>
    .cart-empty-icon {
        font-size: 6rem;
    }
    .quantity-cell input {
        max-width: 70px;
    }
</style>
@endsection

@section('content')
<div class="container py-5">

    {{-- Page Header --}}
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold mb-1">
                <i class="bi bi-cart3 text-success me-2"></i>Keranjang Belanja
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('beranda') }}" class="text-decoration-none text-success">Beranda</a>
                    </li>
                    <li class="breadcrumb-item active">Keranjang</li>
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
    @endphp

    @if(!empty($keranjang))
        <div class="row g-4">

            {{-- Cart Items Column --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-bag me-2 text-success"></i>
                            Item ({{ count($keranjang) }} produk)
                        </h5>
                        <a href="{{ route('keranjang.kosongkan') }}"
                            class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Kosongkan semua keranjang?')">
                            <i class="bi bi-trash me-1"></i>Kosongkan
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3">Produk</th>
                                        <th class="text-center py-3">Harga Satuan</th>
                                        <th class="text-center py-3">Jumlah</th>
                                        <th class="text-end py-3">Subtotal</th>
                                        <th class="text-center py-3">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($keranjang as $key => $item)
                                        @php
                                            $hargaItem = $item['harga'] ?? 0;
                                            $jumlahItem = $item['jumlah'] ?? 1;
                                            $subtotalItem = $hargaItem * $jumlahItem;
                                            $namaItem = strtolower($item['nama'] ?? $item['merk'] ?? '');
                                        @endphp
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="d-flex align-items-center justify-content-center bg-light rounded"
                                                        style="width: 56px; height: 56px; font-size: 2rem; flex-shrink: 0;">
                                                        @if(str_contains($namaItem, 'kelanting'))
                                                            🥨
                                                        @elseif(str_contains($namaItem, 'untir'))
                                                            🍩
                                                        @elseif(str_contains($namaItem, 'keripik') || str_contains($namaItem, 'singkong'))
                                                            🥔
                                                        @else
                                                            🍿
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $item['nama'] ?? $item['merk'] ?? 'Produk' }}</div>
                                                        @if(isset($item['stok']))
                                                            <small class="text-muted">Stok: {{ $item['stok'] }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-success fw-semibold">
                                                    Rp {{ number_format($hargaItem, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="text-center quantity-cell">
                                                <form action="{{ route('keranjang.update') }}" method="POST" class="d-flex align-items-center justify-content-center gap-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="key" value="{{ $key }}">
                                                    <div class="input-group input-group-sm" style="max-width: 120px;">
                                                        <button type="button" class="btn btn-outline-secondary btn-qty-minus" data-key="{{ $key }}">
                                                            <i class="bi bi-dash"></i>
                                                        </button>
                                                        <input type="number" name="jumlah" class="form-control text-center qty-input"
                                                            value="{{ $jumlahItem }}" min="1"
                                                            max="{{ $item['stok'] ?? 99 }}"
                                                            data-key="{{ $key }}"
                                                            data-harga="{{ $hargaItem }}"
                                                            style="width: 50px;">
                                                        <button type="button" class="btn btn-outline-secondary btn-qty-plus" data-key="{{ $key }}"
                                                            data-max="{{ $item['stok'] ?? 99 }}">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>
                                                    <button type="submit" class="btn btn-sm btn-success ms-1" title="Update">
                                                        <i class="bi bi-check2"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-end pe-3">
                                                <span class="fw-bold text-dark subtotal-{{ $key }}">
                                                    Rp {{ number_format($subtotalItem, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('keranjang.hapus', $key) }}"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Hapus"
                                                    onclick="return confirm('Hapus produk ini dari keranjang?')">
                                                    <i class="bi bi-trash3"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top py-3">
                        <a href="{{ route('katalog.index') }}" class="btn btn-outline-success">
                            <i class="bi bi-arrow-left me-2"></i>Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>

            {{-- Order Summary Column --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-receipt me-2"></i>Ringkasan Pesanan
                        </h5>
                    </div>
                    <div class="card-body">

                        {{-- Items Summary --}}
                        @foreach($keranjang as $key => $item)
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="text-muted small" style="max-width: 60%;">
                                    {{ $item['nama'] ?? $item['merk'] ?? 'Produk' }}
                                    <span class="text-secondary">×{{ $item['jumlah'] ?? 1 }}</span>
                                </div>
                                <span class="small fw-semibold">
                                    Rp {{ number_format(($item['harga'] ?? 0) * ($item['jumlah'] ?? 1), 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach

                        <hr>

                        {{-- Subtotal --}}
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-semibold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Ongkos Kirim</span>
                            <span class="text-muted small">Dihitung saat checkout</span>
                        </div>

                        <hr>

                        {{-- Total --}}
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 text-success">
                                Rp {{ number_format($totalHarga, 0, ',', '.') }}
                            </span>
                        </div>

                        {{-- Checkout Button --}}
                        @if(session('pengguna'))
                            <a href="{{ route('keranjang.checkout') }}" class="btn btn-success w-100 btn-lg fw-semibold">
                                <i class="bi bi-bag-check me-2"></i>Checkout Sekarang
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-success w-100 btn-lg fw-semibold">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login untuk Checkout
                            </a>
                        @endif

                        {{-- Payment Info --}}
                        <div class="mt-3 p-3 bg-light rounded">
                            <p class="small text-muted mb-1">
                                <i class="bi bi-shield-check text-success me-1"></i>
                                Pembayaran aman melalui transfer bank
                            </p>
                            <p class="small text-muted mb-0">
                                <i class="bi bi-clock text-warning me-1"></i>
                                Pesanan diproses setelah pembayaran dikonfirmasi
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    @else
        {{-- Empty Cart --}}
        <div class="text-center py-5">
            <div class="cart-empty-icon mb-4">🛒</div>
            <h4 class="fw-bold text-muted mb-2">Keranjang Masih Kosong</h4>
            <p class="text-muted mb-4">
                Belum ada produk di keranjang Anda.<br>
                Yuk, mulai belanja sekarang!
            </p>
            <a href="{{ route('katalog.index') }}" class="btn btn-success btn-lg px-5">
                <i class="bi bi-grid me-2"></i>Lihat Katalog Produk
            </a>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Quantity minus buttons
        document.querySelectorAll('.btn-qty-minus').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const key = this.dataset.key;
                const input = document.querySelector('.qty-input[data-key="' + key + '"]');
                let val = parseInt(input.value) || 1;
                if (val > 1) {
                    input.value = val - 1;
                }
            });
        });

        // Quantity plus buttons
        document.querySelectorAll('.btn-qty-plus').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const key = this.dataset.key;
                const maxVal = parseInt(this.dataset.max) || 99;
                const input = document.querySelector('.qty-input[data-key="' + key + '"]');
                let val = parseInt(input.value) || 1;
                if (val < maxVal) {
                    input.value = val + 1;
                }
            });
        });
    });
</script>
@endpush
