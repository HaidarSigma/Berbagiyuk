<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $r)
    {
        $barang = Barang::with('kategori')
            ->where('status', 'tersedia')->where('verifikasi', 'disetujui')
            ->when($r->q, fn ($q, $kata) => $q->where(fn ($w) => $w
                ->where('judul', 'like', "%{$kata}%")
                ->orWhere('deskripsi', 'like', "%{$kata}%")
                ->orWhere('lokasi', 'like', "%{$kata}%")))
            ->when($r->kategori, fn ($q, $k) => $q->where('kategori_id', $k))
            ->when($r->kondisi, fn ($q, $k) => $q->where('kondisi', $k))
            ->latest()->paginate(12)->withQueryString();

        return view('cari', ['barang' => $barang, 'kategori' => Kategori::all()]);
    }

    public function show(Barang $barang)
    {
        abort_unless($barang->status === 'tersedia' && $barang->verifikasi === 'disetujui', 404);
        return view('detail', ['barang' => $barang->load('kategori', 'user')]);
    }
}