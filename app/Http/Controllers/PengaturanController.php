<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Rules\PasswordKuat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('pengaturan', ['u' => auth()->user()]);
    }

    public function profil(Request $r)
    {
        $u      = $r->user();
        $lokasi = in_array($u->role, ['donatur', 'penerima']);
        $wajib  = $lokasi ? 'required' : 'nullable';

        $data = $r->validateWithBag('profil', [
            'name'      => 'required|string|max:100',
            'no_hp'     => 'required|string|max:20',
            'alamat'    => $wajib.'|string',
            'latitude'  => $wajib.'|numeric',
            'longitude' => $wajib.'|numeric',
        ]);

        if (! $lokasi) {
            unset($data['alamat'], $data['latitude'], $data['longitude']);
        }

        $u->update($data);
        LogAktivitas::catat('profil_diubah');

        return back()->with('status', 'Profil berhasil diperbarui.')->with('bagian', 'profil');
    }

    public function password(Request $r)
    {
        $r->validateWithBag('password', [
            'password_lama' => 'required',
            'password'      => ['required', 'confirmed', 'different:password_lama', new PasswordKuat],
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.different' => 'Password baru harus berbeda dari password lama.',
        ]);

        $u = $r->user();

        if (! Hash::check($r->password_lama, $u->password)) {
            LogAktivitas::catat('password_gagal_diganti');
            return back()->withErrors(['password_lama' => 'Password lama salah.'], 'password');
        }

        $u->forceFill(['password' => bcrypt($r->password)])->save();
        LogAktivitas::catat('password_diganti');

        try {
            Mail::raw('Password akun BerbagiYuk kamu baru saja diubah. Jika bukan kamu yang melakukannya, segera hubungi admin.',
                fn ($m) => $m->to($u->email)->subject('Keamanan akun BerbagiYuk'));
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('status', 'Password berhasil diganti.')->with('bagian', 'password');
    }

    public function pin(Request $r)
    {
        $r->validateWithBag('pin', [
            'pin_lama' => 'required|digits:6',
            'pin'      => 'required|digits:6|confirmed|different:pin_lama',
        ], [
            'pin.confirmed' => 'Konfirmasi PIN tidak cocok.',
            'pin.different' => 'PIN baru harus berbeda dari PIN lama.',
        ]);

        $u = $r->user();

        if (! Hash::check($r->pin_lama, $u->pin)) {
            LogAktivitas::catat('pin_salah', 'Saat mengganti PIN');
            return back()->withErrors(['pin_lama' => 'PIN lama salah.'], 'pin');
        }

        $u->forceFill(['pin' => Hash::make($r->pin), 'pin_percobaan' => 0, 'pin_terkunci_sampai' => null])->save();
        LogAktivitas::catat('pin_diganti');

        return back()->with('status', 'PIN berhasil diganti.')->with('bagian', 'pin');
    }
    public function foto(Request $r)
{
    $r->validateWithBag('foto', [
        'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
    ], [
        'foto.required' => 'Pilih foto terlebih dahulu.',
        'foto.image'    => 'Berkas harus berupa gambar.',
        'foto.mimes'    => 'Format foto harus JPG, PNG, atau WEBP.',
        'foto.max'      => 'Ukuran foto maksimal 2 MB.',
    ]);

    $u = $r->user();
    if ($u->foto_profil) {
        Storage::disk('public')->delete($u->foto_profil);
    }

    $u->forceFill(['foto_profil' => $r->file('foto')->store('profil', 'public')])->save();
    LogAktivitas::catat('foto_profil_diganti');

    return back()->with('status', 'Foto profil diperbarui.')->with('bagian', 'foto');
}

public function hapusFoto(Request $r)
{
    $u = $r->user();
    if ($u->foto_profil) {
        Storage::disk('public')->delete($u->foto_profil);
    }

    $u->forceFill(['foto_profil' => null])->save();
    LogAktivitas::catat('foto_profil_dihapus');

    return back()->with('status', 'Foto profil dihapus.')->with('bagian', 'foto');
}
}