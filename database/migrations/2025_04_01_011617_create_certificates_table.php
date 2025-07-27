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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            // diklat_id, employee_id, vendor_id, fungsi, certificate_number, certificate_date, certificate_expire, certificate_path (nullable)
            $table->foreignId('diklat_id')->constrained('diklats')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->string('fungsi')->nullable();
            $table->string('certificate_number')->nullable();
            $table->date('certificate_date')->nullable();
            $table->date('certificate_expire')->nullable();
            $table->string('certificate_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
