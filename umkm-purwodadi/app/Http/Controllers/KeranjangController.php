<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function index()
    {
        $keranjang = session()->get('keranjang', []);
        $total = 0;
        foreach ($keranjang as $item) {
            $total += ($item['harga'] ?? 0) * ($item['jumlah'] ?? 1);
        }

        return view('keranjang.index', compact('keranjang', 'total'));
    }

    public function tambah(Request $request)
    {
        $idProduk = $request->input('id_produk', $request->input('id'));
        $jumlahDiminta = (int) $request->input('jumlah', 1);

        $produk = Produk::findOrFail($idProduk);

        if ($produk->status !== 'tersedia' || $produk->stok < 1) {
            return back()->with('error', 'Maaf, stok produk sedang habis.');
        }

        $keranjang = session()->get('keranjang', []);

        if (isset($keranjang[$produk->id_produk])) {
            $jumlahBaru = $keranjang[$produk->id_produk]['jumlah'] + $jumlahDiminta;
            if ($jumlahBaru > $produk->stok) {
                return back()->with('error', "Stok tidak mencukupi. Maksimal stok tersedia: {$produk->stok}.");
            }
            $keranjang[$produk->id_produk]['jumlah'] = $jumlahBaru;
            $keranjang[$produk->id_produk]['subtotal'] = $jumlahBaru * $produk->harga;
        } else {
            if ($jumlahDiminta > $produk->stok) {
                return back()->with('error', "Stok tidak mencukupi. Maksimal stok tersedia: {$produk->stok}.");
            }
            $keranjang[$produk->id_produk] = [
                'id_produk' => $produk->id_produk,
                'merk' => $produk->merk,
                'nama' => $produk->merk,
                'harga' => $produk->harga,
                'jumlah' => $jumlahDiminta,
                'subtotal' => $jumlahDiminta * $produk->harga,
                'gambar' => $produk->gambar,
                'stok' => $produk->stok,
            ];
        }

        session()->put('keranjang', $keranjang);

        return redirect()->route('keranjang.index')->with('success', "{$produk->merk} berhasil ditambahkan ke keranjang!");
    }

    public function update(Request $request)
    {
        $id = $request->input('key', $request->input('id_produk'));
        $jumlah = (int) $request->input('jumlah', 1);

        $keranjang = session()->get('keranjang', []);

        if (isset($keranjang[$id])) {
            $produk = Produk::find($id);
            if ($produk && $jumlah > $produk->stok) {
                return back()->with('error', "Stok {$produk->merk} hanya tersedia {$produk->stok}.");
            }

            $keranjang[$id]['jumlah'] = max(1, $jumlah);
            $keranjang[$id]['subtotal'] = $keranjang[$id]['jumlah'] * ($keranjang[$id]['harga'] ?? 0);
            session()->put('keranjang', $keranjang);
            return back()->with('success', 'Keranjang belanja berhasil diperbarui.');
        }

        return back()->with('error', 'Item tidak ditemukan di keranjang.');
    }

    public function hapus($id)
    {
        $keranjang = session()->get('keranjang', []);

        if (isset($keranjang[$id])) {
            unset($keranjang[$id]);
            session()->put('keranjang', $keranjang);
            return back()->with('success', 'Item berhasil dihapus dari keranjang.');
        }

        return back()->with('error', 'Item tidak ditemukan.');
    }

    public function kosongkan()
    {
        session()->forget('keranjang');
        return redirect()->route('keranjang.index')->with('success', 'Keranjang berhasil dikosongkan.');
    }

    public function checkout()
    {
        $keranjang = session()->get('keranjang', []);

        if (empty($keranjang)) {
            return redirect()->route('katalog.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        if (!session()->has('pengguna')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melakukan checkout.');
        }

        $total = 0;
        foreach ($keranjang as $item) {
            $total += ($item['harga'] ?? 0) * ($item['jumlah'] ?? 1);
        }

        $pengguna = session()->get('pengguna');

        return view('keranjang.checkout', compact('keranjang', 'total', 'pengguna'));
    }
}
