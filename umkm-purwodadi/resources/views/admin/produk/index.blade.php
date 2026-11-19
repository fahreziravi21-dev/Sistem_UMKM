@extends('layouts.admin')

@section('title', 'Kelola Produk')
@section('page-title', 'Kelola Produk')

@section('breadcrumb')
    <li class="breadcrumb-item active">Produk</li>
@endsection

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2 bg-white py-3">
            <span class="fw-bold fs-5 text-primary">📦 Daftar Master Produk Camilan</span>
            <a href="{{ route('admin.produk.create') }}" class="btn btn-primary btn-sm fw-semibold">
                <i class="bi bi-plus-circle me-1"></i> Tambah Produk Baru
            </a>
        </div>

        <div class="card-body p-4">
            {{-- Search Bar (Live AJAX) --}}
            <div class="row g-2 align-items-center mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-primary"></i>
                        </span>
                        <input type="text" id="adminSearchInput" name="search" value="{{ request('search') }}"
                            class="form-control border-start-0" placeholder="Ketik nama produk untuk mencari langsung (AJAX)..." autocomplete="off">
                        <span class="input-group-text bg-white" id="adminSearchSpinner" style="display: none;">
                            <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                        </span>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="badge bg-light text-dark border p-2">
                        Total: <strong id="adminTotalProduk" class="text-primary">{{ $produks->total() }}</strong> Produk
                    </span>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th width="80">Gambar</th>
                            <th>Merk / Nama Produk</th>
                            <th>Harga</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="adminProdukTableBody">
                        @include('admin.produk._table_rows', ['produks' => $produks])
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div id="adminPaginationContainer" class="mt-4">
                @if(isset($produks) && $produks->hasPages())
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted" style="font-size:0.82rem;">
                            Menampilkan {{ $produks->firstItem() }}–{{ $produks->lastItem() }}
                            dari {{ $produks->total() }} produk
                        </div>
                        {{ $produks->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('adminSearchInput');
    const spinner = document.getElementById('adminSearchSpinner');
    const tbody = document.getElementById('adminProdukTableBody');
    const totalEl = document.getElementById('adminTotalProduk');
    const pagination = document.getElementById('adminPaginationContainer');

    let debounceTimer = null;

    if (input) {
        input.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            debounceTimer = setTimeout(function () {
                if (spinner) spinner.style.display = 'inline-block';

                const url = new URL('{{ route("admin.produk.index") }}', window.location.origin);
                if (query) url.searchParams.set('search', query);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (spinner) spinner.style.display = 'none';
                    if (data.success) {
                        tbody.innerHTML = data.html;
                        if (totalEl) totalEl.innerText = data.total;
                        if (pagination) {
                            pagination.style.display = query ? 'none' : 'block';
                        }
                    }
                })
                .catch(err => {
                    if (spinner) spinner.style.display = 'none';
                    console.error('Admin AJAX Search error:', err);
                });
            }, 250);
        });
    }
});
</script>
@endpush
