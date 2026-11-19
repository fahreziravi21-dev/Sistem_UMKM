<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('penggunas')->insert([
            [
                'nama_pengguna' => 'Administrator UMKM',
                'username'      => 'admin',
                'password'      => bcrypt('admin123'),
                'no_tlp'        => '081234567890',
                'alamat'        => 'Dusun 01 Purwodadi, Trimurjo, Lampung Tengah',
                'level'         => 'admin',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama_pengguna' => 'Siti Aminah',
                'username'      => 'pelanggan1',
                'password'      => bcrypt('user123'),
                'no_tlp'        => '085712345678',
                'alamat'        => 'Jl. Sudirman No. 12, Metro',
                'level'         => 'pembeli',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
