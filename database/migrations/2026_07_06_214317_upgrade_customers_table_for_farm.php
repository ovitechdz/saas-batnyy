<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Add farm_id if missing
            if (!Schema::hasColumn('customers', 'farm_id')) {
                $table->uuid('farm_id')->nullable()->after('id');
            }
            // Add type if missing
            if (!Schema::hasColumn('customers', 'type')) {
                $table->string('type')->default('individual')->after('farm_id');
            }
            // Add company if missing
            if (!Schema::hasColumn('customers', 'company')) {
                $table->string('company')->nullable()->after('type');
            }
            // Add city if missing
            if (!Schema::hasColumn('customers', 'city')) {
                $table->string('city')->nullable();
            }
            // Add country if missing
            if (!Schema::hasColumn('customers', 'country')) {
                $table->string('country')->default('Morocco')->nullable();
            }
            // Add address if missing
            if (!Schema::hasColumn('customers', 'address')) {
                $table->text('address')->nullable();
            }
            // Add notes if missing
            if (!Schema::hasColumn('customers', 'notes')) {
                $table->text('notes')->nullable();
            }
            // Add status if missing
            if (!Schema::hasColumn('customers', 'status')) {
                $table->string('status')->default('active');
            }
            // Add total_revenue if missing
            if (!Schema::hasColumn('customers', 'total_revenue')) {
                $table->decimal('total_revenue', 12, 2)->default(0);
            }
            // Add total_orders if missing
            if (!Schema::hasColumn('customers', 'total_orders')) {
                $table->integer('total_orders')->default(0);
            }
            // Add last_purchase_at if missing
            if (!Schema::hasColumn('customers', 'last_purchase_at')) {
                $table->timestamp('last_purchase_at')->nullable();
            }
            // Add name column if missing (merged from first_name + last_name)
            if (!Schema::hasColumn('customers', 'name')) {
                $table->string('name')->nullable()->after('farm_id');
            }
        });

        // Auto-fill name from first_name + last_name for existing rows
        if (Schema::hasColumn('customers', 'first_name') && Schema::hasColumn('customers', 'name')) {
            DB::statement("UPDATE customers SET name = CONCAT(COALESCE(first_name,''), ' ', COALESCE(last_name,'')) WHERE name IS NULL OR name = ''");
        }

        // Set farm_id to first farm for all existing rows that have none
        $firstFarmId = DB::table('farms')->value('id');
        if ($firstFarmId) {
            DB::table('customers')->whereNull('farm_id')->update(['farm_id' => $firstFarmId]);
        }

        // Now make farm_id NOT nullable
        Schema::table('customers', function (Blueprint $table) {
            $table->uuid('farm_id')->nullable(false)->change();
            if (Schema::hasColumn('customers', 'name')) {
                $table->string('name')->nullable(false)->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $columns = ['farm_id', 'type', 'company', 'city', 'country', 'address', 'notes', 'status', 'total_revenue', 'total_orders', 'last_purchase_at', 'name'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('customers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
