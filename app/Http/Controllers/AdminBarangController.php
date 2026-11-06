<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;

class AdminBarangController extends Controller
{
    private function antrean()
    {
        return Barang::with('kategori', 'user')
            ->where('verifikasi', 'menunggu')
            ->where('status', '!=', 'ditarik');
    }

    public function dashboard()
    {
        return view('admin.dashboard', [
            'menunggu' => $this->antrean()->count(),
            'tersedia' => Barang::where('verifikasi', 'disetujui')->where('status', 'tersedia')->count(),
            'ditolak'  => Barang::where('verifikasi', 'ditolak')->count(),
            'donatur'  => User::where('role', 'donatur')->count(),
            'penerima' => User::where('role', 'penerima')->count(),
            'terbaru'  => $this->antrean()->latest()->take(5)->get(),
        ]);
    }

    public function index()
    {
        return view('admin.barang', ['barang' => $this->antrean()->latest()->get()]);
    }

    public function setujui(Barang $barang)
    {
        $barang->update(['verifikasi' => 'disetujui', 'alasan_tolak' => null]);
        return back()->with('status', "Barang '{$barang->judul}' disetujui.");
    }

    public function tolak(Request $r, Barang $barang)
    {
        $data = $r->validate(['alasan_tolak' => 'required|string|max:500']);
        $barang->update(['verifikasi' => 'ditolak', 'alasan_tolak' => $data['alasan_tolak']]);
        return back()->with('status', "Barang '{$barang->judul}' ditolak.");
    }
}