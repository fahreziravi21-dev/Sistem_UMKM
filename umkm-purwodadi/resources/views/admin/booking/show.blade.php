@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $booking->kode_booking)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-receipt me-2 text-primary"></i>Detail Pesanan #{{ $booking->kode_booking }}</h4>
        <small class="text-muted">Tanggal Booking: {{ date('d F Y, H:i', strtotime($booking->tgl_booking)) }} WIB</small>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="bi bi-printer me-1"></i>Cetak
        </button>
        <a href="{{ route('admin.booking.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Detail & Item -->
    <div class="col-lg-8">
        <!-- Informasi Pengiriman -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-geo-alt me-2"></i>Informasi Penerima & Pengiriman</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Nama Penerima</small>
                        <strong class="fs-6">{{ $booking->nama_penerima }}</strong>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Nomor Telepon / WhatsApp</small>
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $booking->no_tlp_penerima)) }}" target="_blank" class="fw-bold text-success text-decoration-none">
                            <i class="bi bi-whatsapp me-1"></i>{{ $booking->no_tlp_penerima }}
                        </a>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Alamat Lengkap Pengiriman</small>
                        <p class="mb-0 bg-light p-2 rounded border">{{ $booking->alamat_pengiriman }}</p>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Akun Terdaftar</small>
                        <span>{{ $booking->pengguna->nama_pengguna ?? 'User' }} ({{ $booking->pengguna->username ?? '-' }})</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Item Pesanan -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-basket2 me-2"></i>Rincian Produk yang Dipesan</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th>Produk Camilan</th>
                                <th class="text-end" style="width: 140px;">Harga Satuan</th>
                                <th class="text-center" style="width: 90px;">Jumlah</th>
                                <th class="text-end" style="width: 150px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($booking->detailBookings as $idx => $d)
                                <tr>
                                    <td class="text-center">{{ $idx + 1 }}</td>
                                    <td>
                                        <strong>{{ $d->produk->merk ?? 'Produk' }}</strong>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $d->jumlah }} pcs</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-success">
                                <td colspan="4" class="text-end fw-bold">TOTAL HARGA:</td>
                                <td class="text-end fw-bold text-success fs-5">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Status & Pembayaran -->
    <div class="col-lg-4">
        <!-- Status Pesanan Form -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-arrow-repeat me-2"></i>Ubah Status Pesanan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.booking.status', $booking->id_booking) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="status_booking" class="form-label fw-semibold">Status Saat Ini:</label>
                        <select name="status_booking" id="status_booking" class="form-select form-select-lg fw-bold 
                            @if($booking->status_booking == 'menunggu_bayar') text-warning border-warning
                            @elseif($booking->status_booking == 'dibayar') text-info border-info
                            @elseif($booking->status_booking == 'dikirim') text-primary border-primary
                            @elseif($booking->status_booking == 'selesai') text-success border-success
                            @else text-danger border-danger @endif">
                            <option value="menunggu_bayar" {{ $booking->status_booking == 'menunggu_bayar' ? 'selected' : '' }}>⏳ Menunggu Bayar</option>
                            <option value="dibayar" {{ $booking->status_booking == 'dibayar' ? 'selected' : '' }}>💳 Dibayar (Siap Diproses)</option>
                            <option value="dikirim" {{ $booking->status_booking == 'dikirim' ? 'selected' : '' }}>🚚 Dikirim</option>
                            <option value="selesai" {{ $booking->status_booking == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                            <option value="batal" {{ $booking->status_booking == 'batal' ? 'selected' : '' }}>❌ Batal (Kembalikan Stok)</option>
                        </select>
                        <small class="text-muted d-block mt-1">Mengubah ke 'Batal' akan mengembalikan jumlah stok produk ke sistem.</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="bi bi-save me-1"></i>Perbarui Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Pembayaran -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-credit-card me-2"></i>Data Pembayaran</h6>
            </div>
            <div class="card-body">
                @if($booking->pembayaran)
                    <div class="mb-3">
                        <small class="text-muted d-block">Status Verifikasi</small>
                        @if($booking->pembayaran->status_konfirmasi == 'terverifikasi')
                            <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i>Terverifikasi</span>
                        @elseif($booking->pembayaran->status_konfirmasi == 'menunggu')
                            <span class="badge bg-warning text-dark fs-6"><i class="bi bi-hourglass-split me-1"></i>Menunggu Verifikasi</span>
                        @else
                            <span class="badge bg-danger fs-6"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                        @endif
                    </div>

                    <table class="table table-sm table-borderless mb-3">
                        <tr>
                            <td class="text-muted">Metode:</td>
                            <td><span class="badge bg-secondary font-monospace">{{ strtoupper($booking->pembayaran->metode ?? 'MANUAL') }}</span></td>
                        </tr>
                        @if($booking->pembayaran->transaksi_id)
                        <tr>
                            <td class="text-muted">ID Transaksi:</td>
                            <td><code class="text-primary">{{ $booking->pembayaran->transaksi_id }}</code></td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted">Bank / Saluran:</td>
                            <td><strong>{{ $booking->pembayaran->bank_pengirim }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">A.n / Pengirim:</td>
                            <td>{{ $booking->pembayaran->nama_rekening ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">No Rek / VA:</td>
                            <td><code>{{ $booking->pembayaran->no_rekening ?? '-' }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nominal:</td>
                            <td class="fw-bold text-success">Rp {{ number_format($booking->pembayaran->nominal, 0, ',', '.') }}</td>
                        </tr>
                    </table>

                    @if($booking->pembayaran->bukti && file_exists(public_path('uploads/bukti/' . $booking->pembayaran->bukti)))
                        <div class="text-center mb-3">
                            <small class="text-muted d-block mb-1">Bukti Transfer:</small>
                            <a href="{{ asset('uploads/bukti/' . $booking->pembayaran->bukti) }}" target="_blank">
                                <img src="{{ asset('uploads/bukti/' . $booking->pembayaran->bukti) }}" alt="Bukti Transfer" class="img-thumbnail" style="max-height: 140px;">
                            </a>
                        </div>
                    @else
                        <div class="alert alert-light text-center py-2 mb-3 border">
                            <i class="bi bi-shield-check text-success fs-4 d-block"></i>
                            <small class="text-success fw-bold">Verifikasi Otomatis Payment Gateway</small>
                        </div>
                    @endif

                    <a href="{{ route('admin.pembayaran.show', $booking->pembayaran->id_pembayaran) }}" class="btn btn-outline-info w-100 fw-semibold">
                        <i class="bi bi-check2-square me-1"></i>Buka Verifikasi Pembayaran
                    </a>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-cash-stack fs-1 d-block mb-2 text-warning"></i>
                        <p class="mb-0">Pembeli belum mengunggah bukti pembayaran.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

