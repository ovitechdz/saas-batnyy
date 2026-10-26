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
        Schema::create('attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable();
            $table->date('date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('breeding_cycles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->nullable();
            $table->date('start_date');
            $table->date('expected_birth_date')->nullable();
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('diagnoses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->nullable();
            $table->string('condition');
            $table->text('notes')->nullable();
            $table->date('diagnosis_date');
            $table->timestamps();
        });

        Schema::create('feed_consumptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->nullable();
            $table->foreignUuid('feed_type_id')->nullable();
            $table->decimal('quantity_kg', 8, 2);
            $table->date('date');
            $table->timestamps();
        });

        Schema::create('hays', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('batch_number');
            $table->decimal('quantity_bales', 8, 2);
            $table->decimal('weight_per_bale_kg', 8, 2);
            $table->date('harvest_date');
            $table->timestamps();
        });

        Schema::create('health_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->nullable();
            $table->string('type'); // vaccination, treatment, checkup
            $table->text('description')->nullable();
            $table->date('record_date');
            $table->timestamps();
        });

        Schema::create('market_listings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('medicine_stocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('medicine_name');
            $table->integer('quantity');
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });

        Schema::create('medicine_usages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->nullable();
            $table->foreignUuid('medicine_id')->nullable();
            $table->decimal('dose_ml', 8, 2);
            $table->date('usage_date');
            $table->timestamps();
        });

        Schema::create('payrolls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('sheep', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tag_number')->unique();
            $table->string('breed')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender');
            $table->timestamps();
        });

        Schema::create('solar_readings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('panel_id')->nullable();
            $table->decimal('energy_produced_kwh', 8, 2);
            $table->dateTime('reading_time');
            $table->timestamps();
        });

        Schema::create('vaccinations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->nullable();
            $table->string('vaccine_name');
            $table->date('vaccination_date');
            $table->date('next_due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vaccinations');
        Schema::dropIfExists('solar_readings');
        Schema::dropIfExists('sheep');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('medicine_usages');
        Schema::dropIfExists('medicine_stocks');
        Schema::dropIfExists('market_listings');
        Schema::dropIfExists('health_records');
        Schema::dropIfExists('hays');
        Schema::dropIfExists('feed_consumptions');
        Schema::dropIfExists('diagnoses');
        Schema::dropIfExists('breeding_cycles');
        Schema::dropIfExists('attendances');
    }
};
