<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('customers')) {
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('type')->default('individual'); // butcher, wholesaler, retailer, individual
            $table->string('company')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Morocco');
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->timestamp('last_purchase_at')->nullable();
            $table->timestamps();
        });
        } // end if !hasTable
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
