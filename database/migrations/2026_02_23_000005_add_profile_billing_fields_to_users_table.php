<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'plan_name')) {
                $table->string('plan_name')->nullable()->after('payment_status');
            }

            if (!Schema::hasColumn('users', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('plan_name');
            }
        });

        if (Schema::hasColumn('users', 'payment_date')) {
            DB::table('users')
                ->whereNull('paid_at')
                ->whereNotNull('payment_date')
                ->update(['paid_at' => DB::raw('payment_date')]);
        }

        DB::table('users')
            ->whereNull('plan_name')
            ->update(['plan_name' => 'Free']);
    }

    public function down(): void
    {
        // non-destructive
    }
};
