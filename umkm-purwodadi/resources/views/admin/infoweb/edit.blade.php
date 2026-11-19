@extends('layouts.admin')

@section('title', 'Pengaturan Website & Rekening - Admin UMKM')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-gear me-2 text-primary"></i>Pengaturan Website & Rekening Toko</h4>
        <small class="text-muted">Konfigurasi identitas UMKM, nomor kontak resmi, dan rekening tujuan transfer</small>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-shop me-2"></i>Informasi Resmi UMKM Dusun 01 Purwodadi</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.infoweb.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">1. Identitas & Kontak UMKM</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="nama_web" class="form-label fw-semibold">Nama Sistem / Toko <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_web') is-invalid @enderror" 
                                id="nama_web" name="nama_web" value="{{ old('nama_web', $infoweb->nama_web ?? '') }}" required>
                            @error('nama_web')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="telp" class="form-label fw-semibold">Nomor Telepon / WhatsApp Resmi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('telp') is-invalid @enderror" 
                                id="telp" name="telp" value="{{ old('telp', $infoweb->telp ?? '') }}" 
                                placeholder="08xxxxxxxxxx" required>
                            @error('telp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Alamat Email Resmi <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email', $infoweb->email ?? '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="alamat" class="form-label fw-semibold">Alamat Lengkap Sentra UMKM <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                id="alamat" name="alamat" rows="2" required>{{ old('alamat', $infoweb->alamat ?? '') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">2. Rekening Bank Tujuan Transfer (Untuk Pelanggan)</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="bank_toko" class="form-label fw-semibold">Nama Bank <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('bank_toko') is-invalid @enderror" 
                                id="bank_toko" name="bank_toko" value="{{ old('bank_toko', $infoweb->bank_toko ?? '') }}" 
                                placeholder="Contoh: Bank BRI / Bank BCA" required>
                            @error('bank_toko')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="no_rek_toko" class="form-label fw-semibold">Nomor Rekening <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('no_rek_toko') is-invalid @enderror" 
                                id="no_rek_toko" name="no_rek_toko" value="{{ old('no_rek_toko', $infoweb->no_rek_toko ?? '') }}" 
                                placeholder="Nomor rekening toko" required>
                            @error('no_rek_toko')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="atas_nama_toko" class="form-label fw-semibold">Atas Nama Rekening <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('atas_nama_toko') is-invalid @enderror" 
                                id="atas_nama_toko" name="atas_nama_toko" value="{{ old('atas_nama_toko', $infoweb->atas_nama_toko ?? '') }}" 
                                placeholder="Nama pemilik rekening" required>
                            @error('atas_nama_toko')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">3. Konfigurasi Payment Gateway QRIS (buatqris.site / EMVCo Dinamis)</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label for="qris_statis" class="form-label fw-semibold">
                                String Kode QRIS Statis Toko
                                <span class="badge bg-success ms-2">Otomatis Jadi QRIS Dinamis</span>
                            </label>
                            <textarea class="form-control font-monospace @error('qris_statis') is-invalid @enderror" 
                                id="qris_statis" name="qris_statis" rows="3" 
                                placeholder="Contoh: 00020101021126580014ID.LINKAJA.WWW0118...6304A1B2">{{ old('qris_statis', $infoweb->qris_statis ?? '') }}</textarea>
                            <div class="form-text small text-muted">
                                <i class="bi bi-info-circle me-1"></i>Masukkan string teks QRIS yang Anda dapatkan dari Bank (BCA/BRI/Mandiri) atau e-wallet (GoPay/OVO/Dana/LinkAja/ShopeePay).
                                Sistem <strong>buatqris.site engine</strong> akan secara otomatis menyisipkan nominal pesanan (Tag 54) dan menghitung ulang checksum CRC16 sehingga pelanggan bisa langsung scan tanpa mengetik nominal secara manual. Jika dikosongkan, sistem memakai QRIS default UMKM Purwodadi.
                            </div>
                            @error('qris_statis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="bi bi-save me-1"></i>Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

