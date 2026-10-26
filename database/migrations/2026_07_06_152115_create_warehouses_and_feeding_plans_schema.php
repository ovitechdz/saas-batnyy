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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->string('type'); // Silo, Cold Storage, Room
            $table->decimal('capacity', 12, 2)->nullable();
            $table->decimal('current_utilization', 12, 2)->default(0);
            $table->string('unit')->default('kg');
            $table->timestamps();
        });

        Schema::create('storage_locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->string('row_identifier')->nullable();
            $table->string('shelf_identifier')->nullable();
            $table->timestamps();
        });

        Schema::create('feeding_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->string('target_group'); // e.g., 'Lactating Ewes', 'Lambs', 'All'
            $table->foreignUuid('feed_inventory_id')->constrained('feed_inventory')->cascadeOnDelete();
            $table->decimal('daily_ration_kg', 8, 2);
            $table->time('schedule_time'); // Morning, Afternoon etc. represented by time
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Let's also add a warehouse_id to feed_inventory table to link inventory to storage
        Schema::table('feed_inventory', function (Blueprint $table) {
            $table->foreignUuid('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feed_inventory', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');
        });
        
        Schema::dropIfExists('feeding_plans');
        Schema::dropIfExists('storage_locations');
        Schema::dropIfExists('warehouses');
    }
};
