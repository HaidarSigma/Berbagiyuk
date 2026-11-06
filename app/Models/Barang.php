<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    const JENIS = [
        'keluarga_kurang_mampu' => 'Keluarga kurang mampu',
        'panti_yayasan'         => 'Panti asuhan / yayasan',
        'korban_bencana'        => 'Korban bencana',
        'pelajar'               => 'Pelajar',
    ];

    protected $fillable = ['user_id', 'kategori_id', 'judul', 'deskripsi', 'kondisi', 'syarat_penerima', 'lokasi', 'latitude', 'longitude', 'foto', 'status', 'verifikasi', 'alasan_tolak'];

    protected $casts = ['syarat_penerima' => 'array'];

    public function user() { return $this->belongsTo(User::class); }
    public function kategori() { return $this->belongsTo(Kategori::class); }
    public function permintaan() { return $this->hasMany(Permintaan::class); }

    public function syarat(): array
    {
        return $this->syarat_penerima ?: array_keys(self::JENIS);
    }

    // [boleh mengajukan?, pesan alasan jika tidak]
    public function statusPengajuan(?User $u): array
    {
        if (! $u) return [false, 'Masuk dengan akun penerima untuk mengajukan permintaan.'];
        if ($u->role !== 'penerima') return [false, 'Hanya akun penerima yang dapat mengajukan permintaan.'];
        if ($u->status_penerima !== 'disetujui') return [false, 'Akunmu belum terverifikasi sebagai penerima.'];
        if (! in_array($u->jenis_penerima, $this->syarat())) return [false, 'Barang ini diperuntukkan bagi penerima dengan jenis lain.'];
        return [true, null];
    }
}