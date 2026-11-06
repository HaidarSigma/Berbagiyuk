<?php

use Illuminate\Support\Facades\DB;
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
    Schema::table('permintaans', function (Blueprint $table) {
        $table->text('alasan_tolak')->nullable()->after('status');
    });

    DB::statement("ALTER TABLE permintaans MODIFY status ENUM('menunggu','disetujui','ditolak','dibatalkan') NOT NULL DEFAULT 'menunggu'");
}

public function down(): void
{
    Schema::table('permintaans', function (Blueprint $table) {
        $table->dropColumn('alasan_tolak');
    });
}
};
