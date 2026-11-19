<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate or delete existing products
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('produks')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $produks = [
            // --- KELANTING ---
            [
                'merk'       => 'Kelanting Bumbu Bawang Gurih',
                'harga'      => 15000,
                'stok'       => 60,
                'deskripsi'  => 'Kelanting gurih renyah khas Dusun 01 Purwodadi dengan racikan rempah bawang putih pilihan. Tekstur garing renyah dan tidak keras.',
                'status'     => 'tersedia',
                'gambar'     => 'kelanting_bawang.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'merk'       => 'Kelanting Pedas Manis Balado',
                'harga'      => 16000,
                'stok'       => 45,
                'deskripsi'  => 'Camilan kelanting tradisional dengan baluran bumbu balado pedas manis gurih yang bikin ketagihan.',
                'status'     => 'tersedia',
                'gambar'     => 'kelanting_balado.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'merk'       => 'Kelanting Jagung Manis Renyah',
                'harga'      => 15000,
                'stok'       => 35,
                'deskripsi'  => 'Kelanting renyah berpadu bumbu jagung manis bakar gurih, favorit semua usia untuk teman santai.',
                'status'     => 'tersedia',
                'gambar'     => 'kelanting_jagung.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'merk'       => 'Kelanting Manis Karamel Gula Aren',
                'harga'      => 15000,
                'stok'       => 40,
                'deskripsi'  => 'Kelanting khas dengan baluran karamel gula aren murni khas Trimurjo Lampung Tengah. Manis legit dan gurih.',
                'status'     => 'tersedia',
                'gambar'     => 'kelanting_manis.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // --- UNTIR-UNTIR (KUE TAMBANG) ---
            [
                'merk'       => 'Untir-Untir Manis Wijen Renyah',
                'harga'      => 12000,
                'stok'       => 50,
                'deskripsi'  => 'Kue tambang untir-untir goreng tradisional bertekstur renyah dengan taburan biji wijen wangi dan rasa manis pas.',
                'status'     => 'tersedia',
                'gambar'     => 'untir_untir_wijen.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'merk'       => 'Untir-Untir Gurih Asin Bawang',
                'harga'      => 12000,
                'stok'       => 40,
                'deskripsi'  => 'Kue untir-untir varian asin gurih dengan aroma daun bawang segar, empuk renyah tanpa keras.',
                'status'     => 'tersedia',
                'gambar'     => 'untir_untir_asin.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'merk'       => 'Untir-Untir Pandan Wangi',
                'harga'      => 13000,
                'stok'       => 30,
                'deskripsi'  => 'Kue untir-untir unik beraroma ekstrak daun pandan asli, harum wangi dan manis legit menggugah selera.',
                'status'     => 'tersedia',
                'gambar'     => 'untir_untir_pandan.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // --- KERIPIK SINGKONG ---
            [
                'merk'       => 'Keripik Singkong Original Bawang',
                'harga'      => 10000,
                'stok'       => 80,
                'deskripsi'  => 'Keripik singkong renyah hasil panen singkong lokal Dusun 01 Purwodadi, digoreng tipis renyah bumbu gurih alami.',
                'status'     => 'tersedia',
                'gambar'     => 'keripik_singkong_original.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'merk'       => 'Keripik Singkong Balado Pedas',
                'harga'      => 12000,
                'stok'       => 55,
                'deskripsi'  => 'Olahan keripik singkong dengan bumbu cabai merah pedas manis mantap khas Lampung Tengah.',
                'status'     => 'tersedia',
                'gambar'     => 'keripik_singkong_balado.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'merk'       => 'Keripik Singkong Keju Gurih',
                'harga'      => 13000,
                'stok'       => 40,
                'deskripsi'  => 'Keripik singkong renyah dengan taburan bubuk keju premium yang gurih creamy dan lezat.',
                'status'     => 'tersedia',
                'gambar'     => 'keripik_singkong_keju.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // --- KERIPIK PISANG KHAS LAMPUNG ---
            [
                'merk'       => 'Keripik Pisang Cokelat Khas Lampung',
                'harga'      => 18000,
                'stok'       => 65,
                'deskripsi'  => 'Oleh-oleh legendaris khas Lampung dari pisang kepok pilihan dengan lumuran bubuk cokelat tebal dan lumer di mulut.',
                'status'     => 'tersedia',
                'gambar'     => 'keripik_pisang_cokelat.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'merk'       => 'Keripik Pisang Keju Susu',
                'harga'      => 18000,
                'stok'       => 50,
                'deskripsi'  => 'Keripik pisang khas Lampung dengan balutan keju manis gurih dan susu yang creamy renyah.',
                'status'     => 'tersedia',
                'gambar'     => 'keripik_pisang_keju.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // --- ANEKA CAMILAN TRADISIONAL LAINNYA ---
            [
                'merk'       => 'Stik Bawang Gurih Renyah',
                'harga'      => 12000,
                'stok'       => 45,
                'deskripsi'  => 'Stik bawang renyah empuk dengan wangi irisan daun seledri segar dan rempah bawang gurih.',
                'status'     => 'tersedia',
                'gambar'     => 'stik_bawang.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'merk'       => 'Peyek Kacang Tanah Daun Jeruk',
                'harga'      => 14000,
                'stok'       => 35,
                'deskripsi'  => 'Rempeyek kacang tanah melimpah dengan aroma harum daun jeruk wangi, super renyah dan gurih.',
                'status'     => 'tersedia',
                'gambar'     => 'peyek_kacang.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'merk'       => 'Kembang Goyang Manis Wijen',
                'harga'      => 13000,
                'stok'       => 30,
                'deskripsi'  => 'Kue tradisional kembang goyang berbentuk bunga cantik yang renyah manis gurih bertabur wijen.',
                'status'     => 'tersedia',
                'gambar'     => 'kembang_goyang.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('produks')->insert($produks);
    }
}
