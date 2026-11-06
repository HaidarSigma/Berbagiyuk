<?php

namespace Database\Seeders;

use App\Models\{Barang, KebutuhanMendesak, User};
use Illuminate\Database\Seeder;

class DataContohSeeder extends Seeder
{
    public function run(): void
    {
        User::forceCreate([
            'name' => 'Admin', 'email' => 'admin@berbagiyuk.test',
            'password' => bcrypt('password'), 'role' => 'admin', 'terverifikasi' => true,
            'email_verified_at' => now(), 'pin' => bcrypt('123456'),
        ]);

        $donatur = User::forceCreate([
            'name' => 'Donatur Contoh', 'email' => 'donatur@berbagiyuk.test',
            'password' => bcrypt('password'), 'role' => 'donatur', 'terverifikasi' => true,
            'email_verified_at' => now(), 'pin' => bcrypt('123456'),
        ]);

        User::forceCreate([
    'name' => 'Penerima Contoh', 'email' => 'penerima@berbagiyuk.test',
    'password' => bcrypt('password'), 'role' => 'penerima', 'terverifikasi' => true,
    'email_verified_at' => now(), 'pin' => bcrypt('123456'),
    'no_hp' => '081234567890', 'alamat' => 'Banjarsari, Surakarta',
    'latitude' => -7.5561, 'longitude' => 110.8216,
    'jenis_penerima' => 'keluarga_kurang_mampu', 'status_penerima' => 'disetujui',
        ]);

        User::forceCreate([
    'name' => 'Penerima Menunggu', 'email' => 'penerima2@berbagiyuk.test',
    'password' => bcrypt('password'), 'role' => 'penerima', 'terverifikasi' => true,
    'email_verified_at' => now(), 'pin' => bcrypt('123456'),
    'no_hp' => '081298765432', 'alamat' => 'Jebres, Surakarta',
    'latitude' => -7.5622, 'longitude' => 110.8554,
    'jenis_penerima' => 'pelajar', 'status_penerima' => 'menunggu',
        ]);

        $data = [
            ['Pakaian', 1, 'Laweyan, Surakarta'],
            ['Buku Pelajaran SMP', 2, 'Banjarsari, Surakarta'],
            ['Meja dan Kursi Belajar', 3, 'Jebres, Surakarta'],
            ['Tas Sekolah', 5, 'Pasar Kliwon, Surakarta'],
            ['Beras 5kg + Mie Instan', 7, 'Serengan, Surakarta'],
            ['Laptop (Kondisi Baik)', 6, 'Banjarsari, Surakarta'],
            ['Sepatu Sneakers', 1, 'Laweyan, Surakarta'],
            ['Rak Buku', 3, 'Jebres, Surakarta'],
        ];
        foreach ($data as [$judul, $kat, $lokasi]) {
            Barang::create(['user_id' => $donatur->id, 'kategori_id' => $kat, 'judul' => $judul, 'lokasi' => $lokasi, 'verifikasi' => 'disetujui']);
        }

        // dua barang yang menunggu verifikasi admin
        Barang::create(['user_id' => $donatur->id, 'kategori_id' => 4, 'judul' => 'Kompor Gas 2 Tungku', 'lokasi' => 'Serengan, Surakarta']);
        Barang::create(['user_id' => $donatur->id, 'kategori_id' => 1, 'judul' => 'Jaket Anak', 'lokasi' => 'Pasar Kliwon, Surakarta']);

        KebutuhanMendesak::insert([
            ['judul' => 'Buku pelajaran untuk SD', 'keterangan' => 'Untuk anak-anak di panti asuhan', 'lokasi' => 'Banjarsari, Surakarta', 'created_at' => now(), 'updated_at' => now()],
            ['judul' => 'Pakaian anak-anak', 'keterangan' => 'Untuk keluarga kurang mampu', 'lokasi' => 'Jebres, Surakarta', 'created_at' => now(), 'updated_at' => now()],
            ['judul' => 'Sembako', 'keterangan' => 'Untuk warga terdampak bencana', 'lokasi' => 'Laweyan, Surakarta', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}