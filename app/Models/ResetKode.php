<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetKode extends Model
{
    protected $table = 'reset_kode';
    public $timestamps = false;
    protected $fillable = ['email', 'tujuan', 'kode', 'percobaan', 'kedaluwarsa_at', 'dipakai_at'];
    protected $casts = ['kedaluwarsa_at' => 'datetime', 'dipakai_at' => 'datetime'];
}