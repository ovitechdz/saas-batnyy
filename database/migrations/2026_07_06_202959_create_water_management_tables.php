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
        Schema::create('water_sources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->string('type')->default('Well'); // Well, Tank, Municipal, Rainwater, River
            $table->decimal('capacity', 10, 2)->nullable(); // In cubic meters (m³)
            $table->string('status')->default('Active'); // Active, Maintenance, Depleted, Offline
            $table->timestamps();
        });

        Schema::create('water_readings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('water_source_id')->constrained('water_sources')->cascadeOnDelete();
            $table->dateTime('reading_date');
            $table->decimal('consumption', 10, 2)->default(0); // Volume consumed in m³
            $table->decimal('ph_level', 4, 2)->nullable();
            $table->decimal('salinity', 6, 2)->nullable(); // ppm or similar
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_readings');
        Schema::dropIfExists('water_sources');
    }
};
