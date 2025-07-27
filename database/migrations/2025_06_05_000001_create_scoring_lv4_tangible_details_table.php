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
        Schema::create('scoring_lv4_tangible_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scoring_lv4_tangible_id')->constrained('scoring_lv4_tangibles')->onDelete('cascade');
            $table->string('component_name'); // Component name (Level 2)
            $table->string('sub_component_name')->nullable(); // Sub-component name (Level 3)
            $table->decimal('price', 20, 2)->default(0); // Price of the sub-component
            $table->string('operator')->default('*'); // Mathematical operator (* for multiply, + for add, etc.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoring_lv4_tangible_details');
    }
};