<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('barangs', function (Blueprint $table) {
        $table->decimal('latitude', 10, 7)->nullable()->after('lokasi');
        $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        $table->text('alasan_tolak')->nullable()->after('verifikasi');
    });

    DB::statement("ALTER TABLE barangs MODIFY status ENUM('tersedia','diproses','selesai','ditarik') NOT NULL DEFAULT 'tersedia'");
}

public function down(): void
{
    Schema::table('barangs', function (Blueprint $table) {
        $table->dropColumn(['latitude', 'longitude', 'alasan_tolak']);
    });
}
};