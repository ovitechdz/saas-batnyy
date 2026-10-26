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
        Schema::table('water_sources', function (Blueprint $table) {
            $table->foreignUuid('zone_id')->nullable()->constrained('farm_zones')->nullOnDelete();
            $table->decimal('flow_rate', 10, 2)->nullable(); // m³/h
            $table->decimal('depth', 10, 2)->nullable(); // meters
            $table->string('coordinates')->nullable(); // GPS
            $table->date('installation_date')->nullable();
            $table->date('next_maintenance_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('water_sources', function (Blueprint $table) {
            $table->dropForeign(['zone_id']);
            $table->dropColumn([
                'zone_id',
                'flow_rate',
                'depth',
                'coordinates',
                'installation_date',
                'next_maintenance_date'
            ]);
        });
    }
};
