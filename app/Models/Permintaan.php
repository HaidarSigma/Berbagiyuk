<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    use HasFactory;

    protected $table = 'permintaans';
    protected $fillable = ['barang_id', 'penerima_id', 'pesan', 'status', 'alasan_tolak'];

    public function barang() { return $this->belongsTo(Barang::class); }
    public function penerima() { return $this->belongsTo(User::class, 'penerima_id'); }
}