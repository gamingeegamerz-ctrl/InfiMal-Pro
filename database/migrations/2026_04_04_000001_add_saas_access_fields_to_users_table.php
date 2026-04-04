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
            if (! Schema::hasColumn('users', 'accepted_terms_at')) {
                $table->timestamp('accepted_terms_at')->nullable()->after('otp_verified_at');
            }
            if (! Schema::hasColumn('users', 'campaign_count')) {
                $table->unsignedInteger('campaign_count')->default(0)->after('accepted_terms_at');
            }
            if (! Schema::hasColumn('users', 'email_sent')) {
                $table->unsignedBigInteger('email_sent')->default(0)->after('campaign_count');
            }
            if (! Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('email_sent');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            foreach (['accepted_terms_at', 'campaign_count', 'email_sent', 'last_login_at'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
