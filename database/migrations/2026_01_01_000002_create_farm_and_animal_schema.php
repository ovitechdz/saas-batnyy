<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Farm Locations
        Schema::create('farm_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('street');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->foreignUuid('country_id')->constrained('countries');
            $table->timestamps();
        });

        Schema::create('farm_locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('total_area_hectares', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('barns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('location_id')->nullable()->constrained('farm_locations')->nullOnDelete();
            $table->string('name');
            $table->integer('capacity')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('zones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('barn_id')->nullable()->constrained('barns')->cascadeOnDelete();
            $table->string('name');
            $table->string('purpose')->nullable();
            $table->timestamps();
        });

        Schema::create('pens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('zone_id')->nullable()->constrained('zones')->cascadeOnDelete();
            $table->string('name');
            $table->integer('capacity')->nullable();
            $table->timestamps();
        });

        Schema::create('gates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignUuid('pen_id')->nullable()->constrained('pens')->cascadeOnDelete();
            $table->string('name');
            $table->boolean('is_open')->default(false);
            $table->timestamps();
        });

        Schema::create('gps_locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->uuid('trackable_id');
            $table->string('trackable_type');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamp('recorded_at');
            
            $table->index(['trackable_id', 'trackable_type']);
        });

        // Animal Lookups
        Schema::create('animal_species', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('animal_breeds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('species_id')->constrained('animal_species')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('animal_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name'); // e.g., Ewe, Ram, Lamb
            $table->timestamps();
        });

        Schema::create('animal_status', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name'); // e.g., Healthy, Sick, Pregnant, Sold, Deceased
            $table->timestamps();
        });

        // Core Animal Table
        Schema::create('animals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('internal_id')->nullable();
            $table->string('name')->nullable();
            
            $table->foreignUuid('species_id')->constrained('animal_species');
            $table->foreignUuid('breed_id')->nullable()->constrained('animal_breeds');
            $table->foreignUuid('category_id')->nullable()->constrained('animal_categories');
            $table->foreignUuid('status_id')->constrained('animal_status');
            
            $table->enum('gender', ['M', 'F', 'Unknown'])->default('Unknown');
            $table->date('birth_date')->nullable();
            $table->decimal('birth_weight', 8, 2)->nullable();
            
            // Self-referencing relationships for pedigree
            $table->uuid('mother_id')->nullable();
            $table->uuid('father_id')->nullable();
            
            // Location
            $table->foreignUuid('pen_id')->nullable()->constrained('pens')->nullOnDelete();
            
            // Audit
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->foreignUuid('updated_by')->nullable()->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('farm_id');
            $table->index('status_id');
            $table->index(['farm_id', 'internal_id']);
        });

        // Add foreign keys for mother/father
        Schema::table('animals', function (Blueprint $table) {
            $table->foreign('mother_id')->references('id')->on('animals')->nullOnDelete();
            $table->foreign('father_id')->references('id')->on('animals')->nullOnDelete();
        });

        // Animal Details
        Schema::create('animal_photos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->string('photo_path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('animal_weights', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->decimal('weight_kg', 8, 2);
            $table->date('measurement_date');
            $table->timestamps();
            $table->index(['animal_id', 'measurement_date']);
        });

        Schema::create('animal_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->uuid('from_pen_id')->nullable()->constrained('pens');
            $table->uuid('to_pen_id')->nullable()->constrained('pens');
            $table->timestamp('moved_at');
            $table->foreignUuid('moved_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('animal_sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->decimal('sale_price', 12, 2);
            $table->uuid('buyer_id')->nullable(); // Can link to a customers table
            $table->date('sale_date');
            $table->timestamps();
        });

        Schema::create('animal_purchases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->decimal('purchase_price', 12, 2);
            $table->uuid('supplier_id')->nullable();
            $table->date('purchase_date');
            $table->timestamps();
        });

        Schema::create('animal_deaths', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->date('death_date');
            $table->string('cause_of_death')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('animal_births', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mother_id')->nullable()->constrained('animals')->nullOnDelete();
            $table->foreignUuid('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->date('birth_date');
            $table->integer('litter_size')->default(1);
            $table->integer('live_births')->default(1);
            $table->timestamps();
        });

        Schema::create('animal_notes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->foreignUuid('created_by')->constrained('users');
            $table->text('note');
            $table->timestamps();
        });

        Schema::create('animal_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->string('title');
            $table->string('file_path');
            $table->string('document_type')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animal_documents');
        Schema::dropIfExists('animal_notes');
        Schema::dropIfExists('animal_births');
        Schema::dropIfExists('animal_deaths');
        Schema::dropIfExists('animal_purchases');
        Schema::dropIfExists('animal_sales');
        Schema::dropIfExists('animal_movements');
        Schema::dropIfExists('animal_weights');
        Schema::dropIfExists('animal_photos');
        
        // Remove self referencing foreign keys before dropping
        Schema::table('animals', function (Blueprint $table) {
            $table->dropForeign(['mother_id']);
            $table->dropForeign(['father_id']);
        });
        
        Schema::dropIfExists('animals');
        Schema::dropIfExists('animal_status');
        Schema::dropIfExists('animal_categories');
        Schema::dropIfExists('animal_breeds');
        Schema::dropIfExists('animal_species');
        Schema::dropIfExists('gps_locations');
        Schema::dropIfExists('gates');
        Schema::dropIfExists('pens');
        Schema::dropIfExists('zones');
        Schema::dropIfExists('barns');
        Schema::dropIfExists('farm_locations');
        Schema::dropIfExists('farm_addresses');
    }
};
