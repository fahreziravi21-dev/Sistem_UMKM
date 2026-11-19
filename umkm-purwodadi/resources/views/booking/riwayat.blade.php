@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya - UMKM Purwodadi')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pesanan Saya</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Riwayat Pesanan Saya</h5>
            <a href="{{ route('katalog.index') }}" class="btn btn-light btn-sm fw-semibold text-success">
                <i class="bi bi-bag-plus me-1"></i>Pesan Lagi
            </a>
        </div>

        <div class="card-body p-4">
            @if($bookings->isEmpty())
                <div class="text-center py-5">
                    <span class="fs-1 d-block mb-3">🛒</span>
                    <h5 class="fw-bold">Belum Ada Riwayat Pesanan</h5>
                    <p class="text-muted">Anda belum pernah melakukan pemesanan produk UMKM Dusun 01 Purwodadi.</p>
                    <a href="{{ route('katalog.index') }}" class="btn btn-success fw-bold px-4 mt-2">
                        <i class="bi bi-shop me-1"></i>Mulai Belanja Sekarang
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode Booking</th>
                                <th>Tanggal</th>
                                <th>Penerima</th>
                                <th>Total Harga</th>
                                <th>Status Pesanan</th>
                                <th>Pembayaran</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $b)
                                <tr>
                                    <td>
                                        <a href="{{ route('booking.invoice', $b->kode_booking) }}" class="fw-bold text-decoration-none font-monospace text-primary">
                                            {{ $b->kode_booking }}
                                        </a>
                                    </td>
                                    <td>
                                        <small>{{ date('d M Y, H:i', strtotime($b->tgl_booking)) }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $b->nama_penerima }}</div>
                                        <small class="text-muted">{{ $b->no_tlp_penerima }}</small>
                                    </td>
                                    <td class="fw-bold text-success">
                                        Rp {{ number_format($b->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($b->status_booking == 'menunggu_bayar') bg-warning text-dark
                                            @elseif($b->status_booking == 'dibayar') bg-info text-dark
                                            @elseif($b->status_booking == 'dikirim') bg-primary
                                            @elseif($b->status_booking == 'selesai') bg-success
                                            @else bg-danger @endif">
                                            {{ strtoupper(str_replace('_', ' ', $b->status_booking)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($b->pembayaran)
                                            @if($b->pembayaran->status_konfirmasi == 'terverifikasi')
                                                <span class="badge bg-success"><i class="bi bi-check2 me-1"></i>Lunas</span>
                                            @elseif($b->pembayaran->status_konfirmasi == 'menunggu')
                                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Dicek</span>
                                            @else
                                                <span class="badge bg-danger"><i class="bi bi-x me-1"></i>Ditolak</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Belum Upload</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('booking.invoice', $b->kode_booking) }}" class="btn btn-outline-primary" title="Lihat Invoice">
                                                <i class="bi bi-receipt"></i>
                                            </a>
                                            <a href="{{ route('booking.tracking', ['kode_booking' => $b->kode_booking]) }}" class="btn btn-outline-info" title="Lacak">
                                                <i class="bi bi-truck"></i>
                                            </a>
                                            @if($b->status_booking == 'menunggu_bayar' && (!$b->pembayaran || $b->pembayaran->status_konfirmasi == 'ditolak'))
                                                <a href="{{ route('pembayaran.create', $b->id_booking) }}" class="btn btn-warning" title="Upload Bukti Bayar">
                                                    <i class="bi bi-upload"></i> Bayar
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

