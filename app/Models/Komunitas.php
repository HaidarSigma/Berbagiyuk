<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komunitas extends Model
{
    use HasFactory;

    protected $table = 'komunitas';
    protected $fillable = ['nama', 'deskripsi', 'kota'];

    public function anggota()
    {
        return $this->belongsToMany(User::class, 'komunitas_anggota', 'komunitas_id', 'user_id');
    }
}