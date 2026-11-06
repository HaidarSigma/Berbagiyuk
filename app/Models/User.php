<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
protected $fillable = ['name', 'email', 'password', 'no_hp', 'kota', 'role', 'terverifikasi', 'alamat', 'latitude', 'longitude', 'jenis_penerima', 'dokumen', 'status_penerima'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

protected $hidden = [
    'password',
    'pin',
    'remember_token',
];

protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    'pin_terkunci_sampai' => 'datetime',
    'no_hp' => 'encrypted',
    'alamat' => 'encrypted',
];
}
