<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InfowebSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('infowebs')->insert([
            [
                'nama_web'      => 'UMKM Dusun 01 Purwodadi',
                'telp'          => '081377889900',
                'alamat'        => 'Dusun 01 Purwodadi, Kecamatan Trimurjo, Kabupaten Lampung Tengah, Provinsi Lampung 34172',
                'email'         => 'umkmpurwodadi@gmail.com',
                'bank_toko'     => 'Bank BRI',
                'no_rek_toko'   => '1234-01-000567-53-8',
                'atas_nama_toko'=> 'UMKM Purwodadi',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
