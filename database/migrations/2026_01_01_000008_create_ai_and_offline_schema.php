<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // AI Architecture
        Schema::create('ai_models', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('version');
            $table->string('purpose'); // Health, Breeding, Yield
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('ai_predictions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('model_id')->constrained('ai_models');
            $table->uuid('target_id'); // Animal ID, Barn ID, etc.
            $table->string('target_type');
            $table->string('prediction_type');
            $table->decimal('confidence_score', 5, 4);
            $table->jsonb('prediction_data');
            $table->boolean('is_acknowledged')->default(false);
            $table->timestamps();
            
            $table->index(['target_id', 'target_type']);
        });

        Schema::create('ai_recommendations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('prediction_id')->constrained('ai_predictions')->cascadeOnDelete();
            $table->text('action_suggested');
            $table->boolean('was_implemented')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_alerts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('prediction_id')->constrained('ai_predictions')->cascadeOnDelete();
            $table->string('severity'); // Low, Medium, High, Critical
            $table->string('message');
            $table->timestamps();
        });

        Schema::create('ai_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('model_id')->constrained('ai_models');
            $table->decimal('execution_time_ms', 10, 2);
            $table->boolean('success');
            $table->jsonb('error_details')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_training_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('model_id')->constrained('ai_models');
            $table->string('dataset_reference');
            $table->integer('records_count');
            $table->timestamp('trained_at');
            $table->timestamps();
        });

        // Reports Architecture
        Schema::create('report_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('module'); // Finance, Animals, Health
            $table->jsonb('config_schema');
            $table->timestamps();
        });

        Schema::create('generated_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('template_id')->constrained('report_templates');
            $table->foreignUuid('generated_by')->constrained('users');
            $table->string('file_path');
            $table->string('status'); // Pending, Generating, Completed, Failed
            $table->timestamps();
        });

        Schema::create('exports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users');
            $table->string('type'); // CSV, PDF, Excel
            $table->string('file_path');
            $table->timestamps();
        });

        // Notifications Architecture
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('channel'); // Email, SMS, Push, Database
            $table->text('content_template');
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type');
            $table->jsonb('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('channel');
            $table->string('status'); // Sent, Failed
            $table->text('error_message')->nullable();
            $table->timestamps();
        });

        // System & Audit Architecture
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->nullable()->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event'); // created, updated, deleted
            $table->uuid('auditable_id');
            $table->string('auditable_type');
            $table->jsonb('old_values')->nullable();
            $table->jsonb('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['auditable_id', 'auditable_type']);
            $table->index('farm_id');
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('action');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('system_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('level'); // error, warning, info
            $table->text('message');
            $table->jsonb('context')->nullable();
            $table->timestamps();
        });

        Schema::create('backup_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('file_name');
            $table->decimal('size_mb', 8, 2);
            $table->string('status'); // Success, Failed
            $table->timestamps();
        });

        // PWA Offline Sync Architecture
        Schema::create('offline_queue', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('operation'); // POST, PUT, DELETE
            $table->string('endpoint');
            $table->jsonb('payload');
            $table->string('client_uuid')->unique(); // UUID generated on client-side
            $table->integer('retry_count')->default(0);
            $table->string('status')->default('pending'); // pending, processed, failed
            $table->timestamps();
        });

        Schema::create('sync_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->integer('records_synced');
            $table->integer('conflicts_detected');
            $table->decimal('duration_ms', 10, 2);
            $table->timestamps();
        });

        Schema::create('conflict_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('queue_id')->constrained('offline_queue')->cascadeOnDelete();
            $table->jsonb('client_data');
            $table->jsonb('server_data');
            $table->string('resolution_strategy'); // Server Wins, Client Wins, Manual
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conflict_logs');
        Schema::dropIfExists('sync_logs');
        Schema::dropIfExists('offline_queue');
        Schema::dropIfExists('backup_logs');
        Schema::dropIfExists('system_logs');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('exports');
        Schema::dropIfExists('generated_reports');
        Schema::dropIfExists('report_templates');
        Schema::dropIfExists('ai_training_data');
        Schema::dropIfExists('ai_logs');
        Schema::dropIfExists('ai_alerts');
        Schema::dropIfExists('ai_recommendations');
        Schema::dropIfExists('ai_predictions');
        Schema::dropIfExists('ai_models');
    }
};
