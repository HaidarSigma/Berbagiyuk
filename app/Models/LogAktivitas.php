<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    public $timestamps = false;
    protected $fillable = ['user_id', 'aksi', 'keterangan', 'ip', 'created_at'];
    protected $casts = ['created_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }

    public static function catat(string $aksi, ?string $keterangan = null, ?int $userId = null): void
    {
        static::create([
            'user_id'    => $userId ?? auth()->id(),
            'aksi'       => $aksi,
            'keterangan' => $keterangan ? mb_substr($keterangan, 0, 250) : null,
            'ip'         => request()->ip(),
            'created_at' => now(),
        ]);
    }
}