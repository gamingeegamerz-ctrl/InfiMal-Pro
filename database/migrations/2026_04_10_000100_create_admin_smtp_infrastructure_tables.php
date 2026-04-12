<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_smtp_nodes', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('server_ip')->unique();
            $table->string('hostname');
            $table->json('supported_ports');
            $table->string('sending_domain');
            $table->decimal('reputation_score', 5, 2)->default(100);
            $table->enum('status', ['active', 'paused', 'dead'])->default('active');
            $table->unsignedInteger('daily_limit')->default(100000);
            $table->unsignedInteger('daily_sent')->default(0);
            $table->unsignedSmallInteger('last_port')->nullable();
            $table->timestamp('last_health_check_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['status', 'is_active']);
        });

        Schema::create('admin_ip_pool', function (Blueprint $table) {
            $table->id();
            $table->foreignId('node_id')->constrained('admin_smtp_nodes')->cascadeOnDelete();
            $table->ipAddress('ip_address')->unique();
            $table->decimal('reputation_score', 5, 2)->default(100);
            $table->decimal('success_rate', 5, 4)->default(1);
            $table->decimal('bounce_rate', 5, 4)->default(0);
            $table->unsignedInteger('daily_limit')->default(100000);
            $table->unsignedInteger('daily_sent')->default(0);
            $table->unsignedInteger('warmup_day')->default(1);
            $table->enum('status', ['active', 'paused', 'dead'])->default('active');
            $table->unsignedSmallInteger('last_port')->nullable();
            $table->timestamps();

            $table->index(['status', 'daily_sent']);
        });

        Schema::create('admin_warmup_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_ip_pool_id')->constrained('admin_ip_pool')->cascadeOnDelete();
            $table->string('sending_domain');
            $table->unsignedInteger('warmup_day');
            $table->unsignedInteger('target_volume');
            $table->unsignedInteger('actual_volume');
            $table->decimal('bounce_rate', 5, 4)->default(0);
            $table->decimal('complaint_rate', 5, 4)->default(0);
            $table->enum('status', ['within_limit', 'throttled'])->default('within_limit');
            $table->text('notes')->nullable();
            $table->date('logged_on');
            $table->timestamps();

            $table->index(['admin_ip_pool_id', 'logged_on']);
        });

        Schema::create('admin_reputation_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_ip_pool_id')->constrained('admin_ip_pool')->cascadeOnDelete();
            $table->string('sending_domain')->nullable();
            $table->string('provider')->default('global');
            $table->decimal('reputation_score', 5, 4);
            $table->decimal('success_rate', 5, 4);
            $table->decimal('bounce_rate', 5, 4);
            $table->decimal('complaint_rate', 5, 4)->default(0);
            $table->decimal('low_usage_factor', 5, 4);
            $table->decimal('composite_score', 6, 4);
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index(['provider', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_reputation_scores');
        Schema::dropIfExists('admin_warmup_logs');
        Schema::dropIfExists('admin_ip_pool');
        Schema::dropIfExists('admin_smtp_nodes');
    }
};
