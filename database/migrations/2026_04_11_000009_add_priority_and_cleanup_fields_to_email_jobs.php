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
                $table->unsignedTinyInteger('priority')->default(3)->after('status');
            }
            if (! Schema::hasColumn('email_jobs', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('retry_at');
            }

            $table->index(['status', 'scheduled_at', 'retry_at', 'priority'], 'email_jobs_due_priority_idx');
            $table->index(['status', 'expires_at'], 'email_jobs_status_expires_idx');
        });
    }

    public function down(): void
    {
        // non-destructive
    }
};
