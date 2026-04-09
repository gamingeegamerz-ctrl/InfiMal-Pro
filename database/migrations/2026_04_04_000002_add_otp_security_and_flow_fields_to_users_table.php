<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'onboarding_step')) {
                $table->string('onboarding_step')->default('payment_required')->after('last_login_at');
            }
            if (! Schema::hasColumn('users', 'otp_failed_attempts')) {
                $table->unsignedTinyInteger('otp_failed_attempts')->default(0)->after('otp_expires_at');
            }
            if (! Schema::hasColumn('users', 'otp_locked_until')) {
                $table->timestamp('otp_locked_until')->nullable()->after('otp_failed_attempts');
            }
            if (! Schema::hasColumn('users', 'otp_last_sent_at')) {
                $table->timestamp('otp_last_sent_at')->nullable()->after('otp_locked_until');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            foreach (['onboarding_step', 'otp_failed_attempts', 'otp_locked_until', 'otp_last_sent_at'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
