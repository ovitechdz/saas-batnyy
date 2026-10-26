<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Feed Categories & Suppliers
        Schema::create('feed_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('feed_suppliers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // Feed Inventory
        Schema::create('feed_inventory', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('category_id')->constrained('feed_categories');
            $table->string('name');
            $table->decimal('total_quantity', 12, 2)->default(0);
            $table->string('unit'); // kg, ton, bale
            $table->decimal('minimum_threshold', 12, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('nutrition_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('feed_inventory_id')->constrained('feed_inventory')->cascadeOnDelete();
            $table->decimal('protein_percentage', 5, 2)->nullable();
            $table->decimal('energy_mcal', 5, 2)->nullable();
            $table->decimal('fiber_percentage', 5, 2)->nullable();
            $table->decimal('calcium_percentage', 5, 2)->nullable();
            $table->decimal('phosphorus_percentage', 5, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('feed_batches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('feed_inventory_id')->constrained('feed_inventory')->cascadeOnDelete();
            $table->string('batch_number');
            $table->foreignUuid('supplier_id')->nullable()->constrained('feed_suppliers')->nullOnDelete();
            $table->decimal('quantity_received', 12, 2);
            $table->decimal('quantity_remaining', 12, 2);
            $table->date('delivery_date');
            $table->date('expiration_date')->nullable();
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('feed_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('supplier_id')->constrained('feed_suppliers');
            $table->date('order_date');
            $table->date('expected_delivery_date')->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->string('status'); // Pending, Delivered, Cancelled
            $table->timestamps();
        });

        Schema::create('feed_costs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('feed_batch_id')->constrained('feed_batches')->cascadeOnDelete();
            $table->decimal('total_cost', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->timestamps();
        });

        Schema::create('feed_consumption', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('feed_batch_id')->constrained('feed_batches');
            $table->foreignUuid('pen_id')->nullable()->constrained('pens'); // Feed distributed to a specific pen
            $table->foreignUuid('animal_id')->nullable()->constrained('animals'); // Or individual animal
            $table->decimal('quantity_consumed', 10, 2);
            $table->timestamp('fed_at');
            $table->foreignUuid('fed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['farm_id', 'fed_at']);
        });

        // Feed Production (For on-farm mixed feeds)
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('stock_quantity', 12, 2);
            $table->string('unit');
            $table->timestamps();
        });

        Schema::create('silage', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('pit_name');
            $table->date('harvest_date');
            $table->date('open_date')->nullable();
            $table->decimal('estimated_tonnage', 10, 2);
            $table->string('status'); // Fermenting, Open, Empty
            $table->timestamps();
        });

        Schema::create('hay', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('field_source')->nullable();
            $table->date('cut_date');
            $table->integer('bale_count');
            $table->decimal('average_bale_weight', 8, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('concentrates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->text('recipe_description')->nullable();
            $table->timestamps();
        });

        Schema::create('production_batches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('batch_number');
            $table->foreignUuid('concentrate_id')->nullable()->constrained('concentrates');
            $table->decimal('yield_quantity', 10, 2);
            $table->string('unit');
            $table->date('production_date');
            $table->timestamps();
        });

        Schema::create('production_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('production_batch_id')->constrained('production_batches')->cascadeOnDelete();
            $table->foreignUuid('raw_material_id')->nullable()->constrained('raw_materials');
            $table->decimal('quantity_used', 10, 2);
            $table->timestamps();
        });

        Schema::create('production_costs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('production_batch_id')->constrained('production_batches')->cascadeOnDelete();
            $table->string('cost_type'); // Labor, Machinery, Raw Materials
            $table->decimal('amount', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_costs');
        Schema::dropIfExists('production_logs');
        Schema::dropIfExists('production_batches');
        Schema::dropIfExists('concentrates');
        Schema::dropIfExists('hay');
        Schema::dropIfExists('silage');
        Schema::dropIfExists('raw_materials');
        Schema::dropIfExists('feed_consumption');
        Schema::dropIfExists('feed_costs');
        Schema::dropIfExists('feed_orders');
        Schema::dropIfExists('feed_batches');
        Schema::dropIfExists('nutrition_profiles');
        Schema::dropIfExists('feed_inventory');
        Schema::dropIfExists('feed_suppliers');
        Schema::dropIfExists('feed_categories');
    }
};
