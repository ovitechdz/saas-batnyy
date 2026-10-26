<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Breeding Architecture
        Schema::create('heat_cycles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('intensity')->nullable(); // High, Medium, Low
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('breeding_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('mother_id')->constrained('animals')->cascadeOnDelete();
            $table->foreignUuid('father_id')->nullable()->constrained('animals')->nullOnDelete();
            $table->date('mating_date');
            $table->string('method'); // Natural, AI (Artificial Insemination)
            $table->string('status')->default('pending'); // pending, successful, failed
            $table->foreignUuid('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('pregnancy_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('breeding_record_id')->nullable()->constrained('breeding_records')->nullOnDelete();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->date('confirmation_date')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->date('actual_delivery_date')->nullable();
            $table->integer('number_of_fetuses')->nullable();
            $table->string('status'); // Pregnant, Delivered, Miscarriage
            $table->timestamps();
        });

        Schema::create('genetics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->string('blood_type')->nullable();
            $table->jsonb('genetic_markers')->nullable();
            $table->jsonb('inherited_traits')->nullable();
            $table->timestamps();
        });

        Schema::create('offspring', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pregnancy_record_id')->constrained('pregnancy_records')->cascadeOnDelete();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete(); // The born animal
            $table->string('birth_status'); // Alive, Stillborn
            $table->timestamps();
        });

        Schema::create('pedigree', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->jsonb('ancestry_tree')->nullable(); // Denormalized tree for fast rendering
            $table->decimal('inbreeding_coefficient', 5, 4)->nullable();
            $table->timestamps();
        });

        // Health Architecture
        Schema::create('diseases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('scientific_name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_contagious')->default(false);
            $table->timestamps();
        });

        Schema::create('symptoms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('medicines', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->string('active_ingredient')->nullable();
            $table->string('manufacturer')->nullable();
            $table->integer('withdrawal_period_days')->default(0); // Meat/Milk withdrawal
            $table->timestamps();
        });

        Schema::create('medicine_stock', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->decimal('quantity', 10, 2);
            $table->string('unit'); // ml, mg, bottle
            $table->date('expiration_date');
            $table->string('batch_number')->nullable();
            $table->timestamps();
        });

        Schema::create('veterinary_visits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('veterinarian_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('visit_date');
            $table->text('reason');
            $table->text('general_notes')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('medical_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->foreignUuid('visit_id')->nullable()->constrained('veterinary_visits')->nullOnDelete();
            $table->date('examination_date');
            $table->decimal('temperature', 5, 2)->nullable();
            $table->integer('heart_rate')->nullable();
            $table->integer('respiratory_rate')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('diagnosis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('medical_record_id')->constrained('medical_records')->cascadeOnDelete();
            $table->foreignUuid('disease_id')->nullable()->constrained('diseases')->nullOnDelete();
            $table->string('severity')->nullable(); // Mild, Moderate, Severe
            $table->timestamps();
        });

        Schema::create('treatments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('medical_record_id')->constrained('medical_records')->cascadeOnDelete();
            $table->string('treatment_type');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status'); // Ongoing, Completed, Cancelled
            $table->timestamps();
        });

        Schema::create('medicine_usage', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('treatment_id')->constrained('treatments')->cascadeOnDelete();
            $table->foreignUuid('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->foreignUuid('stock_id')->nullable()->constrained('medicine_stock')->nullOnDelete();
            $table->decimal('dosage', 8, 2);
            $table->string('unit');
            $table->timestamp('administered_at');
            $table->foreignUuid('administered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('health_alerts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->string('alert_type'); // Temperature Spike, Missed Medication
            $table->text('message');
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
        });

        // Vaccination Architecture
        Schema::create('vaccines', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->string('target_disease');
            $table->integer('booster_days')->nullable();
            $table->timestamps();
        });

        Schema::create('vaccination_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('vaccination_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->foreignUuid('vaccine_id')->constrained('vaccines')->cascadeOnDelete();
            $table->date('administered_date');
            $table->date('next_due_date')->nullable();
            $table->string('batch_number')->nullable();
            $table->foreignUuid('administered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('vaccination_reminders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->foreignUuid('vaccine_id')->constrained('vaccines')->cascadeOnDelete();
            $table->date('reminder_date');
            $table->boolean('is_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vaccination_reminders');
        Schema::dropIfExists('vaccination_records');
        Schema::dropIfExists('vaccination_plans');
        Schema::dropIfExists('vaccines');
        Schema::dropIfExists('health_alerts');
        Schema::dropIfExists('medicine_usage');
        Schema::dropIfExists('treatments');
        Schema::dropIfExists('diagnosis');
        Schema::dropIfExists('medical_records');
        Schema::dropIfExists('veterinary_visits');
        Schema::dropIfExists('medicine_stock');
        Schema::dropIfExists('medicines');
        Schema::dropIfExists('symptoms');
        Schema::dropIfExists('diseases');
        Schema::dropIfExists('pedigree');
        Schema::dropIfExists('offspring');
        Schema::dropIfExists('genetics');
        Schema::dropIfExists('pregnancy_records');
        Schema::dropIfExists('breeding_records');
        Schema::dropIfExists('heat_cycles');
    }
};
