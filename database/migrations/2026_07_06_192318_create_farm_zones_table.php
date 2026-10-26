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
        Schema::create('farm_zones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->string('type'); // Grazing, Cropland, Quarantine, etc.
            $table->decimal('area_size', 10, 2)->nullable();
            $table->string('status')->default('active');
            $table->jsonb('coordinates')->nullable(); // For 3D map mapping
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_zones');
    }
};
