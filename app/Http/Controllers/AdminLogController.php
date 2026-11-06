<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class AdminLogController extends Controller
{
    public function index(Request $r)
    {
        return view('admin.log', [
            'log' => LogAktivitas::with('user')
                ->when($r->aksi, fn ($q, $a) => $q->where('aksi', $a))
                ->latest('created_at')->paginate(25)->withQueryString(),
            'daftarAksi' => LogAktivitas::select('aksi')->distinct()->orderBy('aksi')->pluck('aksi'),
            'gagal' => LogAktivitas::whereIn('aksi', ['login_gagal', 'pin_salah', 'pin_terkunci'])
                ->where('created_at', '>=', now()->subDay())->count(),
        ]);
    }
}