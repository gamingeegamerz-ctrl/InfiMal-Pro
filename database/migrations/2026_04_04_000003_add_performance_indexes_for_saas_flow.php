<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table): void {
                $table->index(['user_id', 'status'], 'payments_user_status_index');
            });
        }

        if (Schema::hasTable('email_logs')) {
            Schema::table('email_logs', function (Blueprint $table): void {
                $table->index(['user_id', 'created_at'], 'email_logs_user_created_index');
            });
        }

        if (Schema::hasTable('campaigns')) {
            Schema::table('campaigns', function (Blueprint $table): void {
                $table->index(['user_id', 'created_at'], 'campaigns_user_created_index');
            });
        }

        if (Schema::hasTable('subscribers')) {
            Schema::table('subscribers', function (Blueprint $table): void {
                $table->index(['user_id', 'created_at'], 'subscribers_user_created_index');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table): void {
                $table->dropIndex('payments_user_status_index');
            });
        }

        if (Schema::hasTable('email_logs')) {
            Schema::table('email_logs', function (Blueprint $table): void {
                $table->dropIndex('email_logs_user_created_index');
            });
        }

        if (Schema::hasTable('campaigns')) {
            Schema::table('campaigns', function (Blueprint $table): void {
                $table->dropIndex('campaigns_user_created_index');
            });
        }

        if (Schema::hasTable('subscribers')) {
            Schema::table('subscribers', function (Blueprint $table): void {
                $table->dropIndex('subscribers_user_created_index');
            });
        }
    }
};
