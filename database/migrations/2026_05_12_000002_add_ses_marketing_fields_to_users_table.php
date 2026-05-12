<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'daily_limit')) {
                $table->unsignedInteger('daily_limit')->default(50)->after('email_sent');
            }
            if (! Schema::hasColumn('users', 'today_sent')) {
                $table->unsignedInteger('today_sent')->default(0)->after('daily_limit');
            }
            if (! Schema::hasColumn('users', 'warmup_stage')) {
                $table->string('warmup_stage')->default('days_1_7')->after('today_sent');
            }
            if (! Schema::hasColumn('users', 'suspended')) {
                $table->boolean('suspended')->default(false)->index()->after('warmup_stage');
            }
            if (! Schema::hasColumn('users', 'suspension_reason')) {
                $table->text('suspension_reason')->nullable()->after('suspended');
            }
            if (! Schema::hasColumn('users', 'domain')) {
                $table->string('domain')->nullable()->index()->after('suspension_reason');
            }
            if (! Schema::hasColumn('users', 'domain_verified')) {
                $table->boolean('domain_verified')->default(false)->index()->after('domain');
            }
            if (! Schema::hasColumn('users', 'verification_token')) {
                $table->string('verification_token')->nullable()->after('domain_verified');
            }
            if (! Schema::hasColumn('users', 'bounce_count')) {
                $table->unsignedInteger('bounce_count')->default(0)->after('verification_token');
            }
            if (! Schema::hasColumn('users', 'complaint_count')) {
                $table->unsignedInteger('complaint_count')->default(0)->after('bounce_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            foreach (['complaint_count', 'bounce_count', 'verification_token', 'domain_verified', 'domain', 'suspension_reason', 'suspended', 'warmup_stage', 'today_sent', 'daily_limit'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
