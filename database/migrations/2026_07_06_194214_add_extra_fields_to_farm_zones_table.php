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
        Schema::table('farm_zones', function (Blueprint $table) {
            $table->string('soil_type')->nullable()->after('area_size');
            $table->uuid('manager_id')->nullable()->after('soil_type');
            
            // Note: status is already present
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farm_zones', function (Blueprint $table) {
            $table->dropColumn(['soil_type', 'manager_id']);
        });
    }
};
