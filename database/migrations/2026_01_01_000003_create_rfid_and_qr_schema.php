<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // RFID Architecture
        Schema::create('rfid_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->string('mac_address')->unique();
            $table->string('ip_address')->nullable();
            $table->string('status')->default('offline'); // online, offline, error
            $table->timestamp('last_ping_at')->nullable();
            $table->timestamps();
        });

        Schema::create('rfid_readers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('device_id')->constrained('rfid_devices')->cascadeOnDelete();
            $table->string('reader_model');
            $table->string('firmware_version')->nullable();
            $table->timestamps();
        });

        Schema::create('rfid_antennas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('device_id')->constrained('rfid_devices')->cascadeOnDelete();
            $table->integer('port_number');
            $table->string('location_description')->nullable();
            $table->foreignUuid('zone_id')->nullable()->constrained('zones')->nullOnDelete();
            $table->timestamps();
            $table->unique(['device_id', 'port_number']);
        });

        Schema::create('rfid_tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('animal_id')->nullable()->constrained('animals')->nullOnDelete();
            $table->string('epc_code'); // Electronic Product Code
            $table->string('tag_type')->nullable(); // Ear tag, bolus, etc.
            $table->date('assigned_date')->nullable();
            $table->string('status')->default('active'); // active, lost, replaced
            $table->timestamps();
            
            // Allow same EPC code across different farms, but unique within one farm
            $table->unique(['farm_id', 'epc_code']);
        });

        Schema::create('rfid_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('rfid_tag_id')->constrained('rfid_tags')->cascadeOnDelete();
            $table->foreignUuid('antenna_id')->constrained('rfid_antennas')->cascadeOnDelete();
            $table->decimal('rssi', 5, 2)->nullable(); // Signal strength
            $table->timestamp('read_at')->useCurrent();
            
            // Note: This table will grow massively. In a production deployment script, 
            // we should convert this to a Partitioned Table by `read_at`.
            $table->index(['rfid_tag_id', 'read_at']);
        });

        Schema::create('rfid_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('rfid_tag_id')->constrained('rfid_tags')->cascadeOnDelete();
            $table->string('event_type'); // e.g., 'entered_zone', 'left_zone', 'fed'
            $table->jsonb('event_data')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();
        });

        Schema::create('rfid_gate_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('gate_id')->constrained('gates')->cascadeOnDelete();
            $table->foreignUuid('rfid_tag_id')->constrained('rfid_tags')->cascadeOnDelete();
            $table->string('direction'); // in, out
            $table->timestamp('crossed_at');
            $table->timestamps();
        });

        // QR Code Architecture
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('code_hash')->unique();
            $table->uuid('linkable_id');
            $table->string('linkable_type'); // Animal, Barn, FeedBatch, etc.
            $table->string('qr_image_path')->nullable();
            $table->timestamps();
            
            $table->index(['linkable_id', 'linkable_type']);
        });

        Schema::create('qr_labels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('qr_code_id')->constrained('qr_codes')->cascadeOnDelete();
            $table->string('label_template');
            $table->timestamp('printed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('qr_scans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('qr_code_id')->constrained('qr_codes')->cascadeOnDelete();
            $table->foreignUuid('scanned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('scanned_at');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_scans');
        Schema::dropIfExists('qr_labels');
        Schema::dropIfExists('qr_codes');
        Schema::dropIfExists('rfid_gate_logs');
        Schema::dropIfExists('rfid_events');
        Schema::dropIfExists('rfid_logs');
        Schema::dropIfExists('rfid_tags');
        Schema::dropIfExists('rfid_antennas');
        Schema::dropIfExists('rfid_readers');
        Schema::dropIfExists('rfid_devices');
    }
};
