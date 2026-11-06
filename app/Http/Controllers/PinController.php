<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PinController extends Controller
{
    public function formBuat()
    {
        return auth()->user()->pin ? redirect('/') : view('auth.pin-buat');
    }

    public function buat(Request $r)
    {
        $r->validate(['pin' => 'required|digits:6|confirmed']);
        $r->user()->forceFill(['pin' => Hash::make($r->pin)])->save();
        $r->session()->put('pin_ok', true);
        return redirect('/');
    }

    public function formMasuk()
    {
        return auth()->user()->pin ? view('auth.pin-masuk') : redirect('/pin/buat');
    }

    public function masuk(Request $r)
    {
        $r->validate(['pin' => 'required|digits:6']);
        $u = $r->user();

        if ($u->pin_terkunci_sampai && now()->lt($u->pin_terkunci_sampai)) {
            return back()->withErrors(['pin' => 'Terlalu banyak percobaan. Coba lagi setelah '.$u->pin_terkunci_sampai->format('H:i').'.']);
        }

        if (Hash::check($r->pin, $u->pin)) {
            $u->pin_percobaan = 0;
            $u->pin_terkunci_sampai = null;
            $u->save();
            $r->session()->put('pin_ok', true);
            return redirect('/');
        }

        $u->pin_percobaan++;
        if ($u->pin_percobaan >= 5) {
            $u->pin_percobaan = 0;
            $u->pin_terkunci_sampai = now()->addMinutes(15);
        }
        $u->save();
        return back()->withErrors(['pin' => 'PIN salah.']);
    }
}