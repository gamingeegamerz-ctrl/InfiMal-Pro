<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'payment_status')) {
                $table->enum('payment_status', ['unpaid', 'paid', 'expired'])->default('unpaid')->after('workspace_id');
            }
            if (!Schema::hasColumn('users', 'payment_date')) {
                $table->timestamp('payment_date')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('users', 'payment_amount')) {
                $table->decimal('payment_amount', 10, 2)->nullable()->after('payment_date');
            }
            if (!Schema::hasColumn('users', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('payment_amount');
            }
            if (!Schema::hasColumn('users', 'plan_expiry_date')) {
                $table->timestamp('plan_expiry_date')->nullable()->after('transaction_id');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $columns = ['payment_status', 'payment_date', 'payment_amount', 'transaction_id', 'plan_expiry_date'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
