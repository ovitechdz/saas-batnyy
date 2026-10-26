<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Marketplace Architecture
        Schema::create('product_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('category_id')->constrained('product_categories');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('stock_quantity', 12, 2);
            $table->string('unit'); // e.g., kg, piece, animal
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete(); // Optional link if registered
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->text('shipping_address')->nullable();
            $table->text('billing_address')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete(); // The farm selling
            $table->foreignUuid('customer_id')->constrained('customers');
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->string('status'); // Pending, Paid, Shipped, Delivered, Cancelled
            $table->string('payment_status'); // Unpaid, Paid, Refunded
            $table->foreignUuid('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignUuid('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name'); // Snapshot of name
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total', 15, 2);
            $table->timestamps();
        });

        Schema::create('wishlists', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['customer_id', 'product_id']);
        });

        Schema::create('shopping_cart', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')->nullable()->constrained('customers')->cascadeOnDelete();
            $table->string('session_id')->nullable();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->decimal('quantity', 10, 2);
            $table->timestamps();
            
            $table->index('session_id');
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignUuid('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });

        // Solar & Energy Architecture
        Schema::create('solar_panels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->string('model')->nullable();
            $table->decimal('capacity_kw', 8, 2);
            $table->date('installation_date');
            $table->string('status')->default('active'); // active, maintenance, offline
            $table->timestamps();
        });

        Schema::create('batteries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('capacity_kwh', 8, 2);
            $table->decimal('current_charge_percentage', 5, 2)->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('iot_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('device_type'); // Inverter, Smart Meter, Weather Station
            $table->string('serial_number')->unique();
            $table->string('ip_address')->nullable();
            $table->jsonb('config')->nullable();
            $table->timestamps();
        });

        Schema::create('energy_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('solar_panel_id')->nullable()->constrained('solar_panels')->cascadeOnDelete();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->decimal('energy_produced_kwh', 10, 4);
            $table->timestamp('recorded_at');
            
            // This table will be partitioned by recorded_at in production
            $table->index(['farm_id', 'recorded_at']);
        });

        Schema::create('power_consumption', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('consumer_type'); // Barn, Pump, General
            $table->uuid('consumer_id')->nullable(); 
            $table->decimal('energy_consumed_kwh', 10, 4);
            $table->timestamp('recorded_at');
            
            $table->index(['farm_id', 'recorded_at']);
        });

        Schema::create('maintenance', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->uuid('maintainable_id'); // Panel ID, Battery ID, Tractor ID
            $table->string('maintainable_type');
            $table->date('scheduled_date');
            $table->date('completed_date')->nullable();
            $table->text('description');
            $table->decimal('cost', 10, 2)->nullable();
            $table->foreignUuid('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance');
        Schema::dropIfExists('power_consumption');
        Schema::dropIfExists('energy_logs');
        Schema::dropIfExists('iot_devices');
        Schema::dropIfExists('batteries');
        Schema::dropIfExists('solar_panels');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('shopping_cart');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
    }
};
