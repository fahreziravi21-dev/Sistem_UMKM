@extends('layouts.admin')

@section('title', 'Review Pembayaran - #' . ($pembayaran->booking->kode_booking ?? ''))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-shield-check me-2 text-primary"></i>Review Bukti Pembayaran</h4>
        <small class="text-muted">Verifikasi kesesuaian dana transfer dengan pesanan</small>
    </div>
    <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-4">
    <!-- Gambar Bukti Transfer -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-image me-2"></i>Foto Bukti Transfer / Gateway</h6>
            </div>
            <div class="card-body text-center p-3">
                @if($pembayaran->bukti && file_exists(public_path('uploads/bukti/' . $pembayaran->bukti)))
                    <a href="{{ asset('uploads/bukti/' . $pembayaran->bukti) }}" target="_blank" title="Klik untuk memperbesar gambar">
                        <img src="{{ asset('uploads/bukti/' . $pembayaran->bukti) }}" alt="Bukti Transfer" class="img-fluid rounded border shadow-sm" style="max-height: 480px;">
                    </a>
                    <small class="text-muted d-block mt-2"><i class="bi bi-zoom-in me-1"></i>Klik gambar di atas untuk membuka ukuran penuh di tab baru.</small>
                @else
                    <div class="py-5 bg-light rounded border">
                        <span class="fs-1 d-block mb-2">⚡</span>
                        <h5 class="fw-bold text-success">Pembayaran Payment Gateway</h5>
                        <p class="text-muted small mb-0">Transaksi diverifikasi secara otomatis melalui Midtrans Snap Gateway tanpa perlu unggah struk manual.</p>
                        @if($pembayaran->transaksi_id)
                            <div class="mt-3">
                                <span class="badge bg-primary p-2 font-monospace">ID: {{ $pembayaran->transaksi_id }}</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Rincian & Form Verifikasi -->
    <div class="col-lg-6">
        <!-- Informasi Transaksi -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-info-circle me-2"></i>Data Transaksi & Rekening</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted" style="width: 160px;">Kode Pesanan</td>
                        <td>
                            <a href="{{ route('admin.booking.show', $pembayaran->id_booking) }}" class="fw-bold font-monospace text-primary text-decoration-none">
                                {{ $pembayaran->booking->kode_booking ?? '-' }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama Pembeli (Akun)</td>
                        <td><strong>{{ $pembayaran->booking->pengguna->nama_pengguna ?? 'User' }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama Penerima</td>
                        <td>{{ $pembayaran->booking->nama_penerima ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Tagihan Pesanan</td>
                        <td class="fw-bold fs-6 text-dark">
                            Rp {{ number_format($pembayaran->booking->total_harga ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="table-light border-top">
                        <td class="text-muted">Bank Pengirim</td>
                        <td><strong class="text-primary">{{ $pembayaran->bank_pengirim }}</strong></td>
                    </tr>
                    <tr class="table-light">
                        <td class="text-muted">Nama Pemilik Rekening</td>
                        <td><strong>{{ $pembayaran->nama_rekening }}</strong></td>
                    </tr>
                    <tr class="table-light">
                        <td class="text-muted">Nomor Rekening</td>
                        <td><code>{{ $pembayaran->no_rekening }}</code></td>
                    </tr>
                    <tr class="table-light">
                        <td class="text-muted">Nominal Ditransfer</td>
                        <td class="fw-bold fs-5 text-success">
                            Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Waktu Pengiriman</td>
                        <td>{{ date('d F Y, H:i:s', strtotime($pembayaran->tgl_pembayaran)) }} WIB</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Form Keputusan Verifikasi -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-pencil-square me-2"></i>Keputusan Verifikasi</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.pembayaran.verifikasi', $pembayaran->id_pembayaran) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="status_konfirmasi" class="form-label fw-semibold">Tindakan / Hasil Verifikasi <span class="text-danger">*</span></label>
                        <select name="status_konfirmasi" id="status_konfirmasi" class="form-select form-select-lg fw-bold" required>
                            <option value="terverifikasi" {{ $pembayaran->status_konfirmasi == 'terverifikasi' ? 'selected' : '' }}>
                                ✅ Terverifikasi (Dana Masuk & Sesuai)
                            </option>
                            <option value="ditolak" {{ $pembayaran->status_konfirmasi == 'ditolak' ? 'selected' : '' }}>
                                ❌ Tolak Pembayaran (Tidak Sesuai / Palsu)
                            </option>
                        </select>
                        <small class="text-muted d-block mt-1">Jika terverifikasi, status pesanan otomatis berubah menjadi <strong>Dibayar</strong>.</small>
                    </div>

                    <div class="mb-4">
                        <label for="catatan_admin" class="form-label fw-semibold">Catatan Admin (Opsional / Wajib jika ditolak)</label>
                        <textarea name="catatan_admin" id="catatan_admin" rows="3" class="form-control" 
                            placeholder="Contoh: Dana belum masuk mutasi rekening / nominal kurang / bukti buram...">{{ old('catatan_admin', $pembayaran->catatan_admin) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-success fw-bold px-4">
                            <i class="bi bi-check-lg me-1"></i>Simpan Keputusan Verifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

