<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Permintaan;
use Illuminate\Http\Request;

class PermintaanController extends Controller
{
    const BATAS_AKTIF = 3;

    public function store(Request $r, Barang $barang)
    {
        abort_unless($r->user()->role === 'penerima', 403);
        abort_unless($barang->status === 'tersedia' && $barang->verifikasi === 'disetujui', 404);

        $data = $r->validate(['pesan' => 'required|string|min:10|max:500']);

        [$boleh, $alasan] = $barang->statusPengajuan($r->user());
        if (! $boleh) return back()->withErrors(['pesan' => $alasan]);

        $aktif = Permintaan::where('penerima_id', $r->user()->id)->whereIn('status', ['menunggu', 'disetujui']);
        if ((clone $aktif)->count() >= self::BATAS_AKTIF) {
            return back()->withErrors(['pesan' => 'Kamu sudah memiliki ' . self::BATAS_AKTIF . ' permintaan aktif. Tunggu salah satunya selesai atau batalkan.']);
        }
        if ((clone $aktif)->where('barang_id', $barang->id)->exists()) {
            return back()->withErrors(['pesan' => 'Kamu sudah mengajukan permintaan untuk barang ini.']);
        }

        Permintaan::create(['barang_id' => $barang->id, 'penerima_id' => $r->user()->id, 'pesan' => $data['pesan']]);

        return redirect('/permintaan-saya')->with('status', 'Permintaan terkirim dan menunggu persetujuan admin.');
    }

    public function saya()
    {
        abort_unless(auth()->user()->role === 'penerima', 403);
        return view('penerima.permintaan', [
            'permintaan' => Permintaan::with('barang.kategori')->where('penerima_id', auth()->id())->latest()->get(),
        ]);
    }

    public function batal(Permintaan $permintaan)
    {
        abort_unless($permintaan->penerima_id === auth()->id() && $permintaan->status === 'menunggu', 403);
        $permintaan->update(['status' => 'dibatalkan']);
        return back()->with('status', 'Permintaan dibatalkan.');
    }
}