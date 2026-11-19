@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

{{-- ===== STAT CARDS ROW ===== --}}
<div class="row g-4 mb-4">

    {{-- Total Pendapatan --}}
    <div class="col-xl-3 col-md-6">
        <div class="card h-100" style="border-left: 4px solid #0d6efd;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;background:rgba(13,110,253,0.12);font-size:1.5rem;flex-shrink:0;">
                    💰
                </div>
                <div>
                    <div class="text-muted" style="font-size:0.76rem;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">Total Pendapatan</div>
                    <div class="fw-bold" style="font-size:1.3rem;color:#0d6efd;">
                        Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="text-muted" style="font-size:0.72rem;">Dari pesanan selesai</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pesanan Baru --}}
    <div class="col-xl-3 col-md-6">
        <div class="card h-100" style="border-left: 4px solid #ffc107;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;background:rgba(255,193,7,0.15);font-size:1.5rem;flex-shrink:0;">
                    🛒
                </div>
                <div>
                    <div class="text-muted" style="font-size:0.76rem;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">Pesanan Baru</div>
                    <div class="fw-bold" style="font-size:1.3rem;color:#e6a800;">
                        {{ $bookingBaru ?? 0 }}
                    </div>
                    <div class="text-muted" style="font-size:0.72rem;">Menunggu pembayaran</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stok Habis --}}
    <div class="col-xl-3 col-md-6">
        <div class="card h-100" style="border-left: 4px solid #dc3545;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;background:rgba(220,53,69,0.12);font-size:1.5rem;flex-shrink:0;">
                    📭
                </div>
                <div>
                    <div class="text-muted" style="font-size:0.76rem;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">Stok Habis</div>
                    <div class="fw-bold" style="font-size:1.3rem;color:#dc3545;">
                        {{ $stokHabis ?? 0 }}
                    </div>
                    <div class="text-muted" style="font-size:0.72rem;">Produk perlu restock</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Produk --}}
    <div class="col-xl-3 col-md-6">
        <div class="card h-100" style="border-left: 4px solid #198754;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;background:rgba(25,135,84,0.12);font-size:1.5rem;flex-shrink:0;">
                    📦
                </div>
                <div>
                    <div class="text-muted" style="font-size:0.76rem;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">Total Produk</div>
                    <div class="fw-bold" style="font-size:1.3rem;color:#198754;">
                        {{ $totalProduk ?? 0 }}
                    </div>
                    <div class="text-muted" style="font-size:0.72rem;">Produk terdaftar</div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ===== TABLES ROW ===== --}}
<div class="row g-4">

    {{-- Pesanan Terbaru --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>🛒 Pesanan Terbaru</span>
                <a href="{{ route('admin.booking.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="padding-left:20px;">Kode Booking</th>
                                <th>Pembeli</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookingTerbaru ?? [] as $booking)
                                <tr>
                                    <td style="padding-left:20px;">
                                        <span class="fw-semibold text-primary" style="font-size:0.85rem;">
                                            {{ $booking->kode_booking }}
                                        </span>
                                    </td>
                                    <td style="font-size:0.85rem;">
                                        {{ $booking->pengguna->nama_pengguna ?? $booking->nama_pembeli ?? '-' }}
                                    </td>
                                    <td style="font-size:0.85rem;">
                                        Rp {{ number_format($booking->total_harga ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @php
                                            $statusMap = [
                                                'menunggu_bayar' => ['bg-warning text-dark', 'Menunggu Bayar'],
                                                'dibayar'        => ['bg-info text-dark', 'Dibayar'],
                                                'dikirim'        => ['bg-primary', 'Dikirim'],
                                                'selesai'        => ['bg-success', 'Selesai'],
                                                'batal'          => ['bg-danger', 'Batal'],
                                            ];
                                            $st = $statusMap[$booking->status_booking] ?? ['bg-secondary', ucfirst($booking->status_booking ?? '-')];
                                        @endphp
                                        <span class="badge {{ $st[0] }}">{{ $st[1] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.booking.show', $booking->id_booking) }}"
                                           class="btn btn-sm btn-outline-secondary">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Belum ada pesanan masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Produk Stok Menipis --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>⚠️ Stok Menipis</span>
                <a href="{{ route('admin.produk.index') }}" class="btn btn-sm btn-outline-warning">
                    Kelola
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="padding-left:20px;">Produk</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stokMenipis ?? [] as $produk)
                                <tr>
                                    <td style="padding-left:20px;font-size:0.83rem;">
                                        {{ $produk->merk }}
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold {{ $produk->stok == 0 ? 'text-danger' : 'text-warning' }}">
                                            {{ $produk->stok }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($produk->status === 'habis' || $produk->stok == 0)
                                            <span class="badge bg-danger">Habis</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Menipis</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4" style="font-size:0.82rem;">
                                        Semua stok aman ✅
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
