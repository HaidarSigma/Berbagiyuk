<?php

use App\Http\Controllers\LupaController;
use App\Http\Controllers\AdminLogController;
use App\Http\Controllers\AdminPermintaanController;
use App\Http\Controllers\PermintaanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\AdminPenerimaController;
use App\Http\Controllers\PenerimaController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\DonaturBarangController;
use App\Http\Controllers\PinController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminBarangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use Illuminate\Support\Facades\Route;


    
    Route::get('/', [BerandaController::class, 'index']);

    Route::get('/cari', [BarangController::class, 'index']);
    Route::get('/barang/{barang}', [BarangController::class, 'show']);

    Route::get('/daftar', [AuthController::class, 'formDaftar']);
    Route::post('/daftar', [AuthController::class, 'daftar'])->middleware('throttle:10,1');
    Route::get('/masuk', [AuthController::class, 'formMasuk'])->name('login');
    Route::post('/masuk', [AuthController::class, 'masuk'])->middleware('throttle:5,1');
    Route::post('/keluar', [AuthController::class, 'keluar']);
    
    Route::get('/lupa', [LupaController::class, 'formEmail']);
    Route::post('/lupa', [LupaController::class, 'kirimKode'])->middleware('throttle:3,10');
    Route::get('/lupa/kode', [LupaController::class, 'formKode']);
    Route::post('/lupa/kode', [LupaController::class, 'cekKode'])->middleware('throttle:10,10');
    Route::get('/lupa/baru', [LupaController::class, 'formBaru']);
    Route::post('/lupa/baru', [LupaController::class, 'simpan']);

    Route::get('/email/verifikasi/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = \App\Models\User::findOrFail($id);
    abort_unless(hash_equals(sha1($user->getEmailForVerification()), (string) $hash), 403);

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));
        \App\Models\LogAktivitas::catat('email_terverifikasi', null, $user->id);
    }

    return redirect('/masuk')->with('status', 'Email berhasil diverifikasi. Silakan masuk.');
})->middleware('signed')->name('verification.verify');

    Route::middleware(['auth', 'verified', 'pin', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminBarangController::class, 'dashboard']);

    Route::get('/barang', [AdminBarangController::class, 'index']);
    Route::post('/barang/{barang}/setujui', [AdminBarangController::class, 'setujui']);
    Route::post('/barang/{barang}/tolak', [AdminBarangController::class, 'tolak']);

    Route::get('/penerima', [AdminPenerimaController::class, 'index']);
    Route::get('/penerima/{user}/dokumen', [AdminPenerimaController::class, 'dokumen']);
    Route::post('/penerima/{user}/setujui', [AdminPenerimaController::class, 'setujui']);
    Route::post('/penerima/{user}/tolak', [AdminPenerimaController::class, 'tolak']);

    Route::get('/permintaan', [AdminPermintaanController::class, 'index']);
    Route::post('/permintaan/{permintaan}/setujui', [AdminPermintaanController::class, 'setujui']);
    Route::post('/permintaan/{permintaan}/tolak', [AdminPermintaanController::class, 'tolak']);

    Route::get('/log', [AdminLogController::class, 'index']);
});
    
Route::middleware(['auth', 'verified', 'pin'])->group(function () {
    Route::get('/berbagi', [DonaturBarangController::class, 'create']);
    Route::post('/berbagi', [DonaturBarangController::class, 'store']);
    Route::get('/barang-saya', [DonaturBarangController::class, 'saya']);
    Route::post('/barang-saya/{barang}/tarik', [DonaturBarangController::class, 'tarik']);
    Route::get('/barang-saya/{barang}/ubah', [DonaturBarangController::class, 'edit']);
    Route::post('/barang-saya/{barang}/ubah', [DonaturBarangController::class, 'update']);
    Route::get('/status-penerima', [PenerimaController::class, 'status']);

    Route::post('/barang/{barang}/ajukan', [PermintaanController::class, 'store']);
    Route::get('/permintaan-saya', [PermintaanController::class, 'saya']);
    Route::post('/permintaan-saya/{permintaan}/batal', [PermintaanController::class, 'batal']);

    Route::get('/pengaturan', [PengaturanController::class, 'index']);
    Route::post('/pengaturan/profil', [PengaturanController::class, 'profil']);
    Route::post('/pengaturan/password', [PengaturanController::class, 'password'])->middleware('throttle:5,1');
    Route::post('/pengaturan/pin', [PengaturanController::class, 'pin'])->middleware('throttle:5,1');
});

    Route::post('/pengaturan/foto', [PengaturanController::class, 'foto'])->middleware('throttle:10,1');
    Route::post('/pengaturan/foto/hapus', [PengaturanController::class, 'hapusFoto']);  
    Route::middleware('auth')->group(function () {
    Route::get('/email/verifikasi', fn () => view('auth.verifikasi'))->name('verification.notice');

    Route::post('/email/kirim-ulang', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Email verifikasi dikirim ulang.');
    })->middleware('throttle:3,1')->name('verification.send');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/pin/buat', [PinController::class, 'formBuat']);
    Route::post('/pin/buat', [PinController::class, 'buat']);
    Route::get('/pin/masuk', [PinController::class, 'formMasuk']);
    Route::post('/pin/masuk', [PinController::class, 'masuk']);
});