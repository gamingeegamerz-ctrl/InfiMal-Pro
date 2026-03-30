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
            if (!Schema::hasColumn('users', 'license_status')) {
                $table->string('license_status')->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'license_key')) {
                $table->string('license_key')->nullable()->after('license_status')->unique();
            }
            if (!Schema::hasColumn('users', 'license_expires_at')) {
                $table->timestamp('license_expires_at')->nullable()->after('license_key');
            }
            if (!Schema::hasColumn('users', 'license_plan')) {
                $table->string('license_plan')->default('basic')->after('license_expires_at');
            }
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false)->after('license_plan');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            foreach ([
                'license_status',
                'license_key',
                'license_expires_at',
                'license_plan',
                'is_admin',
            ] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
