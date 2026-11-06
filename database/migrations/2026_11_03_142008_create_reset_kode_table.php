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
    Schema::create('reset_kode', function (Blueprint $table) {
        $table->id();
        $table->string('email')->index();
        $table->enum('tujuan', ['password', 'pin']);
        $table->string('kode');
        $table->unsignedTinyInteger('percobaan')->default(0);
        $table->timestamp('kedaluwarsa_at');
        $table->timestamp('dipakai_at')->nullable();
        $table->timestamp('created_at')->useCurrent();
    });
}

public function down(): void
{
    Schema::dropIfExists('reset_kode');
}
};
