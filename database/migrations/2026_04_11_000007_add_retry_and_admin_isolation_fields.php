<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('email_jobs')) {
            Schema::table('email_jobs', function (Blueprint $table) {
                if (! Schema::hasColumn('email_jobs', 'retry_at')) {
                    $table->timestamp('retry_at')->nullable()->after('scheduled_at');
                    $table->index(['status', 'retry_at'], 'email_jobs_status_retry_idx');
                }
            });
        }

        if (Schema::hasTable('smtps')) {
            Schema::table('smtps', function (Blueprint $table) {
                if (! Schema::hasColumn('smtps', 'is_admin_pool')) {
                    $table->boolean('is_admin_pool')->default(false)->after('is_default');
                    $table->index(['user_id', 'is_admin_pool'], 'smtps_user_admin_pool_idx');
                }
            });
        }

        if (Schema::hasTable('smtp_servers')) {
            Schema::table('smtp_servers', function (Blueprint $table) {
                if (! Schema::hasColumn('smtp_servers', 'max_daily_per_ip')) {
                    $table->unsignedInteger('max_daily_per_ip')->default(5000)->after('hourly_limit');
                }
                if (! Schema::hasColumn('smtp_servers', 'max_daily_per_domain')) {
                    $table->unsignedInteger('max_daily_per_domain')->default(2000)->after('max_daily_per_ip');
                }
                if (! Schema::hasColumn('smtp_servers', 'is_paused')) {
                    $table->boolean('is_paused')->default(false)->after('is_active');
                }
            });
        }
    }

    public function down(): void
    {
        // non-destructive rollback intentionally omitted
    }
};
