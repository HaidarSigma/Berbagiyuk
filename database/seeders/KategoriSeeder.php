<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
{
    foreach (['Pakaian','Buku & Alat Tulis','Furnitur','Perlengkapan Rumah','Perlengkapan Sekolah','Elektronik','Makanan & Minuman','Lainnya'] as $nama) {
        \App\Models\Kategori::create(['nama' => $nama]);
    }
}
}
