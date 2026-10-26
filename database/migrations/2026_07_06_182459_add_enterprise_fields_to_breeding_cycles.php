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
        Schema::table('breeding_cycles', function (Blueprint $table) {
            $table->foreignUuid('ram_id')->nullable()->after('animal_id')->comment('Sire/Father ID');
            $table->string('breeding_method')->default('natural')->after('ram_id')->comment('natural, ai, embryo_transfer');
            $table->string('mating_type')->nullable()->after('breeding_method')->comment('pen, paddock, hand');
            $table->decimal('bcs_at_mating', 3, 1)->nullable()->after('mating_type')->comment('Body Condition Score 1.0 - 5.0');
            $table->date('scan_date')->nullable()->after('expected_birth_date')->comment('Ultrasound/Scan date');
            $table->string('pregnancy_status')->default('unknown')->after('scan_date')->comment('unknown, pregnant, open, aborted');
            $table->integer('fetus_count')->nullable()->after('pregnancy_status');
            $table->text('notes')->nullable()->after('fetus_count');
        });
    }

    public function down(): void
    {
        Schema::table('breeding_cycles', function (Blueprint $table) {
            $table->dropColumn([
                'ram_id',
                'breeding_method',
                'mating_type',
                'bcs_at_mating',
                'scan_date',
                'pregnancy_status',
                'fetus_count',
                'notes'
            ]);
        });
    }
};
