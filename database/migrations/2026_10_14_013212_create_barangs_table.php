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
Schema::create('barangs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('kategori_id')->constrained('kategoris');
    $table->string('judul', 150);
    $table->text('deskripsi')->nullable();
    $table->enum('kondisi', ['Baru', 'Layak Pakai', 'Perlu Perbaikan'])->default('Layak Pakai');
    $table->string('lokasi', 100)->nullable();
    $table->string('foto')->nullable();
    $table->enum('status', ['tersedia', 'diproses', 'selesai'])->default('tersedia');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
