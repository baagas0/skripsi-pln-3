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
        Schema::table('diklat_plannings', function (Blueprint $table) {
            // diklat_type
            $table->string('diklat_type')->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diklat_plannings', function (Blueprint $table) {
            $table->dropColumn('diklat_type');
        });
    }
};
