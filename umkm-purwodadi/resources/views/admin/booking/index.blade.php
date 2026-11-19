@extends('layouts.admin')

@section('title', 'Kelola Pesanan - Admin UMKM')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-cart-check me-2 text-primary"></i>Kelola Pesanan (Booking)</h4>
        <small class="text-muted">Daftar transaksi pemesanan produk UMKM Dusun 01 Purwodadi</small>
    </div>
</div>

<!-- Filter Tabs -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-3">
        <ul class="nav nav-pills flex-column flex-sm-row gap-1 mb-3">
            <li class="nav-item">
                <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.booking.index') }}">
                    Semua
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'menunggu_bayar' ? 'active' : '' }}" href="{{ route('admin.booking.index', ['status' => 'menunggu_bayar']) }}">
                    Menunggu Bayar
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'dibayar' ? 'active' : '' }}" href="{{ route('admin.booking.index', ['status' => 'dibayar']) }}">
                    Dibayar
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'dikirim' ? 'active' : '' }}" href="{{ route('admin.booking.index', ['status' => 'dikirim']) }}">
                    Dikirim
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'selesai' ? 'active' : '' }}" href="{{ route('admin.booking.index', ['status' => 'selesai']) }}">
                    Selesai
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'batal' ? 'active' : '' }}" href="{{ route('admin.booking.index', ['status' => 'batal']) }}">
                    Batal
                </a>
            </li>
        </ul>

        <!-- Search Form -->
        <form action="{{ route('admin.booking.index') }}" method="GET" class="row g-2">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="col-md-5">
                <div class="input-group">
                    <input type="text" name="cari" class="form-control" placeholder="Cari kode booking atau nama penerima..." value="{{ request('cari') }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
                    @if(request('cari'))
                        <a href="{{ route('admin.booking.index', ['status' => request('status')]) }}" class="btn btn-outline-danger"><i class="bi bi-x"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bookings Table -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        @if($bookings->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                <h6 class="text-muted">Tidak ada data pesanan yang cocok.</h6>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Kode Booking</th>
                            <th>Akun Pembeli</th>
                            <th>Penerima & Alamat</th>
                            <th>Total Harga</th>
                            <th>Waktu Booking</th>
                            <th>Status Pesanan</th>
                            <th>Pembayaran</th>
                            <th class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $b)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-bold font-monospace text-primary">{{ $b->kode_booking }}</span>
                                </td>
                                <td>
                                    <div><strong>{{ $b->pengguna->nama_pengguna ?? 'User' }}</strong></div>
                                    <small class="text-muted">@ {{ $b->pengguna->username ?? '-' }}</small>
                                </td>
                                <td>
                                    <div>{{ $b->nama_penerima }}</div>
                                    <small class="text-muted">{{ $b->no_tlp_penerima }}</small>
                                </td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($b->total_harga, 0, ',', '.') }}
                                </td>
                                <td>
                                    <small>{{ date('d M Y, H:i', strtotime($b->tgl_booking)) }}</small>
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
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Lunas</span>
                                        @elseif($b->pembayaran->status_konfirmasi == 'menunggu')
                                            <a href="{{ route('admin.pembayaran.show', $b->pembayaran->id_pembayaran) }}" class="badge bg-warning text-dark text-decoration-none">
                                                <i class="bi bi-hourglass-split me-1"></i>Periksa
                                            </a>
                                        @else
                                            <span class="badge bg-danger"><i class="bi bi-x me-1"></i>Ditolak</span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-muted border">Belum Bayar</span>
                                    @endif
                                </td>
                                <td class="text-center pe-3">
                                    <a href="{{ route('admin.booking.show', $b->id_booking) }}" class="btn btn-sm btn-outline-primary fw-semibold">
                                        <i class="bi bi-eye me-1"></i>Kelola
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

