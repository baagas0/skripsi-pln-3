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
        Schema::table('diklats', function (Blueprint $table) {
            $table->enum('status_monitoring', [
                'Penunjukkan Vendor',
                'Persetujuan SPK/Pengadaan',
                'Pelaksanaan',
                'Kelengkapan Syarat Administrasi',
                'VIP',
                'Selesai'
            ])->default('Penunjukkan Vendor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diklats', function (Blueprint $table) {
            $table->dropColumn('status_monitoring');
        });
    }
};
