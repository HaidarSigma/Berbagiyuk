<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// KebutuhanMendesak.php
class KebutuhanMendesak extends Model
{
    use HasFactory;

    protected $table = 'kebutuhan_mendesaks';
    protected $fillable = ['judul', 'keterangan', 'lokasi', 'foto', 'status'];
}