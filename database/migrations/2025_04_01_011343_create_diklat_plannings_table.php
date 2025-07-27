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
        Schema::create('diklat_plannings', function (Blueprint $table) {
            $table->id();
            // name, year, estimate_start_date, estimate_end_date, vendor_id, count_of_participant, unit_id, total_cost, approve_by_htd
            $table->string('name');
            $table->year('year');
            $table->date('estimate_start_date');
            $table->date('estimate_end_date');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->integer('count_of_participant');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->decimal('total_cost', 15, 2);
            $table->timestamp('locked_at')->nullable();
            $table->boolean('approve_by_htd')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diklat_plannings');
    }
};
