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
        Schema::create('diklat_planning_area', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diklat_planning_id')->constrained()->onDelete('cascade');
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->unique(['diklat_planning_id', 'area_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diklat_planning_area');
    }
};
