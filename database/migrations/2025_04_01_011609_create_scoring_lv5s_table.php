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
        Schema::create('scoring_lv5s', function (Blueprint $table) {
            $table->id();
            // diklat_id, total_tangible, cost_of_training, roti
            $table->foreignId('diklat_id')->constrained('diklats')->onDelete('cascade');
            $table->decimal('total_tangible', 50, 2)->default(0);
            $table->decimal('cost_of_training', 50, 2)->default(0);
            $table->decimal('roti', 50, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoring_lv5s');
    }
};
