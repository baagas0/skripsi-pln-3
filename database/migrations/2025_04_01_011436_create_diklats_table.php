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
        Schema::create('diklats', function (Blueprint $table) {
            $table->id();
            // slug, name, letter_number, year, estimate_start_date, estimate_end_date, vendor_id, count_of_participant, unit_id, total_cost, payment_status (belum tertagih, sudah tertagih, belum dibayar, sudah dibayar), locked_at
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('letter_number')->unique();
            $table->year('year');
            $table->date('estimate_start_date');
            $table->date('estimate_end_date');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->integer('count_of_participant');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->decimal('total_cost', 15, 2);
            $table->enum('payment_status', ['belum tertagih', 'sudah tertagih', 'belum dibayar', 'sudah dibayar'])->default('belum tertagih');
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diklats');
    }
};
