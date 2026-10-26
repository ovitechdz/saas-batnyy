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
        Schema::create('silages', function (Blueprint $table) {
            $table->id();
            $table->string('pit_name');
            $table->date('harvest_date');
            $table->decimal('estimated_tonnage', 10, 2);
            $table->string('status')->default('Fermenting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('silages');
    }
};
