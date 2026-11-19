@if($produks->isEmpty())
    <div class="col-12">
        <div class="text-center py-5 bg-light rounded border">
            <span class="fs-1 d-block mb-3">🔍</span>
            <h5 class="fw-bold text-dark">Tidak Ada Produk Ditemukan</h5>
            <p class="text-muted mb-0">Coba gunakan kata kunci pencarian yang lain atau reset filter status produk.</p>
        </div>
    </div>
@else
    @foreach($produks as $produk)
        @php
            $nama = strtolower($produk->merk);
            if (str_contains($nama, 'kelanting')) {
                $emoji = '🥨';
            } elseif (str_contains($nama, 'untir')) {
                $emoji = '🍩';
            } elseif (str_contains($nama, 'keripik') || str_contains($nama, 'singkong')) {
                $emoji = '🥔';
            } else {
                $emoji = '🍿';
            }
        @endphp
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card h-100 shadow-sm border-0 product-card position-relative">
                <!-- Status Badge Floating -->
                <div class="position-absolute top-0 end-0 m-2" style="z-index: 2;">
                    @if($produk->status === 'tersedia' && $produk->stok > 0)
                        <span class="badge bg-success shadow-sm"><i class="bi bi-check-circle me-1"></i>Tersedia</span>
                    @else
                        <span class="badge bg-danger shadow-sm"><i class="bi bi-x-circle me-1"></i>Habis</span>
                    @endif
                </div>

                <!-- Product Image / Emoji -->
                <div class="product-image-placeholder text-center bg-light py-4 rounded-top position-relative overflow-hidden" style="height: 180px;">
                    @if($produk->gambar && file_exists(public_path('uploads/produk/' . $produk->gambar)))
                        <img src="{{ asset('uploads/produk/' . $produk->gambar) }}" alt="{{ $produk->merk }}" class="img-fluid h-100 w-100" style="object-fit: cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100 fs-1">
                            {{ $emoji }}
                        </div>
                    @endif
                </div>

                <!-- Card Body -->
                <div class="card-body d-flex flex-column p-3">
                    <h5 class="card-title fw-bold text-dark mb-1">{{ $produk->merk }}</h5>
                    <p class="card-text text-muted small flex-grow-1 mb-2">
                        {{ \Illuminate\Support\Str::limit($produk->deskripsi ?? 'Camilan tradisional khas Dusun 01 Purwodadi Trimurjo.', 75) }}
                    </p>

                    <div class="d-flex justify-content-between align-items-baseline mb-3">
                        <span class="fs-5 fw-bold text-success">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </span>
                        <small class="text-muted">
                            <i class="bi bi-box-seam me-1"></i>Stok: <strong>{{ $produk->stok }}</strong>
                        </small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('katalog.detail', $produk->id_produk ?? $produk->id) }}" class="btn btn-outline-secondary btn-sm flex-grow-1">
                            <i class="bi bi-info-circle me-1"></i>Detail
                        </a>
                        @if($produk->status === 'tersedia' && $produk->stok > 0)
                            <form action="{{ route('keranjang.tambah') }}" method="POST" class="flex-grow-1">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $produk->id_produk ?? $produk->id }}">
                                <input type="hidden" name="jumlah" value="1">
                                <button type="submit" class="btn btn-success btn-sm w-100 fw-semibold">
                                    <i class="bi bi-cart-plus me-1"></i>Beli
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary btn-sm flex-grow-1" disabled>
                                Habis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

