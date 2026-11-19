<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Infoweb;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $infoweb = Infoweb::first();
        if (!$infoweb) {
            $infoweb = new Infoweb([
                'nama_web' => 'UMKM Dusun 01 Purwodadi',
                'telp' => '081377889900',
                'alamat' => 'Dusun 01 Purwodadi, Kecamatan Trimurjo, Kabupaten Lampung Tengah',
                'email' => 'umkmpurwodadi@gmail.com',
                'bank_toko' => 'Bank BRI',
                'no_rek_toko' => '1234-01-000567-53-8',
                'atas_nama_toko' => 'UMKM Purwodadi',
            ]);
        }

        $produkUnggulan = Produk::where('status', 'tersedia')
            ->orderBy('id_produk', 'asc')
            ->take(6)
            ->get();

        $produkIkonik = [
            'kelanting' => Produk::where('merk', 'like', '%Kelanting%')->first(),
            'untir' => Produk::where('merk', 'like', '%Untir%')->first(),
            'pisang' => Produk::where('merk', 'like', '%Pisang%')->first() 
                ?? Produk::where('merk', 'like', '%Keripik%')->first(),
        ];

        $totalProduk = Produk::count();

        return view('beranda', compact('infoweb', 'produkUnggulan', 'produkIkonik', 'totalProduk'));
    }
}
