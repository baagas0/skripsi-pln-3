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
        Schema::create('scoring_lv2s', function (Blueprint $table) {
            $table->id();
            // diklat_participant_id, pretest_score, posttest_score
            $table->foreignId('diklat_participant_id')->constrained('diklat_participants')->onDelete('cascade');
            $table->decimal('pretest_score', 5, 2)->default(0);
            $table->decimal('posttest_score', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoring_lv2s');
    }
};
