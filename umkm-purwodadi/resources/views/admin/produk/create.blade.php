@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.produk.index') }}" class="text-decoration-none">Produk</a></li>
    <li class="breadcrumb-item active">Tambah Produk</li>
@endsection

@push('styles')
<style>
    .image-preview-box {
        width: 100%;
        max-width: 300px;
        height: 220px;
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f8f9fa;
        cursor: pointer;
        transition: border-color 0.2s;
    }
    .image-preview-box:hover {
        border-color: #0d6efd;
    }
    .image-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }
    .image-preview-placeholder {
        text-align: center;
        color: #adb5bd;
    }
    .image-preview-placeholder .icon {
        font-size: 2.5rem;
    }
</style>
@endpush

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2"></i>Form Tambah Produk Baru
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Terdapat kesalahan pada form:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.produk.store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="formTambahProduk">
                    @csrf

                    <div class="row g-4">

                        {{-- Left column: form fields --}}
                        <div class="col-md-7">

                            {{-- Merk / Nama Produk --}}
                            <div class="mb-3">
                                <label for="merk" class="form-label fw-semibold">
                                    Merk / Nama Produk <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       id="merk"
                                       name="merk"
                                       value="{{ old('merk') }}"
                                       class="form-control @error('merk') is-invalid @enderror"
                                       placeholder="Contoh: Beras Premium Rojolele"
                                       required>
                                @error('merk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Harga --}}
                            <div class="mb-3">
                                <label for="harga" class="form-label fw-semibold">
                                    Harga (Rp) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number"
                                           id="harga"
                                           name="harga"
                                           value="{{ old('harga') }}"
                                           class="form-control @error('harga') is-invalid @enderror"
                                           placeholder="0"
                                           min="0"
                                           required>
                                    @error('harga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Stok --}}
                            <div class="mb-3">
                                <label for="stok" class="form-label fw-semibold">
                                    Stok <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       id="stok"
                                       name="stok"
                                       value="{{ old('stok', 0) }}"
                                       class="form-control @error('stok') is-invalid @enderror"
                                       min="0"
                                       required>
                                @error('stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div class="mb-3">
                                <label for="status" class="form-label fw-semibold">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select id="status"
                                        name="status"
                                        class="form-select @error('status') is-invalid @enderror"
                                        required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="tersedia" {{ old('status') === 'tersedia' ? 'selected' : '' }}>
                                        ✅ Tersedia
                                    </option>
                                    <option value="habis" {{ old('status') === 'habis' ? 'selected' : '' }}>
                                        ❌ Habis
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label fw-semibold">Deskripsi Produk</label>
                                <textarea id="deskripsi"
                                          name="deskripsi"
                                          rows="4"
                                          class="form-control @error('deskripsi') is-invalid @enderror"
                                          placeholder="Tuliskan deskripsi produk...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Right column: image upload --}}
                        <div class="col-md-5">
                            <label class="form-label fw-semibold d-block">
                                Gambar Produk
                            </label>

                            {{-- Preview Box --}}
                            <div class="image-preview-box mb-2" id="previewBox" onclick="document.getElementById('gambar').click()">
                                <img id="previewImg" src="#" alt="Preview" style="display:none;">
                                <div class="image-preview-placeholder" id="previewPlaceholder">
                                    <div class="icon">🖼️</div>
                                    <div style="font-size:0.8rem;" class="mt-1">Klik untuk pilih gambar</div>
                                    <div style="font-size:0.72rem;">JPG, PNG, GIF (Maks. 2MB)</div>
                                </div>
                            </div>

                            <input type="file"
                                   id="gambar"
                                   name="gambar"
                                   accept="image/*"
                                   class="form-control @error('gambar') is-invalid @enderror"
                                   style="display:none;">

                            <button type="button"
                                    class="btn btn-outline-secondary btn-sm w-100 mb-1"
                                    onclick="document.getElementById('gambar').click()">
                                <i class="bi bi-upload me-1"></i> Upload Gambar
                            </button>

                            <div id="fileName" class="text-muted text-center" style="font-size:0.76rem;">
                                Belum ada file dipilih
                            </div>

                            @error('gambar')
                                <div class="text-danger mt-1" style="font-size:0.82rem;">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- Action Buttons --}}
                    <hr class="my-4">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Simpan Produk
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const gambarInput = document.getElementById('gambar');
    const previewImg  = document.getElementById('previewImg');
    const previewPlaceholder = document.getElementById('previewPlaceholder');
    const fileName    = document.getElementById('fileName');

    gambarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Show file name
        fileName.textContent = file.name;

        // Preview image
        const reader = new FileReader();
        reader.onload = function(ev) {
            previewImg.src = ev.target.result;
            previewImg.style.display = 'block';
            previewPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
