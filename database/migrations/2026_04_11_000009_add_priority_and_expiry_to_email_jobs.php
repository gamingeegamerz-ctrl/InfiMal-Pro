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
            if (! Schema::hasColumn('email_jobs', 'priority')) {
                $table->unsignedTinyInteger('priority')->default(2)->after('status');
            }
            if (! Schema::hasColumn('email_jobs', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('retry_at');
            }

            $table->index(['status', 'priority', 'scheduled_at'], 'email_jobs_status_priority_sched_idx');
            $table->index(['status', 'retry_at'], 'email_jobs_status_retry_at_idx2');
        });
    }

    public function down(): void
    {
        // non-destructive
    }
};
