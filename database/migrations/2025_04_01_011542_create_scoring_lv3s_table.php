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
        Schema::create('scoring_lv3s', function (Blueprint $table) {
            $table->id();
            // scoring_lv3_question_id, diklat_participant_id, score
            $table->foreignId('scoring_lv3_question_id')->constrained('scoring_lv3_questions')->onDelete('cascade');
            $table->foreignId('diklat_participant_id')->constrained('diklat_participants')->onDelete('cascade');
            $table->decimal('score', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoring_lv3s');
    }
};
