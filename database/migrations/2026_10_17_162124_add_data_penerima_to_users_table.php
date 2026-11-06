<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->enum('jenis_penerima', ['keluarga_kurang_mampu', 'panti_yayasan', 'korban_bencana', 'pelajar'])->nullable();
        $table->string('dokumen')->nullable();
        $table->enum('status_penerima', ['menunggu', 'disetujui', 'ditolak'])->nullable();
        $table->text('alasan_tolak_penerima')->nullable();
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['jenis_penerima', 'dokumen', 'status_penerima', 'alasan_tolak_penerima']);
    });
}
};
