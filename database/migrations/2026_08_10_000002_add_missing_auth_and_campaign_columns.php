<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table): void {
                if (!Schema::hasColumn('users', 'google_id')) $table->string('google_id')->nullable()->index();
                if (!Schema::hasColumn('users', 'google_password_set')) $table->boolean('google_password_set')->default(false);
                if (!Schema::hasColumn('users', 'payment_status')) $table->string('payment_status')->default('unpaid');
                if (!Schema::hasColumn('users', 'is_paid')) $table->boolean('is_paid')->default(false)->index();
                if (!Schema::hasColumn('users', 'is_verified')) $table->boolean('is_verified')->default(false);
                if (!Schema::hasColumn('users', 'paid_at')) $table->timestamp('paid_at')->nullable();
                if (!Schema::hasColumn('users', 'license_key')) $table->string('license_key')->nullable()->unique();
                if (!Schema::hasColumn('users', 'license_status')) $table->string('license_status')->default('inactive');
                if (!Schema::hasColumn('users', 'phone')) $table->string('phone')->nullable();
                if (!Schema::hasColumn('users', 'bio')) $table->text('bio')->nullable();
                if (!Schema::hasColumn('users', 'plan_name')) $table->string('plan_name')->nullable();
                if (!Schema::hasColumn('users', 'payment_date')) $table->timestamp('payment_date')->nullable();
                if (!Schema::hasColumn('users', 'payment_amount')) $table->decimal('payment_amount', 12, 2)->nullable();
                if (!Schema::hasColumn('users', 'transaction_id')) $table->string('transaction_id')->nullable()->index();
                if (!Schema::hasColumn('users', 'payment_id')) $table->string('payment_id')->nullable()->index();
                if (!Schema::hasColumn('users', 'license_expires_at')) $table->timestamp('license_expires_at')->nullable();
                if (!Schema::hasColumn('users', 'is_admin')) $table->boolean('is_admin')->default(false)->index();
                if (!Schema::hasColumn('users', 'otp_code')) $table->string('otp_code')->nullable();
                if (!Schema::hasColumn('users', 'otp_expires_at')) $table->timestamp('otp_expires_at')->nullable();
                if (!Schema::hasColumn('users', 'otp_verified_at')) $table->timestamp('otp_verified_at')->nullable();
                if (!Schema::hasColumn('users', 'accepted_terms_at')) $table->timestamp('accepted_terms_at')->nullable();
                if (!Schema::hasColumn('users', 'last_login_at')) $table->timestamp('last_login_at')->nullable();
                if (!Schema::hasColumn('users', 'campaign_count')) $table->unsignedInteger('campaign_count')->default(0);
                if (!Schema::hasColumn('users', 'email_sent')) $table->unsignedBigInteger('email_sent')->default(0);
                if (!Schema::hasColumn('users', 'otp_last_sent_at')) $table->timestamp('otp_last_sent_at')->nullable();
                if (!Schema::hasColumn('users', 'otp_locked_until')) $table->timestamp('otp_locked_until')->nullable();
                if (!Schema::hasColumn('users', 'otp_failed_attempts')) $table->unsignedInteger('otp_failed_attempts')->default(0);
                if (!Schema::hasColumn('users', 'onboarding_step')) $table->string('onboarding_step')->nullable();
                if (!Schema::hasColumn('users', 'daily_limit')) $table->unsignedInteger('daily_limit')->default(2000);
                if (!Schema::hasColumn('users', 'today_sent')) $table->unsignedInteger('today_sent')->default(0);
                if (!Schema::hasColumn('users', 'warmup_stage')) $table->unsignedTinyInteger('warmup_stage')->default(1);
                if (!Schema::hasColumn('users', 'suspended')) $table->boolean('suspended')->default(false)->index();
                if (!Schema::hasColumn('users', 'suspension_reason')) $table->string('suspension_reason')->nullable();
                if (!Schema::hasColumn('users', 'domain')) $table->string('domain')->nullable();
                if (!Schema::hasColumn('users', 'domain_verified')) $table->boolean('domain_verified')->default(false);
                if (!Schema::hasColumn('users', 'verification_token')) $table->string('verification_token')->nullable()->index();
                if (!Schema::hasColumn('users', 'bounce_count')) $table->unsignedInteger('bounce_count')->default(0);
                if (!Schema::hasColumn('users', 'complaint_count')) $table->unsignedInteger('complaint_count')->default(0);
            });
        }

        if (Schema::hasTable('campaigns')) {
            Schema::table('campaigns', function (Blueprint $table): void {
                if (!Schema::hasColumn('campaigns', 'html_content')) $table->longText('html_content')->nullable();
                if (!Schema::hasColumn('campaigns', 'plain_text')) $table->longText('plain_text')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Intentionally non-destructive. Existing production data may depend on these columns.
    }
};
