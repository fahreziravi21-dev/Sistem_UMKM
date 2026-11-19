@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran - Admin UMKM')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-wallet2 me-2 text-primary"></i>Verifikasi Pembayaran</h4>
        <small class="text-muted">Kelola dan validasi bukti transfer pembayaran dari pembeli</small>
    </div>
</div>

<!-- Filter Tabs -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-3">
        <ul class="nav nav-pills flex-column flex-sm-row gap-1">
            <li class="nav-item">
                <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.pembayaran.index') }}">
                    Semua
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'menunggu' ? 'active' : '' }}" href="{{ route('admin.pembayaran.index', ['status' => 'menunggu']) }}">
                    ⏳ Menunggu Verifikasi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'terverifikasi' ? 'active' : '' }}" href="{{ route('admin.pembayaran.index', ['status' => 'terverifikasi']) }}">
                    ✅ Terverifikasi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'ditolak' ? 'active' : '' }}" href="{{ route('admin.pembayaran.index', ['status' => 'ditolak']) }}">
                    ❌ Ditolak
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Payments Table -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        @if($pembayarans->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-credit-card fs-1 text-muted d-block mb-2"></i>
                <h6 class="text-muted">Tidak ada data bukti pembayaran.</h6>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Kode Booking</th>
                            <th>Pembeli</th>
                            <th>Bank & Rekening Asal</th>
                            <th>Nominal</th>
                            <th>Tanggal Upload</th>
                            <th class="text-center">Bukti Transfer</th>
                            <th>Status Konfirmasi</th>
                            <th class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembayarans as $p)
                            <tr>
                                <td class="ps-3">
                                    <a href="{{ route('admin.booking.show', $p->id_booking) }}" class="fw-bold font-monospace text-primary text-decoration-none">
                                        {{ $p->booking->kode_booking ?? '-' }}
                                    </a>
                                </td>
                                <td>
                                    <strong>{{ $p->booking->pengguna->nama_pengguna ?? 'User' }}</strong>
                                </td>
                                <td>
                                    <div><strong>{{ $p->bank_pengirim }}</strong></div>
                                    <small class="text-muted">{{ $p->nama_rekening }} ({{ $p->no_rekening }})</small>
                                </td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($p->nominal, 0, ',', '.') }}
                                </td>
                                <td>
                                    <small>{{ date('d M Y, H:i', strtotime($p->tgl_pembayaran)) }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ asset('uploads/bukti/' . $p->bukti) }}" target="_blank">
                                        <img src="{{ asset('uploads/bukti/' . $p->bukti) }}" alt="Bukti" class="img-thumbnail" style="height: 50px; width: 50px; object-fit: cover;">
                                    </a>
                                </td>
                                <td>
                                    @if($p->status_konfirmasi == 'terverifikasi')
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Terverifikasi</span>
                                    @elseif($p->status_konfirmasi == 'menunggu')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Menunggu</span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                    @endif
                                </td>
                                <td class="text-center pe-3">
                                    <a href="{{ route('admin.pembayaran.show', $p->id_pembayaran) }}" class="btn btn-sm btn-primary fw-semibold">
                                        <i class="bi bi-check2-square me-1"></i>Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top">
                {{ $pembayarans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

