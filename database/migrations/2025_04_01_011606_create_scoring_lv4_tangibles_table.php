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
        Schema::create('scoring_lv4_tangibles', function (Blueprint $table) {
            $table->id();
            // diklat_id, category, cost
            $table->foreignId('diklat_id')->constrained('diklats')->onDelete('cascade');
            $table->string('category');
            $table->decimal('cost', 50, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoring_lv4_tangibles');
    }
};
