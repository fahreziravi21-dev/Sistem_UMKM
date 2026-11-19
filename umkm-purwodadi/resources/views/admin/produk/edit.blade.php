@extends('layouts.admin')

@section('title', 'Edit Produk - ' . $produk->merk)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Data Produk</h4>
        <small class="text-muted">Perbarui data produk makanan ringan UMKM Purwodadi</small>
    </div>
    <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Produk
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('admin.produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-8">
                    <label for="merk" class="form-label fw-semibold">Nama / Merk Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('merk') is-invalid @enderror" 
                        id="merk" name="merk" value="{{ old('merk', $produk->merk) }}" 
                        placeholder="Contoh: Kelanting Gurih Renyah" required>
                    @error('merk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="harga" class="form-label fw-semibold">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                        id="harga" name="harga" value="{{ old('harga', $produk->harga) }}" 
                        min="0" required>
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="stok" class="form-label fw-semibold">Jumlah Stok (Pcs) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('stok') is-invalid @enderror" 
                        id="stok" name="stok" value="{{ old('stok', $produk->stok) }}" 
                        min="0" required>
                    <small class="text-muted">Jika stok diisi 0, status produk otomatis menjadi 'habis'.</small>
                    @error('stok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label fw-semibold">Status Ketersediaan <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="tersedia" {{ old('status', $produk->status) == 'tersedia' ? 'selected' : '' }}>Tersedia (Bisa dipesan)</option>
                        <option value="habis" {{ old('status', $produk->status) == 'habis' ? 'selected' : '' }}>Habis (Kosong)</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="deskripsi" class="form-label fw-semibold">Deskripsi Produk</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                        id="deskripsi" name="deskripsi" rows="3" 
                        placeholder="Keterangan rasa, bahan baku, berat kemasan...">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="gambar" class="form-label fw-semibold">Foto / Gambar Produk</label>
                    <input type="file" class="form-control @error('gambar') is-invalid @enderror" 
                        id="gambar" name="gambar" accept="image/*" onchange="previewImage(event)">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto saat ini. Format JPG, PNG, WEBP (maks 2MB).</small>
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <!-- Preview area -->
                    <div class="mt-3 d-flex align-items-center gap-3">
                        @if($produk->gambar)
                            <div>
                                <small class="text-muted d-block mb-1">Foto Saat Ini:</small>
                                <img src="{{ asset('uploads/produk/' . $produk->gambar) }}" alt="{{ $produk->merk }}" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @endif
                        <div id="newPreviewContainer" class="d-none">
                            <small class="text-muted d-block mb-1">Foto Baru Dipilih:</small>
                            <img id="newImagePreview" src="#" alt="Preview Foto Baru" class="img-thumbnail border-primary" style="max-height: 100px;">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary fw-bold px-4">
                    <i class="bi bi-save me-1"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImage(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('newImagePreview').src = e.target.result;
            document.getElementById('newPreviewContainer').classList.remove('d-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection

