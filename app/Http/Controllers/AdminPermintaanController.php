<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPermintaanController extends Controller
{
    public function index()
    {
        return view('admin.permintaan', [
            'permintaan' => Permintaan::with('barang.kategori', 'penerima')->where('status', 'menunggu')->latest()->get(),
        ]);
    }

    public function setujui(Permintaan $permintaan)
    {
        $barang = $permintaan->barang;
        if ($permintaan->status !== 'menunggu' || $barang->status !== 'tersedia') {
            return back()->with('status', 'Permintaan ini sudah tidak bisa diproses.');
        }

        DB::transaction(function () use ($permintaan, $barang) {
            $permintaan->update(['status' => 'disetujui', 'alasan_tolak' => null]);
            $barang->update(['status' => 'diproses']);
            Permintaan::where('barang_id', $barang->id)
                ->where('status', 'menunggu')->where('id', '!=', $permintaan->id)
                ->update(['status' => 'ditolak', 'alasan_tolak' => 'Barang sudah diberikan kepada penerima lain.']);
        });

        return back()->with('status', "Permintaan {$permintaan->penerima->name} untuk '{$barang->judul}' disetujui.");
    }

    public function tolak(Request $r, Permintaan $permintaan)
    {
        $data = $r->validate(['alasan_tolak' => 'required|string|max:500']);
        abort_unless($permintaan->status === 'menunggu', 403);
        $permintaan->update(['status' => 'ditolak', 'alasan_tolak' => $data['alasan_tolak']]);
        return back()->with('status', 'Permintaan ditolak.');
    }
}