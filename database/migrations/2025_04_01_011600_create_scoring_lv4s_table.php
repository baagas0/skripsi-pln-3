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
        Schema::create('scoring_lv4s', function (Blueprint $table) {
            $table->id();
            // diklat_id, score_positive, impacts (array of string)
            $table->foreignId('diklat_id')->constrained('diklats')->onDelete('cascade');
            $table->decimal('score_positive', 5, 2)->default(0);
            $table->json('impacts')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoring_lv4s');
    }
};
