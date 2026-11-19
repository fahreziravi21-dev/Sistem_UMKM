@forelse($produks ?? [] as $index => $produk)
    <tr>
        <td class="text-muted" style="font-size:0.83rem;">
            {{ ($produks->currentPage() - 1) * $produks->perPage() + $loop->iteration }}
        </td>
        <td>
            @if($produk->gambar && file_exists(public_path('uploads/produk/' . $produk->gambar)))
                <img src="{{ asset('uploads/produk/' . $produk->gambar) }}"
                     alt="{{ $produk->merk }}"
                     class="rounded"
                     style="width:52px;height:52px;object-fit:cover;">
            @else
                <div class="rounded bg-light d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;font-size:1.3rem;">
                    📦
                </div>
            @endif
        </td>
        <td>
            <div class="fw-semibold" style="font-size:0.88rem;">{{ $produk->merk }}</div>
            @if($produk->deskripsi)
                <div class="text-muted" style="font-size:0.76rem;">
                    {{ \Illuminate\Support\Str::limit($produk->deskripsi, 60) }}
                </div>
            @endif
        </td>
        <td style="font-size:0.88rem;">
            <span class="fw-semibold">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
        </td>
        <td class="text-center">
            <span class="fw-bold {{ $produk->stok <= 0 ? 'text-danger' : ($produk->stok < 10 ? 'text-warning' : 'text-success') }}">
                {{ $produk->stok }}
            </span>
        </td>
        <td class="text-center">
            @if($produk->status === 'tersedia' && $produk->stok > 0)
                <span class="badge bg-success">Tersedia</span>
            @else
                <span class="badge bg-danger">Habis</span>
            @endif
        </td>
        <td class="text-center">
            <div class="d-flex gap-1 justify-content-center">
                <a href="{{ route('admin.produk.edit', $produk->id_produk ?? $produk->id) }}"
                   class="btn btn-sm btn-outline-warning"
                   title="Edit Produk">
                    <i class="bi bi-pencil-fill"></i>
                </a>
                <form action="{{ route('admin.produk.destroy', $produk->id_produk ?? $produk->id) }}"
                      method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus produk \'{{ addslashes($produk->merk) }}\'? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-sm btn-outline-danger"
                            title="Hapus Produk">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-muted py-4">
            <i class="bi bi-inbox fs-2 d-block mb-1"></i>
            Tidak ada data produk yang ditemukan.
        </td>
    </tr>
@endforelse

