<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();

        $keyword = $request->cari ?? $request->search;
        if ($keyword) {
            $query->where('merk', 'like', '%' . $keyword . '%');
        }

        $produks = $query->orderBy('id_produk', 'desc')->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'total' => $produks->total(),
                'html' => view('admin.produk._table_rows', compact('produks'))->render(),
            ]);
        }

        return view('admin.produk.index', compact('produks'));
    }

    public function create()
    {
        return view('admin.produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'merk' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:tersedia,habis',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $fileName = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $fileName = 'prod_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = public_path('uploads/produk');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $file->move($path, $fileName);
        }

        Produk::create([
            'merk' => $request->merk,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'status' => $request->stok <= 0 ? 'habis' : $request->status,
            'gambar' => $fileName,
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'merk' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:tersedia,habis',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $fileName = $produk->gambar;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $fileName = 'prod_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = public_path('uploads/produk');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $file->move($path, $fileName);

            // Delete old file if exists
            if ($produk->gambar && file_exists(public_path('uploads/produk/' . $produk->gambar))) {
                @unlink(public_path('uploads/produk/' . $produk->gambar));
            }
        }

        $produk->update([
            'merk' => $request->merk,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'status' => $request->stok <= 0 ? 'habis' : $request->status,
            'gambar' => $fileName,
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Data produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Check if product is referenced in detail_bookings
        if ($produk->detailBookings()->count() > 0) {
            return back()->with('error', 'Produk tidak dapat dihapus karena sudah memiliki riwayat transaksi pemesanan.');
        }

        if ($produk->gambar && file_exists(public_path('uploads/produk/' . $produk->gambar))) {
            @unlink(public_path('uploads/produk/' . $produk->gambar));
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
