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
        Schema::create('quarantine_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->string('reason');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('isolation_area')->nullable();
            $table->string('status')->default('active'); // active, released
            $table->foreignUuid('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('lab_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('medical_record_id')->constrained('medical_records')->cascadeOnDelete();
            $table->string('test_type'); // Blood, PCR, Fecal, Urine
            $table->date('test_date');
            $table->string('result_status'); // Pending, Positive, Negative, Inconclusive
            $table->text('notes')->nullable();
            $table->string('attachment_path')->nullable();
            $table->foreignUuid('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_results');
        Schema::dropIfExists('quarantine_records');
    }
};
