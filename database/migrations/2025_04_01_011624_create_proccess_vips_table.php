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
        Schema::create('proccess_vips', function (Blueprint $table) {
            $table->id();
            // submission_id, diklat_id, status (belum tertagih, sudah tertagih, belum dibayar, sudah dibayar)
            $table->foreignId('diklat_id')->constrained('diklats')->onDelete('cascade');
            $table->string('submission_id')->unique();
            $table->enum('status', ['belum tertagih', 'sudah tertagih', 'belum dibayar', 'sudah dibayar'])->default('belum tertagih');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proccess_vips');
    }
};
