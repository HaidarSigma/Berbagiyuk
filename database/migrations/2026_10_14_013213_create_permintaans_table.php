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
Schema::create('permintaans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
    $table->foreignId('penerima_id')->constrained('users')->cascadeOnDelete();
    $table->text('pesan')->nullable();
    $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaans');
    }
};
