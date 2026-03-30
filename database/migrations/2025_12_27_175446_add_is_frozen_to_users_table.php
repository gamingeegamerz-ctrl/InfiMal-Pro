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
            if (!Schema::hasColumn('users', 'is_frozen')) {
                $table->boolean('is_frozen')->default(false);
            }
            if (!Schema::hasColumn('users', 'license_status')) {
                $table->string('license_status')->nullable();
            }
            if (!Schema::hasColumn('users', 'license_expires_at')) {
                $table->timestamp('license_expires_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'stage')) {
                $table->integer('stage')->default(1);
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            foreach (['is_frozen', 'license_status', 'license_expires_at', 'stage'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
