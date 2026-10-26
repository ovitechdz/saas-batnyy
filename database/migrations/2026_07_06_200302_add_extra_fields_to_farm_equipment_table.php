<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farm_equipment', function (Blueprint $table) {
            $table->foreignUuid('zone_id')->nullable()->after('farm_id')->constrained('farm_zones')->nullOnDelete();
            $table->string('serial_number')->nullable()->after('name');
            $table->string('power_type')->default('Diesel')->after('type'); // Diesel, Electric, Solar, Manual
        });
    }

    public function down(): void
    {
        Schema::table('farm_equipment', function (Blueprint $table) {
            $table->dropForeign(['zone_id']);
            $table->dropColumn(['zone_id', 'serial_number', 'power_type']);
        });
    }
};
