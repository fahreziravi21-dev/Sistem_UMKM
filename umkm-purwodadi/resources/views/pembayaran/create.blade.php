@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran - UMKM Purwodadi')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('booking.invoice', $booking->kode_booking) }}">Invoice</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Konfirmasi Pembayaran</li>
                </ol>
            </nav>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-upload me-2"></i>Form Konfirmasi & Upload Bukti Pembayaran</h5>
                    <small class="opacity-75">Pesanan: {{ $booking->kode_booking }}</small>
                </div>

                <div class="card-body p-4">
                    <!-- Ringkasan Tagihan -->
                    <div class="p-3 bg-light rounded border mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <small class="text-muted d-block">Kode Booking / Pesanan</small>
                                <span class="fw-bold font-monospace fs-5 text-dark">{{ $booking->kode_booking }}</span>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <small class="text-muted d-block">Total yang Harus Ditransfer</small>
                                <span class="fw-bold fs-4 text-success">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Rekening Tujuan UMKM -->
                    <div class="alert alert-info border-info mb-4">
                        <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-1"></i>Rekening Resmi Tujuan Transfer:</h6>
                        <div class="d-flex flex-wrap gap-3">
                            <div><strong>Bank:</strong> {{ $infoweb->bank_toko ?? 'Bank BRI' }}</div>
                            <div><strong>No. Rekening:</strong> <span class="font-monospace fw-bold">{{ $infoweb->no_rek_toko ?? '1234-01-000567-53-8' }}</span></div>
                            <div><strong>Atas Nama:</strong> {{ $infoweb->atas_nama_toko ?? 'UMKM Purwodadi' }}</div>
                        </div>
                    </div>

                    <!-- Form Upload -->
                    <form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_booking" value="{{ $booking->id_booking }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="bank_pengirim" class="form-label fw-semibold">Bank Asal Pengirim <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('bank_pengirim') is-invalid @enderror" 
                                    id="bank_pengirim" name="bank_pengirim" 
                                    value="{{ old('bank_pengirim', $booking->pembayaran->bank_pengirim ?? '') }}" 
                                    placeholder="Contoh: BCA, BRI, Mandiri, BNI, Dana" required>
                                @error('bank_pengirim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="no_rekening" class="form-label fw-semibold">Nomor Rekening Pengirim <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('no_rekening') is-invalid @enderror" 
                                    id="no_rekening" name="no_rekening" 
                                    value="{{ old('no_rekening', $booking->pembayaran->no_rekening ?? '') }}" 
                                    placeholder="Nomor rekening yang digunakan transfer" required>
                                @error('no_rekening')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nama_rekening" class="form-label fw-semibold">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_rekening') is-invalid @enderror" 
                                    id="nama_rekening" name="nama_rekening" 
                                    value="{{ old('nama_rekening', $booking->pembayaran->nama_rekening ?? '') }}" 
                                    placeholder="Nama tertera di buku rekening / e-wallet" required>
                                @error('nama_rekening')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nominal" class="form-label fw-semibold">Nominal Ditransfer (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('nominal') is-invalid @enderror" 
                                    id="nominal" name="nominal" 
                                    value="{{ old('nominal', $booking->pembayaran->nominal ?? $booking->total_harga) }}" 
                                    min="1000" required>
                                @error('nominal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="bukti" class="form-label fw-semibold">Foto / Tangkapan Layar Bukti Transfer <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('bukti') is-invalid @enderror" 
                                    id="bukti" name="bukti" accept="image/*" onchange="previewImage(event)" required>
                                <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG, atau WEBP. Ukuran maksimal: 2MB.</small>
                                @error('bukti')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- Image Preview -->
                                <div class="mt-3 text-center d-none" id="previewContainer">
                                    <p class="text-muted small mb-1">Pratinjau Bukti Transfer:</p>
                                    <img id="imagePreview" src="#" alt="Preview Bukti" class="img-thumbnail" style="max-height: 220px;">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('booking.invoice', $booking->kode_booking) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Invoice
                            </a>
                            <button type="submit" class="btn btn-success fw-bold px-4">
                                <i class="bi bi-send-check me-1"></i>Kirim Konfirmasi Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('previewContainer').classList.remove('d-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection

