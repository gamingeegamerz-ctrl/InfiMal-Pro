<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'payment_status')) {
                $table->string('payment_status')->default('free')->after('password');
            }

            if (!Schema::hasColumn('users', 'plan_name')) {
                $table->string('plan_name')->nullable()->after('payment_status');
            }

            if (!Schema::hasColumn('users', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('plan_name');
            }
        });
    }

    public function down(): void
    {
        // non-destructive migration
    }
};
