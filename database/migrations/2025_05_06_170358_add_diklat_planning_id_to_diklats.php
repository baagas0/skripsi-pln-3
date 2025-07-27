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
            $table->foreignId('diklat_planning_id')->nullable()->constrained('diklat_plannings')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diklats', function (Blueprint $table) {
            $table->dropForeign(['diklat_planning_id']);
            $table->dropColumn('diklat_planning_id');
        });
    }
};
