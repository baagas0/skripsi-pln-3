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
            $table->json('tangible_benefit_categories')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diklats', function (Blueprint $table) {
            $table->dropColumn('tangible_benefit_categories');
        });
    }
};
