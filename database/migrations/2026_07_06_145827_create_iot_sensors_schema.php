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
        Schema::create('iot_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('mac_address')->unique();
            $table->string('name');
            $table->string('type'); // SensorNode, SmartGate, Gateway
            $table->string('status')->default('offline');
            $table->decimal('battery_level', 5, 2)->nullable();
            $table->decimal('signal_strength', 5, 2)->nullable(); // RSSI
            $table->string('firmware_version')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
        });

        Schema::create('iot_sensors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('device_id')->constrained('iot_devices')->cascadeOnDelete();
            $table->string('sensor_type'); // Temperature, Humidity, Ammonia, WaterLevel
            $table->string('unit_of_measurement')->nullable(); // C, %, ppm, L
            $table->decimal('safe_min', 8, 2)->nullable();
            $table->decimal('safe_max', 8, 2)->nullable();
            $table->timestamps();
            
            $table->unique(['device_id', 'sensor_type']);
        });

        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sensor_id')->constrained('iot_sensors')->cascadeOnDelete();
            $table->decimal('value', 12, 4);
            $table->timestamp('recorded_at')->useCurrent();
            
            $table->index(['sensor_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_readings');
        Schema::dropIfExists('iot_sensors');
        Schema::dropIfExists('iot_devices');
    }
};
