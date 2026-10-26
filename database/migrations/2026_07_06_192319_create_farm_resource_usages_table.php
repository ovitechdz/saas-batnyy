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
        Schema::create('farm_resource_usages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('type'); // water, energy
            $table->decimal('amount', 10, 2);
            $table->string('unit'); // Liters, kWh
            $table->date('date');
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('source')->nullable(); // Grid, Solar, Borehole
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_resource_usages');
    }
};
