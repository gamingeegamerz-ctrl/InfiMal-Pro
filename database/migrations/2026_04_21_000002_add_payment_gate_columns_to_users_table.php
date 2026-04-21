<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->after('password');
            }

            if (! Schema::hasColumn('users', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('is_paid');
            }

            if (! Schema::hasColumn('users', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('is_verified');
            }

            if (! Schema::hasColumn('users', 'payment_status')) {
                $table->string('payment_status')->default('unpaid')->after('payment_id');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            foreach (['is_verified', 'payment_id'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
