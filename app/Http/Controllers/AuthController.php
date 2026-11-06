<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function formDaftar() { return view('auth.daftar'); }
    public function formMasuk()  { return view('auth.masuk'); }

    public function daftar(Request $r)
    {
        $data = $r->validate([
            'name'           => 'required|string|max:100',
            'email'          => 'required|email|unique:users',
            'password'       => ['required', 'confirmed', new \App\Rules\PasswordKuat],
            'role'           => 'required|in:donatur,penerima',
            'no_hp'          => 'required|string|max:20',
            'alamat'         => 'required|string',
            'latitude'       => 'required|numeric',
            'longitude'      => 'required|numeric',
            'jenis_penerima' => 'required_if:role,penerima|nullable|in:keluarga_kurang_mampu,panti_yayasan,korban_bencana,pelajar',
            'dokumen'        => 'required_if:role,penerima|nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);
        $data['password'] = bcrypt($data['password']);

        if ($data['role'] === 'penerima') {
        $nama = 'dokumen/'.\Illuminate\Support\Str::uuid().'.'.$r->file('dokumen')->extension().'.enc';
        \Illuminate\Support\Facades\Storage::put($nama, \Illuminate\Support\Facades\Crypt::encrypt(file_get_contents($r->file('dokumen')->getRealPath())));
$data['dokumen'] = $nama;
            $data['status_penerima'] = 'menunggu';
        } else {
            unset($data['jenis_penerima'], $data['dokumen']);
        }

        $user = User::create($data);
        event(new Registered($user));
        Auth::login($user);
        return redirect()->route('verification.notice');
    }

public function masuk(Request $r)
{
    if (Auth::attempt($r->only('email', 'password'))) {
        $r->session()->regenerate();
        $r->session()->forget('pin_ok');
        return redirect('/pin/masuk');
    }
    return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
}

    public function keluar(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect('/');
    }
}