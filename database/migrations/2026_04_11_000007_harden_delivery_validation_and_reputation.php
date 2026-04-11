<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('smtps')) {
            Schema::table('smtps', function (Blueprint $table): void {
                if (! Schema::hasColumn('smtps', 'validation_status')) {
                    $table->enum('validation_status', ['valid', 'invalid', 'risky'])->default('invalid')->after('is_active');
                }
                if (! Schema::hasColumn('smtps', 'validation_message')) {
                    $table->string('validation_message', 1000)->nullable()->after('validation_status');
                }
                if (! Schema::hasColumn('smtps', 'last_validated_at')) {
                    $table->timestamp('last_validated_at')->nullable()->after('validation_message');
                }
                if (! Schema::hasColumn('smtps', 'success_rate')) {
                    $table->decimal('success_rate', 5, 4)->default(1)->after('reputation_score');
                }
                if (! Schema::hasColumn('smtps', 'bounce_rate')) {
                    $table->decimal('bounce_rate', 5, 4)->default(0)->after('success_rate');
                }
                if (! Schema::hasColumn('smtps', 'complaint_rate')) {
                    $table->decimal('complaint_rate', 5, 4)->default(0)->after('bounce_rate');
                }
                if (! Schema::hasColumn('smtps', 'engagement_score')) {
                    $table->decimal('engagement_score', 5, 4)->default(0)->after('complaint_rate');
                }
                if (! Schema::hasColumn('smtps', 'gmail_score')) {
                    $table->decimal('gmail_score', 5, 4)->default(0.5)->after('engagement_score');
                }
                if (! Schema::hasColumn('smtps', 'outlook_score')) {
                    $table->decimal('outlook_score', 5, 4)->default(0.5)->after('gmail_score');
                }
                if (! Schema::hasColumn('smtps', 'yahoo_score')) {
                    $table->decimal('yahoo_score', 5, 4)->default(0.5)->after('outlook_score');
                }
            });
        }

        if (Schema::hasTable('email_jobs')) {
            Schema::table('email_jobs', function (Blueprint $table): void {
                if (! Schema::hasColumn('email_jobs', 'idempotency_key')) {
                    $table->string('idempotency_key')->nullable()->after('smtp_id');
                    $table->unique('idempotency_key', 'email_jobs_idempotency_unique');
                }
            });
        }

        if (Schema::hasTable('admin_email_jobs')) {
            Schema::table('admin_email_jobs', function (Blueprint $table): void {
                if (! Schema::hasColumn('admin_email_jobs', 'idempotency_key')) {
                    $table->string('idempotency_key')->nullable()->after('batch_key');
                    $table->unique('idempotency_key', 'admin_email_jobs_idempotency_unique');
                }
            });
        }

        if (Schema::hasTable('admin_ip_pool')) {
            Schema::table('admin_ip_pool', function (Blueprint $table): void {
                if (! Schema::hasColumn('admin_ip_pool', 'complaint_rate')) {
                    $table->decimal('complaint_rate', 5, 4)->default(0)->after('bounce_rate');
                }
                if (! Schema::hasColumn('admin_ip_pool', 'engagement_score')) {
                    $table->decimal('engagement_score', 5, 4)->default(0)->after('complaint_rate');
                }
                if (! Schema::hasColumn('admin_ip_pool', 'gmail_score')) {
                    $table->decimal('gmail_score', 5, 4)->default(0.5)->after('engagement_score');
                }
                if (! Schema::hasColumn('admin_ip_pool', 'outlook_score')) {
                    $table->decimal('outlook_score', 5, 4)->default(0.5)->after('gmail_score');
                }
                if (! Schema::hasColumn('admin_ip_pool', 'yahoo_score')) {
                    $table->decimal('yahoo_score', 5, 4)->default(0.5)->after('outlook_score');
                }
                if (! Schema::hasColumn('admin_ip_pool', 'port_performance')) {
                    $table->json('port_performance')->nullable()->after('yahoo_score');
                }
            });
        }

        if (Schema::hasTable('email_logs')) {
            Schema::table('email_logs', function (Blueprint $table): void {
                if (! Schema::hasColumn('email_logs', 'complained_at')) {
                    $table->timestamp('complained_at')->nullable()->after('bounced_at');
                }
                if (! Schema::hasColumn('email_logs', 'replied_at')) {
                    $table->timestamp('replied_at')->nullable()->after('complained_at');
                }
                if (! Schema::hasColumn('email_logs', 'provider')) {
                    $table->string('provider')->nullable()->after('status');
                }
                if (! Schema::hasColumn('email_logs', 'idempotency_key')) {
                    $table->string('idempotency_key')->nullable()->after('message_id');
                    $table->index('idempotency_key', 'email_logs_idempotency_idx');
                }
            });
        }
    }

    public function down(): void
    {
        // Non-destructive hardening migration.
    }
};
