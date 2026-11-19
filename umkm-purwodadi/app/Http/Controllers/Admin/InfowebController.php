<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Infoweb;
use Illuminate\Http\Request;

class InfowebController extends Controller
{
    public function edit()
    {
        $infoweb = Infoweb::first();
        if (!$infoweb) {
            $infoweb = Infoweb::create([
                'nama_web' => 'UMKM Dusun 01 Purwodadi',
                'telp' => '081377889900',
                'alamat' => 'Dusun 01 Purwodadi, Kec. Trimurjo, Kab. Lampung Tengah, Lampung',
                'email' => 'umkmpurwodadi@gmail.com',
                'bank_toko' => 'Bank BRI',
                'no_rek_toko' => '1234-01-000567-53-8',
                'atas_nama_toko' => 'UMKM Purwodadi',
            ]);
        }

        return view('admin.infoweb.edit', compact('infoweb'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_web' => 'required|string|max:100',
            'telp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'email' => 'required|email|max:100',
            'bank_toko' => 'required|string|max:50',
            'no_rek_toko' => 'required|string|max:50',
            'atas_nama_toko' => 'required|string|max:100',
            'qris_statis' => 'nullable|string',
        ]);

        $infoweb = Infoweb::first();
        if (!$infoweb) {
            $infoweb = new Infoweb();
        }

        $infoweb->update($request->all());

        return back()->with('success', 'Informasi website & rekening toko berhasil diperbarui.');
    }
}

