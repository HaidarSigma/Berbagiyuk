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
Schema::create('kebutuhan_mendesaks', function (Blueprint $table) {
    $table->id();
    $table->string('judul', 150);
    $table->string('keterangan')->nullable();
    $table->string('lokasi', 100)->nullable();
    $table->string('foto')->nullable();
    $table->enum('status', ['mendukung', 'selesai'])->default('mendukung');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebutuhan_mendesaks');
    }
};
