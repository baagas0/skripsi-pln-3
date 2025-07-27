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
        Schema::table('scoring_lv2s', function (Blueprint $table) {
            $table->decimal('average_score', 5, 2)->nullable()->after('posttest_score');
            $table->decimal('rank', 5, 2)->nullable()->after('average_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scoring_lv2s', function (Blueprint $table) {
            //
        });
    }
};
