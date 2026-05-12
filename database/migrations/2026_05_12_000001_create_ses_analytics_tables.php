<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sent_emails', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
            $table->string('message_id')->nullable()->index();
            $table->string('recipient_email')->index();
            $table->string('from_email');
            $table->string('subject');
            $table->string('status')->default('sent')->index();
            $table->timestamp('sent_at')->nullable()->index();
            $table->timestamps();

            $table->index(['user_id', 'campaign_id', 'sent_at']);
        });

        Schema::create('email_opens', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sent_email_id')->nullable()->constrained('sent_emails')->nullOnDelete();
            $table->string('message_id')->nullable()->index();
            $table->string('recipient_email')->index();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('opened_at')->index();
            $table->timestamps();

            $table->index(['user_id', 'campaign_id', 'opened_at']);
        });

        Schema::create('email_clicks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sent_email_id')->nullable()->constrained('sent_emails')->nullOnDelete();
            $table->string('message_id')->nullable()->index();
            $table->string('recipient_email')->index();
            $table->text('url');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('clicked_at')->index();
            $table->timestamps();

            $table->index(['user_id', 'campaign_id', 'clicked_at']);
        });

        if (! Schema::hasTable('bounces')) {
            Schema::create('bounces', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('sent_email_id')->nullable()->constrained('sent_emails')->nullOnDelete();
                $table->string('message_id')->nullable()->index();
                $table->string('recipient_email')->index();
                $table->string('bounce_type')->nullable();
                $table->string('bounce_sub_type')->nullable();
                $table->text('diagnostic_code')->nullable();
                $table->timestamp('bounced_at')->index();
                $table->timestamps();

                $table->index(['user_id', 'campaign_id', 'bounced_at']);
            });
        } else {
            Schema::table('bounces', function (Blueprint $table): void {
                if (! Schema::hasColumn('bounces', 'sent_email_id')) {
                    $table->foreignId('sent_email_id')->nullable()->after('user_id')->constrained('sent_emails')->nullOnDelete();
                }
                if (! Schema::hasColumn('bounces', 'message_id')) {
                    $table->string('message_id')->nullable()->index()->after('sent_email_id');
                }
                if (! Schema::hasColumn('bounces', 'recipient_email')) {
                    $table->string('recipient_email')->nullable()->index()->after('message_id');
                }
                if (! Schema::hasColumn('bounces', 'bounce_type')) {
                    $table->string('bounce_type')->nullable()->after('recipient_email');
                }
                if (! Schema::hasColumn('bounces', 'bounce_sub_type')) {
                    $table->string('bounce_sub_type')->nullable()->after('bounce_type');
                }
                if (! Schema::hasColumn('bounces', 'diagnostic_code')) {
                    $table->text('diagnostic_code')->nullable()->after('bounce_sub_type');
                }
                if (! Schema::hasColumn('bounces', 'bounced_at')) {
                    $table->timestamp('bounced_at')->nullable()->index()->after('diagnostic_code');
                }
            });
        }

        Schema::create('complaints', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sent_email_id')->nullable()->constrained('sent_emails')->nullOnDelete();
            $table->string('message_id')->nullable()->index();
            $table->string('recipient_email')->index();
            $table->string('complaint_feedback_type')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('complained_at')->index();
            $table->timestamps();

            $table->index(['user_id', 'campaign_id', 'complained_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
        if (Schema::hasTable('bounces')) {
            Schema::table('bounces', function (Blueprint $table): void {
                foreach (['bounced_at', 'diagnostic_code', 'bounce_sub_type', 'bounce_type', 'recipient_email', 'message_id', 'sent_email_id'] as $column) {
                    if (Schema::hasColumn('bounces', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
        Schema::dropIfExists('email_clicks');
        Schema::dropIfExists('email_opens');
        Schema::dropIfExists('sent_emails');
    }
};
