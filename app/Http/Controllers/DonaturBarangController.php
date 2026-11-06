<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DonaturBarangController extends Controller
{
    private function izin(): void
    {
        abort_unless(auth()->user()->role === 'donatur', 403);
    }

    private function bolehUbah(Barang $barang): void
    {
        abort_unless($barang->user_id === auth()->id(), 403);
        abort_unless($barang->status !== 'ditarik' && in_array($barang->verifikasi, ['menunggu', 'ditolak']), 403);
    }

    private function aturan(bool $fotoWajib): array
    {
        return [
            'judul'       => 'required|string|max:150',
            'kategori_id' => 'required|exists:kategoris,id',
            'kondisi'     => 'required|in:Baru,Layak Pakai,Perlu Perbaikan',
            'syarat_penerima'   => 'required|array|min:1',
            'syarat_penerima.*' => 'in:keluarga_kurang_mampu,panti_yayasan,korban_bencana,pelajar',
            'deskripsi'   => 'required|string',
            'lokasi'      => 'required|string|max:100',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'foto'        => ($fotoWajib ? 'required' : 'nullable') . '|image|max:2048',
        ];
    }

    public function create()
    {
        $this->izin();
        return view('donatur.berbagi', ['kategori' => Kategori::all()]);
    }

    public function store(Request $r)
    {
        $this->izin();
        $data = $r->validate($this->aturan(true));
        $data['foto'] = $r->file('foto')->store('barang', 'public');
        $data['user_id'] = $r->user()->id;
        Barang::create($data);

        return redirect('/barang-saya')->with('status', 'Barang terkirim dan menunggu verifikasi admin.');
    }

    public function saya()
    {
        $this->izin();
        return view('donatur.saya', [
            'barang' => Barang::with('kategori')->where('user_id', auth()->id())->latest()->get(),
        ]);
    }

    public function edit(Barang $barang)
    {
        $this->izin();
        $this->bolehUbah($barang);
        return view('donatur.ubah', ['barang' => $barang, 'kategori' => Kategori::all()]);
    }

    public function update(Request $r, Barang $barang)
    {
        $this->izin();
        $this->bolehUbah($barang);
        $data = $r->validate($this->aturan(false));

        if ($r->hasFile('foto')) {
            if ($barang->foto) Storage::disk('public')->delete($barang->foto);
            $data['foto'] = $r->file('foto')->store('barang', 'public');
        } else {
            unset($data['foto']);
        }

        $data['verifikasi'] = 'menunggu';
        $data['alasan_tolak'] = null;
        $barang->update($data);

        return redirect('/barang-saya')->with('status', 'Barang diperbarui dan diajukan ulang untuk diverifikasi.');
    }

    public function tarik(Barang $barang)
    {
        $this->izin();
        abort_unless($barang->user_id === auth()->id(), 403);
        $barang->update(['status' => 'ditarik']);
        return back()->with('status', 'Barang ditarik.');
    }
}