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
        Schema::table('scoring_lv4s', function (Blueprint $table) {
            $table->json('employee_ids')->nullable()->after('impacts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scoring_lv4s', function (Blueprint $table) {
            $table->dropColumn('employee_ids');
        });
    }
};
