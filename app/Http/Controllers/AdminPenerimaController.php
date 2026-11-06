<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPenerimaController extends Controller
{
    public function index()
    {
        return view('admin.penerima', [
            'penerima' => User::where('role', 'penerima')->where('status_penerima', 'menunggu')->latest()->get(),
        ]);
    }

    public function dokumen(User $user)
{
    abort_unless($user->role === 'penerima' && $user->dokumen, 404);

    try {
        $isi = \Illuminate\Support\Facades\Crypt::decrypt(Storage::get($user->dokumen));
    } catch (\Throwable $e) {
        abort(404, 'Dokumen tidak dapat dibaca.');
    }

    $ext  = pathinfo(substr($user->dokumen, 0, -4), PATHINFO_EXTENSION);
    $mime = ['pdf' => 'application/pdf', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg'][$ext] ?? 'application/octet-stream';

    \App\Models\LogAktivitas::catat('dokumen_dibuka', 'Penerima: '.$user->name);

    return response($isi, 200, ['Content-Type' => $mime, 'Content-Disposition' => 'inline']);
}
    public function setujui(User $user)
    {
        $user->forceFill(['status_penerima' => 'disetujui', 'alasan_tolak_penerima' => null])->save();
        return back()->with('status', "Penerima '{$user->name}' disetujui.");
    }

    public function tolak(Request $r, User $user)
    {
        $data = $r->validate(['alasan_tolak_penerima' => 'required|string|max:500']);
        $user->forceFill(['status_penerima' => 'ditolak', 'alasan_tolak_penerima' => $data['alasan_tolak_penerima']])->save();
        return back()->with('status', "Penerima '{$user->name}' ditolak.");
    }
}