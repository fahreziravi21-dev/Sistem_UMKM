<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('merk', 'like', '%' . $cari . '%')
                    ->orWhere('deskripsi', 'like', '%' . $cari . '%');
            });
        }

        if ($request->filled('status') && in_array($request->status, ['tersedia', 'habis'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'harga_asc') {
                $query->orderBy('harga', 'asc');
            } elseif ($request->sort === 'harga_desc') {
                $query->orderBy('harga', 'desc');
            } elseif ($request->sort === 'stok') {
                $query->orderBy('stok', 'desc');
            } else {
                $query->orderBy('id_produk', 'desc');
            }
        } else {
            $query->orderBy('id_produk', 'desc');
        }

        $produks = $query->paginate(9)->withQueryString();

        // Jika request via AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'total' => $produks->total(),
                'html' => view('katalog._produk_grid', compact('produks'))->render(),
            ]);
        }

        return view('katalog.index', compact('produks'));
    }

    public function ajaxSearch(Request $request)
    {
        $query = Produk::query();

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('merk', 'like', '%' . $cari . '%')
                    ->orWhere('deskripsi', 'like', '%' . $cari . '%');
            });
        }

        if ($request->filled('status') && in_array($request->status, ['tersedia', 'habis'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'harga_asc') {
                $query->orderBy('harga', 'asc');
            } elseif ($request->sort === 'harga_desc') {
                $query->orderBy('harga', 'desc');
            } elseif ($request->sort === 'stok') {
                $query->orderBy('stok', 'desc');
            } else {
                $query->orderBy('id_produk', 'desc');
            }
        } else {
            $query->orderBy('id_produk', 'desc');
        }

        $produks = $query->get();

        $html = view('katalog._produk_grid', compact('produks'))->render();

        return response()->json([
            'success' => true,
            'total' => $produks->count(),
            'html' => $html,
            'data' => $produks->map(function ($p) {
                return [
                    'id_produk' => $p->id_produk,
                    'merk' => $p->merk,
                    'harga' => $p->harga,
                    'harga_formatted' => 'Rp ' . number_format($p->harga, 0, ',', '.'),
                    'stok' => $p->stok,
                    'status' => $p->status,
                    'deskripsi' => $p->deskripsi,
                    'gambar' => $p->gambar,
                    'url' => route('katalog.detail', $p->id_produk),
                ];
            }),
        ]);
    }

    public function detail($id)
    {
        $produk = Produk::findOrFail($id);
        $produkLainnya = Produk::where('id_produk', '!=', $id)
            ->where('status', 'tersedia')
            ->take(3)
            ->get();

        return view('katalog.detail', compact('produk', 'produkLainnya'));
    }
}
