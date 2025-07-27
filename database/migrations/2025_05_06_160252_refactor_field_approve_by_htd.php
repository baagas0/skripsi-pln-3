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
            $table->dropColumn('approve_by_htd');

            $table->integer('approve_by_htd')->default(0)->after('locked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diklat_plannings', function (Blueprint $table) {
            $table->dropColumn('approve_by_htd');

            $table->boolean('approve_by_htd')->default(false)->after('locked_at');
        });
    }
};
