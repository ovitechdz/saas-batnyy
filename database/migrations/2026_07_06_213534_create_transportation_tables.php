<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name'); // e.g. "Truck A", "Trailer 1"
            $table->string('plate_number')->nullable();
            $table->string('type')->default('truck'); // truck, trailer, van, pickup
            $table->string('capacity_animals')->nullable(); // e.g. "30 sheep"
            $table->string('status')->default('available'); // available, in_transit, maintenance
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->string('reference')->unique(); // e.g. SHIP-20260706-001
            $table->string('origin');
            $table->string('destination');
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->integer('animal_count')->default(0);
            $table->string('animal_type')->nullable(); // Sheep, Cattle, etc.
            $table->decimal('total_weight_kg', 10, 2)->nullable();
            $table->decimal('distance_km', 10, 2)->nullable();
            $table->string('status')->default('scheduled'); // scheduled, in_transit, delivered, cancelled
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('departed_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('vehicles');
    }
};
