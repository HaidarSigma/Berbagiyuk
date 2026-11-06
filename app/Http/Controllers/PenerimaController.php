<?php

namespace App\Http\Controllers;

class PenerimaController extends Controller
{
    public function status()
    {
        abort_unless(auth()->user()->role === 'penerima', 403);
        return view('penerima.status');
    }
}