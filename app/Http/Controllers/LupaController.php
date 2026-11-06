<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\ResetKode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LupaController extends Controller
{
    public function formEmail(Request $r)
    {
        return view('auth.lupa-email', ['tujuan' => $r->query('tujuan') === 'pin' ? 'pin' : 'password']);
    }

    public function kirimKode(Request $r)
{
    $data = $r->validate(['email' => 'required|email', 'tujuan' => 'required|in:password,pin']);

    $user  = User::where('email', $data['email'])->first();
    $boleh = $user && in_array($user->role, ['donatur', 'penerima']);

   if ($user && ! $boleh) {
    LogAktivitas::catat('reset_staf_ditolak', $user->role.' | '.$data['email'], $user->id);
    return back()->withErrors(['email' => 'Akun admin dan petugas tidak dapat direset di sini. Hubungi atasan atau pengelola sistem.'])->onlyInput('email');
}

    if ($boleh) {
        $kode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        ResetKode::where('email', $user->email)->where('tujuan', $data['tujuan'])->whereNull('dipakai_at')->delete();
        ResetKode::create([
            'email'          => $user->email,
            'tujuan'         => $data['tujuan'],
            'kode'           => Hash::make($kode),
            'kedaluwarsa_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::raw(
                "Kode untuk mengatur ulang {$data['tujuan']} akun BerbagiYuk kamu: {$kode}\n\nBerlaku 10 menit dan hanya bisa dipakai sekali. Jangan bagikan kode ini kepada siapa pun. Jika bukan kamu yang meminta, abaikan email ini.",
                fn ($m) => $m->to($user->email)->subject('Kode reset '.$data['tujuan'].' BerbagiYuk')
            );
        } catch (\Throwable $e) {
            report($e);
        }
    }

    LogAktivitas::catat('reset_diminta', $data['tujuan'].' | '.$data['email'], $user?->id);

    $r->session()->put(['reset_email' => $data['email'], 'reset_tujuan' => $data['tujuan']]);
    $r->session()->forget('reset_ok');

    return redirect('/lupa/kode')->with('status', 'Jika email terdaftar, kode 6 angka sudah dikirim. Berlaku 10 menit.');
}

    public function formKode(Request $r)
    {
        abort_unless($r->session()->has('reset_email'), 404);
        return view('auth.lupa-kode');
    }

    public function cekKode(Request $r)
    {
        $email  = $r->session()->get('reset_email');
        $tujuan = $r->session()->get('reset_tujuan');
        abort_unless($email && $tujuan, 404);
        $r->validate(['kode' => 'required|digits:6']);

        $rec = ResetKode::where('email', $email)->where('tujuan', $tujuan)->whereNull('dipakai_at')->latest('id')->first();
        $pesan = 'Kode salah atau sudah kedaluwarsa. Minta kode baru.';

        if (! $rec || now()->gt($rec->kedaluwarsa_at) || $rec->percobaan >= 5) {
            LogAktivitas::catat('reset_gagal', 'Kode tidak valid atau kedaluwarsa | '.$email);
            return back()->withErrors(['kode' => $pesan]);
        }

        if (! Hash::check($r->kode, $rec->kode)) {
            $rec->increment('percobaan');
            LogAktivitas::catat('reset_gagal', 'Kode salah | '.$email);
            return back()->withErrors(['kode' => $pesan]);
        }

        $r->session()->put('reset_ok', $rec->id);
        return redirect('/lupa/baru');
    }

    public function formBaru(Request $r)
    {
        abort_unless($r->session()->has('reset_ok'), 403);
        return view('auth.lupa-baru', ['tujuan' => $r->session()->get('reset_tujuan')]);
    }

    public function simpan(Request $r)
    {
        $id     = $r->session()->get('reset_ok');
        $email  = $r->session()->get('reset_email');
        $tujuan = $r->session()->get('reset_tujuan');
        abort_unless($id && $email && $tujuan, 403);

        $rec = ResetKode::where('id', $id)->where('email', $email)->whereNull('dipakai_at')->first();
        abort_unless($rec && now()->lte($rec->kedaluwarsa_at), 403);

        $user = User::where('email', $email)->firstOrFail();
        abort_unless(in_array($user->role, ['donatur', 'penerima']), 403);

        if ($tujuan === 'password') {
            $r->validate(
    ['password' => ['required', 'confirmed', new \App\Rules\PasswordKuat]],
    ['password.confirmed' => 'Konfirmasi password tidak cocok.']
);
            $user->forceFill(['password' => bcrypt($r->password)])->save();
        } else {
            $r->validate(['pin' => 'required|digits:6|confirmed']);
            $user->forceFill(['pin' => Hash::make($r->pin), 'pin_percobaan' => 0, 'pin_terkunci_sampai' => null])->save();
        }

        $rec->update(['dipakai_at' => now()]);
        LogAktivitas::catat('reset_berhasil', $tujuan, $user->id);

        try {
            Mail::raw(
                ucfirst($tujuan)." akun BerbagiYuk kamu baru saja diubah. Jika bukan kamu yang melakukannya, segera hubungi admin.",
                fn ($m) => $m->to($user->email)->subject('Keamanan akun BerbagiYuk')
            );
        } catch (\Throwable $e) {
            report($e);
        }

        $r->session()->forget(['reset_ok', 'reset_email', 'reset_tujuan']);

        return redirect('/masuk')->with('status', ucfirst($tujuan).' berhasil diubah. Silakan masuk.');
    }
}