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
        Schema::table('diklats', function (Blueprint $table) {
            // Drop foreign key constraints first
            if (Schema::hasColumn('diklats', 'area_id')) {
                // Check if a foreign key exists
                $foreignKeys = \DB::select('SHOW CREATE TABLE diklats');
                if(isset($foreignKeys[0])) {
                    $createTableSql = $foreignKeys[0]->{'Create Table'};
                    if(strpos($createTableSql, 'CONSTRAINT `diklats_area_id_foreign`') !== false) {
                        $table->dropForeign(['area_id']);
                    }
                }
                
                $table->dropColumn('area_id');
            }
            
            // Drop the areaId column if it exists
            if (Schema::hasColumn('diklats', 'areaId')) {
                $table->dropColumn('areaId');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diklats', function (Blueprint $table) {
            $table->unsignedBigInteger('area_id')->nullable();
            $table->foreign('area_id')->references('id')->on('areas');
        });
    }
};
