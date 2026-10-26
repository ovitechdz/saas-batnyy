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
        Schema::table('animals', function (Blueprint $table) {
            $table->string('visual_tag')->nullable()->after('internal_id');
            $table->string('electronic_tag')->nullable()->after('visual_tag');
            $table->string('qr_code')->nullable()->after('electronic_tag');
            
            $table->string('color')->nullable()->after('gender');
            $table->string('horn_status')->nullable()->after('color'); // e.g. Polled, Horned, Scurred
            $table->string('wool_type')->nullable()->after('horn_status');
            
            $table->decimal('body_condition_score', 3, 1)->nullable()->after('birth_weight');
            $table->decimal('current_market_value', 12, 2)->nullable()->after('body_condition_score');
            
            $table->string('origin')->nullable()->after('current_market_value');
            $table->string('insurance_policy')->nullable()->after('origin');
            
            $table->uuid('feed_group_id')->nullable()->after('pen_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropColumn([
                'visual_tag',
                'electronic_tag',
                'qr_code',
                'color',
                'horn_status',
                'wool_type',
                'body_condition_score',
                'current_market_value',
                'origin',
                'insurance_policy',
                'feed_group_id'
            ]);
        });
    }
};
