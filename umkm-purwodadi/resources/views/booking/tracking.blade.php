@extends('layouts.app')

@section('title', 'Cek & Lacak Pesanan - UMKM Purwodadi')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <!-- Search Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4 text-center">
                    <span class="fs-1">📦</span>
                    <h3 class="fw-bold mt-2">Lacak Status Pesanan Anda</h3>
                    <p class="text-muted">Masukkan Kode Booking yang Anda terima saat melakukan pemesanan (contoh: <code>BK-20261105...</code>)</p>

                    <form action="{{ route('booking.tracking') }}" method="GET" class="mt-3">
                        <div class="input-group input-group-lg max-w-500 mx-auto" style="max-width: 500px;">
                            <input type="text" name="kode_booking" class="form-control font-monospace" 
                                placeholder="Masukkan Kode Booking..." 
                                value="{{ request('kode_booking') }}" required>
                            <button class="btn btn-success fw-bold px-4" type="submit">
                                <i class="bi bi-search me-1"></i>Lacak
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($booking)
                <!-- Tracking Result Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold"><i class="bi bi-truck me-2"></i>Status Perjalanan Pesanan</h5>
                            <small class="opacity-75">Kode Booking: {{ $booking->kode_booking }}</small>
                        </div>
                        <a href="{{ route('booking.invoice', $booking->kode_booking) }}" class="btn btn-light btn-sm text-success fw-semibold">
                            <i class="bi bi-receipt me-1"></i>Lihat Invoice
                        </a>
                    </div>

                    <div class="card-body p-4">

                        <!-- Timeline Progress Bar -->
                        @php
                            $steps = [
                                'menunggu_bayar' => ['label' => 'Menunggu Pembayaran', 'icon' => 'bi-wallet2', 'step' => 1],
                                'dibayar'        => ['label' => 'Pembayaran Terverifikasi', 'icon' => 'bi-check2-circle', 'step' => 2],
                                'dikirim'        => ['label' => 'Pesanan Dikirim', 'icon' => 'bi-box-seam', 'step' => 3],
                                'selesai'        => ['label' => 'Pesanan Selesai', 'icon' => 'bi-house-check', 'step' => 4],
                            ];
                            $currentStatus = $booking->status_booking;
                            $currentStep = $steps[$currentStatus]['step'] ?? 1;
                        @endphp

                        @if($currentStatus === 'batal')
                            <div class="alert alert-danger text-center p-3 mb-4">
                                <i class="bi bi-x-circle-fill fs-3 d-block mb-1"></i>
                                <strong>Pesanan ini telah dibatalkan.</strong>
                            </div>
                        @else
                            <div class="row text-center mb-4 g-2">
                                @foreach($steps as $key => $st)
                                    @php
                                        $isPastOrCurrent = $st['step'] <= $currentStep;
                                        $isCurrent = $st['step'] == $currentStep;
                                    @endphp
                                    <div class="col-3">
                                        <div class="p-2 rounded {{ $isCurrent ? 'bg-success text-white shadow-sm' : ($isPastOrCurrent ? 'bg-light border text-success' : 'bg-light text-muted opacity-50') }}">
                                            <div class="fs-4 mb-1"><i class="bi {{ $st['icon'] }}"></i></div>
                                            <small class="fw-bold d-block" style="font-size: 0.75rem;">{{ $st['label'] }}</small>
                                            @if($isCurrent)
                                                <span class="badge bg-warning text-dark mt-1" style="font-size: 0.65rem;">Posisi Saat Ini</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Summary Table -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success mb-2">Informasi Penerima & Kiriman</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="text-muted" style="width: 130px;">Nama Penerima</td>
                                        <td>: <strong>{{ $booking->nama_penerima }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">No. Telepon / WA</td>
                                        <td>: {{ $booking->no_tlp_penerima }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Alamat Kirim</td>
                                        <td>: {{ $booking->alamat_pengiriman }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Waktu Pemesanan</td>
                                        <td>: {{ date('d M Y, H:i', strtotime($booking->tgl_booking)) }} WIB</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success mb-2">Ringkasan Produk</h6>
                                <ul class="list-group list-group-flush mb-2">
                                    @foreach($booking->detailBookings as $item)
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1">
                                            <span>{{ $item->produk->merk ?? 'Produk' }} <small class="text-muted">x {{ $item->jumlah }}</small></span>
                                            <span class="fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-top">
                                        <strong class="text-success">Total Belanja</strong>
                                        <strong class="text-success fs-5">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</strong>
                                    </li>
                                </ul>

                                @if($booking->status_booking == 'menunggu_bayar')
                                    <a href="{{ route('pembayaran.create', $booking->id_booking) }}" class="btn btn-warning w-100 fw-bold mt-2">
                                        <i class="bi bi-upload me-1"></i>Upload Bukti Pembayaran
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection

