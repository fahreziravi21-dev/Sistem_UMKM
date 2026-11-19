@extends('layouts.admin')

@section('title', 'Laporan Rekapitulasi Penjualan - Admin UMKM')

@section('content')
<style>
@media print {
    #sidebar, .top-header, .btn-print-hide, .filter-card, .footer {
        display: none !important;
    }
    body {
        margin: 0 !important;
        padding: 20px !important;
        background: #fff !important;
    }
    .main-content {
        margin-left: 0 !important;
        padding: 0 !important;
    }
    .print-only {
        display: block !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
.print-only {
    display: none;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4 btn-print-hide">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-file-earmark-bar-graph me-2 text-primary"></i>Laporan Penjualan</h4>
        <small class="text-muted">Rekapitulasi transaksi penjualan yang telah selesai (lunas & terkirim)</small>
    </div>
    <button onclick="window.print()" class="btn btn-primary fw-semibold">
        <i class="bi bi-printer me-1"></i>Cetak Laporan / PDF
    </button>
</div>

<!-- Filter Date Range -->
<div class="card shadow-sm border-0 mb-4 filter-card">
    <div class="card-body p-3">
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="dari_tanggal" class="form-label fw-semibold">Dari Tanggal:</label>
                <input type="date" class="form-control" id="dari_tanggal" name="dari_tanggal" value="{{ $dariTanggal }}">
            </div>
            <div class="col-md-4">
                <label for="sampai_tanggal" class="form-label fw-semibold">Sampai Tanggal:</label>
                <input type="date" class="form-control" id="sampai_tanggal" name="sampai_tanggal" value="{{ $sampaiTanggal }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-outline-primary w-100 fw-semibold">
                    <i class="bi bi-funnel me-1"></i>Filter Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Formal Print Header (Only visible on print) -->
<div class="print-only text-center mb-4 border-bottom pb-3">
    <h3 class="fw-bold mb-1">LAPORAN REKAPITULASI PENJUALAN PRODUK</h3>
    <h5 class="fw-semibold mb-1">UMKM DUSUN 01 PURWODADI KECAMATAN TRIMURJO</h5>
    <p class="mb-0 text-muted">Periode: {{ date('d F Y', strtotime($dariTanggal)) }} s/d {{ date('d F Y', strtotime($sampaiTanggal)) }}</p>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 bg-primary bg-opacity-10 shadow-sm p-3">
            <small class="text-muted fw-bold text-uppercase">Total Transaksi Selesai</small>
            <h3 class="fw-bold text-primary mb-0 mt-1">{{ $totalTransaksi }} <span class="fs-6 fw-normal text-muted">Pesanan</span></h3>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 bg-success bg-opacity-10 shadow-sm p-3">
            <small class="text-muted fw-bold text-uppercase">Total Pendapatan / Omset Periode</small>
            <h3 class="fw-bold text-success mb-0 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
        </div>
    </div>
</div>

<!-- Laporan Table -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        @if($laporans->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-clipboard-x fs-1 text-muted d-block mb-2"></i>
                <h6 class="text-muted">Tidak ada transaksi selesai pada rentang tanggal yang dipilih.</h6>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 40px;">No</th>
                            <th>Kode Booking</th>
                            <th>Tanggal Selesai</th>
                            <th>Akun Pembeli</th>
                            <th>Penerima</th>
                            <th>Rincian Produk Dipesan</th>
                            <th class="text-end" style="width: 150px;">Total Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporans as $idx => $lap)
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td>
                                    <span class="fw-bold font-monospace text-primary">{{ $lap->kode_booking }}</span>
                                </td>
                                <td>
                                    <small>{{ date('d/m/Y H:i', strtotime($lap->tgl_booking)) }}</small>
                                </td>
                                <td>{{ $lap->pengguna->nama_pengguna ?? '-' }}</td>
                                <td>{{ $lap->nama_penerima }}</td>
                                <td>
                                    <ul class="list-unstyled mb-0 small">
                                        @foreach($lap->detailBookings as $d)
                                            <li>• {{ $d->produk->merk ?? 'Produk' }} ({{ $d->jumlah }}x @ Rp {{ number_format($d->harga_satuan, 0, ',', '.') }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-end fw-bold text-success">
                                    Rp {{ number_format($lap->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-success">
                            <td colspan="6" class="text-end fw-bold">TOTAL PENDAPATAN AKUMULASI:</td>
                            <td class="text-end fw-bold fs-5 text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Formal Signature Area for Thesis / Report (Visible on print) -->
<div class="print-only mt-5 pt-4">
    <div class="row">
        <div class="col-6"></div>
        <div class="col-6 text-center">
            <p class="mb-1">Trimurjo, {{ date('d F Y') }}</p>
            <p class="mb-5"><strong>Pengelola UMKM Dusun 01 Purwodadi</strong></p>
            <br><br>
            <p class="mt-4 mb-0 text-decoration-underline"><strong>( ............................................ )</strong></p>
            <small class="text-muted">Pimpinan / Pemilik Usaha</small>
        </div>
    </div>
</div>
@endsection

