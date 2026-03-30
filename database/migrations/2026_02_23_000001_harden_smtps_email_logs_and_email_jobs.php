<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('smtps')) {
            Schema::table('smtps', function (Blueprint $table) {
                if (!Schema::hasColumn('smtps', 'host')) {
                    $table->string('host')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('smtps', 'port')) {
                    $table->unsignedInteger('port')->default(587)->after('host');
                }
                if (!Schema::hasColumn('smtps', 'username')) {
                    $table->string('username')->nullable()->after('port');
                }
                if (!Schema::hasColumn('smtps', 'password_encrypted')) {
                    $table->text('password_encrypted')->nullable()->after('username');
                }
                if (!Schema::hasColumn('smtps', 'encryption')) {
                    $table->enum('encryption', ['tls', 'ssl', 'none'])->default('tls')->after('password_encrypted');
                }
                if (!Schema::hasColumn('smtps', 'from_email')) {
                    $table->string('from_email')->nullable()->after('encryption');
                }
                if (!Schema::hasColumn('smtps', 'from_name')) {
                    $table->string('from_name')->nullable()->after('from_email');
                }
                if (!Schema::hasColumn('smtps', 'daily_limit')) {
                    $table->unsignedInteger('daily_limit')->default(500)->after('from_name');
                }
                if (!Schema::hasColumn('smtps', 'per_minute_limit')) {
                    $table->unsignedInteger('per_minute_limit')->default(30)->after('daily_limit');
                }
                if (!Schema::hasColumn('smtps', 'warmup_enabled')) {
                    $table->boolean('warmup_enabled')->default(true)->after('per_minute_limit');
                }
                if (!Schema::hasColumn('smtps', 'is_default')) {
                    $table->boolean('is_default')->default(false)->after('is_active');
                }
            });
        }

        if (Schema::hasTable('email_logs')) {
            Schema::table('email_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('email_logs', 'smtp_id')) {
                    $table->foreignId('smtp_id')->nullable()->constrained('smtps')->nullOnDelete()->after('campaign_id');
                }
                if (!Schema::hasColumn('email_logs', 'recipient_email')) {
                    $table->string('recipient_email')->nullable()->after('smtp_id');
                }
                if (!Schema::hasColumn('email_logs', 'opened')) {
                    $table->boolean('opened')->default(false)->after('status');
                }
                if (!Schema::hasColumn('email_logs', 'clicked')) {
                    $table->boolean('clicked')->default(false)->after('opened');
                }

                if (!Schema::hasColumn('email_logs', 'to_email')) {
                    $table->string('to_email')->nullable()->after('recipient_email');
                }

                if (!Schema::hasColumn('email_logs', 'error_message')) {
                    $table->text('error_message')->nullable()->after('message_id');
                }

                $table->index(['user_id', 'campaign_id'], 'email_logs_user_campaign_idx');
                $table->index(['smtp_id', 'created_at'], 'email_logs_smtp_created_idx');
            });
        }

        if (!Schema::hasTable('email_jobs')) {
            return;
        }

        Schema::table('email_jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('email_jobs', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->after('id');
            }
            if (!Schema::hasColumn('email_jobs', 'campaign_id')) {
                $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete()->after('user_id');
            }
            if (!Schema::hasColumn('email_jobs', 'subscriber_id')) {
                $table->foreignId('subscriber_id')->nullable()->constrained()->nullOnDelete()->after('campaign_id');
            }
            if (!Schema::hasColumn('email_jobs', 'to_email')) {
                $table->string('to_email')->after('subscriber_id');
            }
            if (!Schema::hasColumn('email_jobs', 'to_name')) {
                $table->string('to_name')->nullable()->after('to_email');
            }
            if (!Schema::hasColumn('email_jobs', 'subject')) {
                $table->string('subject')->after('to_name');
            }
            if (!Schema::hasColumn('email_jobs', 'body')) {
                $table->text('body')->nullable()->after('subject');
            }
            if (!Schema::hasColumn('email_jobs', 'html')) {
                $table->longText('html')->nullable()->after('body');
            }
            if (!Schema::hasColumn('email_jobs', 'from_email')) {
                $table->string('from_email')->nullable()->after('html');
            }
            if (!Schema::hasColumn('email_jobs', 'from_name')) {
                $table->string('from_name')->nullable()->after('from_email');
            }
            if (!Schema::hasColumn('email_jobs', 'reply_to')) {
                $table->string('reply_to')->nullable()->after('from_name');
            }
            if (!Schema::hasColumn('email_jobs', 'status')) {
                $table->enum('status', ['queued', 'processing', 'sent', 'failed', 'bounced'])->default('queued')->after('reply_to');
            }
            if (!Schema::hasColumn('email_jobs', 'sent_at')) {
                $table->timestamp('sent_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('email_jobs', 'failed_at')) {
                $table->timestamp('failed_at')->nullable()->after('sent_at');
            }
            if (!Schema::hasColumn('email_jobs', 'error_message')) {
                $table->text('error_message')->nullable()->after('failed_at');
            }
            if (!Schema::hasColumn('email_jobs', 'retry_count')) {
                $table->unsignedInteger('retry_count')->default(0)->after('error_message');
            }
            if (!Schema::hasColumn('email_jobs', 'smtp_id')) {
                $table->foreignId('smtp_id')->nullable()->constrained('smtps')->nullOnDelete()->after('retry_count');
            }

            $table->index(['status', 'user_id'], 'email_jobs_status_user_idx');
        });
    }

    public function down(): void
    {
        // Intentionally non-destructive.
    }
};
