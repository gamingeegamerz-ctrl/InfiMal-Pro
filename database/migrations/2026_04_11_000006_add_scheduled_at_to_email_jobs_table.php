<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('email_jobs')) {
            return;
        }

        Schema::table('email_jobs', function (Blueprint $table) {
            if (! Schema::hasColumn('email_jobs', 'scheduled_at')) {
                $table->timestamp('scheduled_at')->nullable()->after('status');
                $table->index(['status', 'scheduled_at'], 'email_jobs_status_scheduled_idx');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('email_jobs')) {
            return;
        }

        Schema::table('email_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('email_jobs', 'scheduled_at')) {
                $table->dropIndex('email_jobs_status_scheduled_idx');
                $table->dropColumn('scheduled_at');
            }
        });
    }
};
