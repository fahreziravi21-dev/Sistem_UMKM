@extends('layouts.app')

@section('title', 'Katalog Produk - UMKM Purwodadi')

@section('styles')
<style>
    .product-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.12) !important;
    }
    .product-image-placeholder {
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        background: #f8f9fa;
        border-radius: 0.375rem 0.375rem 0 0;
    }
    .filter-btn.active {
        background-color: #198754 !important;
        border-color: #198754 !important;
        color: #fff !important;
    }
    .search-box-wrapper {
        position: relative;
    }
    .search-spinner {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }
</style>
@endsection

@section('content')
<div class="container py-5">

    {{-- Page Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold mb-1">
                <i class="bi bi-grid text-success me-2"></i>Katalog Produk Camilan
            </h2>
            <p class="text-muted mb-0">Temukan aneka makanan ringan tradisional khas Dusun 01 Purwodadi.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <span class="badge bg-light text-dark border p-2 fs-6">
                <i class="bi bi-box-seam me-1 text-success"></i>
                <span id="resultCount">{{ $produks->total() ?? count($produks) }}</span> Produk Ditemukan
            </span>
        </div>
    </div>

    {{-- Search & Filter Bar (Real-time AJAX) --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">

                {{-- Live Search Input with Debounce --}}
                <div class="col-md-5">
                    <div class="search-box-wrapper">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-success"></i>
                            </span>
                            <input type="text" id="ajaxSearchInput" class="form-control border-start-0 ps-0"
                                placeholder="Ketik nama camilan (contoh: Kelanting, Singkong, Untir)..."
                                value="{{ request('cari') }}" autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" id="clearSearchBtn" title="Hapus pencarian" style="display: {{ request('cari') ? 'block' : 'none' }};">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="search-spinner" id="searchSpinner">
                            <div class="spinner-border spinner-border-sm text-success" role="status">
                                <span class="visually-hidden">Mencari...</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filter Buttons --}}
                <div class="col-md-4">
                    <div class="d-flex flex-wrap gap-1 justify-content-md-center">
                        <button type="button" class="btn btn-outline-success btn-sm filter-btn {{ !request('status') ? 'active' : '' }}" data-status="">
                            <i class="bi bi-grid me-1"></i>Semua
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm filter-btn {{ request('status') === 'tersedia' ? 'active' : '' }}" data-status="tersedia">
                            <i class="bi bi-check-circle me-1"></i>Tersedia
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm filter-btn {{ request('status') === 'habis' ? 'active' : '' }}" data-status="habis">
                            <i class="bi bi-x-circle me-1"></i>Habis
                        </button>
                    </div>
                </div>

                {{-- Sort Dropdown --}}
                <div class="col-md-3">
                    <select id="sortSelect" class="form-select form-select-sm">
                        <option value="terbaru">Urutkan: Terbaru</option>
                        <option value="harga_asc">Harga: Termurah</option>
                        <option value="harga_desc">Harga: Tertinggi</option>
                        <option value="stok">Stok: Terbanyak</option>
                    </select>
                </div>

            </div>
        </div>
    </div>

    {{-- Product Grid Container (Replaced Dynamically via AJAX) --}}
    <div class="row g-4" id="produkContainer">
        @include('katalog._produk_grid', ['produks' => $produks])
    </div>

    {{-- Standard Pagination Container (hidden during live search if needed) --}}
    <div class="mt-4 d-flex justify-content-center" id="paginationContainer">
        @if(method_exists($produks, 'links'))
            {{ $produks->links() }}
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('ajaxSearchInput');
    const clearBtn = document.getElementById('clearSearchBtn');
    const spinner = document.getElementById('searchSpinner');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const sortSelect = document.getElementById('sortSelect');
    const produkContainer = document.getElementById('produkContainer');
    const resultCount = document.getElementById('resultCount');
    const paginationContainer = document.getElementById('paginationContainer');

    let currentStatus = '{{ request("status", "") }}';
    let currentSort = 'terbaru';
    let debounceTimer = null;

    // Trigger live AJAX search
    function performAjaxSearch() {
        const keyword = searchInput.value.trim();

        // Tampilkan/sembunyikan tombol clear
        if (keyword.length > 0) {
            clearBtn.style.display = 'block';
        } else {
            clearBtn.style.display = 'none';
        }

        // Tampilkan spinner loading
        spinner.style.display = 'block';

        const url = new URL('{{ route("katalog.ajax-search") }}', window.location.origin);
        if (keyword) url.searchParams.set('cari', keyword);
        if (currentStatus) url.searchParams.set('status', currentStatus);
        if (currentSort) url.searchParams.set('sort', currentSort);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            spinner.style.display = 'none';
            if (data.success) {
                // Update product cards
                produkContainer.innerHTML = data.html;
                resultCount.innerText = data.total;

                // Sembunyikan pagination saat live search aktif agar tidak membingungkan
                if (keyword || currentSort !== 'terbaru' || currentStatus) {
                    if (paginationContainer) paginationContainer.style.display = 'none';
                } else {
                    if (paginationContainer) paginationContainer.style.display = 'flex';
                }
            }
        })
        .catch(error => {
            spinner.style.display = 'none';
            console.error('AJAX Search Error:', error);
        });
    }

    // Input Debounce Handler (250ms delay)
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(performAjaxSearch, 250);
    });

    // Clear Button Handler
    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        clearBtn.style.display = 'none';
        performAjaxSearch();
    });

    // Filter Buttons Handler
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentStatus = this.getAttribute('data-status');
            performAjaxSearch();
        });
    });

    // Sort Dropdown Handler
    sortSelect.addEventListener('change', function () {
        currentSort = this.value;
        performAjaxSearch();
    });
});
</script>
@endpush
