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
    $table->string('no_hp', 20)->nullable();
    $table->string('kota', 50)->nullable();
    $table->enum('role', ['donatur', 'penerima', 'petugas', 'admin'])->default('donatur');
    $table->boolean('terverifikasi')->default(false);
    $table->text('alamat')->nullable();
    $table->decimal('latitude', 10, 7)->nullable();
    $table->decimal('longitude', 10, 7)->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
