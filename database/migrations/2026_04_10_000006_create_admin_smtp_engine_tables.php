<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_smtp_nodes', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->ipAddress('server_ip');
            $table->string('hostname');
            $table->json('supported_ports');
            $table->unsignedInteger('preferred_port')->default(587);
            $table->string('sending_domain');
            $table->decimal('reputation_score', 5, 4)->default(0.5);
            $table->enum('status', ['active', 'paused', 'dead'])->default('active');
            $table->unsignedInteger('daily_limit')->default(100000);
            $table->timestamps();

            $table->index(['status', 'sending_domain']);
        });

        Schema::create('admin_ip_pool', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('node_id')->constrained('admin_smtp_nodes')->cascadeOnDelete();
            $table->ipAddress('ip_address');
            $table->enum('status', ['active', 'paused', 'dead'])->default('active');
            $table->unsignedInteger('daily_limit')->default(50000);
            $table->unsignedInteger('sent_today')->default(0);
            $table->decimal('success_rate', 5, 4)->default(1);
            $table->decimal('bounce_rate', 5, 4)->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->unique(['node_id', 'ip_address']);
            $table->index(['status', 'sent_today']);
        });

        Schema::create('admin_warmup_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('ip_pool_id')->constrained('admin_ip_pool')->cascadeOnDelete();
            $table->string('sending_domain');
            $table->unsignedInteger('warmup_day');
            $table->unsignedInteger('target_volume');
            $table->unsignedInteger('actual_sent')->default(0);
            $table->decimal('bounce_rate', 5, 4)->default(0);
            $table->decimal('complaint_rate', 5, 4)->default(0);
            $table->string('status')->default('within_limit');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['ip_pool_id', 'sending_domain']);
        });

        Schema::create('admin_reputation_scores', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('ip_pool_id')->constrained('admin_ip_pool')->cascadeOnDelete();
            $table->string('sending_domain');
            $table->decimal('reputation_score', 5, 4);
            $table->decimal('success_rate', 5, 4);
            $table->decimal('bounce_rate', 5, 4)->default(0);
            $table->decimal('complaint_rate', 5, 4)->default(0);
            $table->decimal('deliverability_score', 5, 4);
            $table->timestamp('calculated_at');
            $table->timestamps();

            $table->index(['sending_domain', 'calculated_at']);
        });

        Schema::create('admin_email_jobs', function (Blueprint $table): void {
            $table->id();
            $table->string('batch_key')->nullable()->index();
            $table->string('to_email');
            $table->string('subject');
            $table->longText('html_body');
            $table->string('from_email');
            $table->string('from_name')->nullable();
            $table->enum('status', ['queued', 'processing', 'sent', 'failed'])->default('queued');
            $table->foreignId('node_id')->nullable()->constrained('admin_smtp_nodes')->nullOnDelete();
            $table->foreignId('ip_pool_id')->nullable()->constrained('admin_ip_pool')->nullOnDelete();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_email_jobs');
        Schema::dropIfExists('admin_reputation_scores');
        Schema::dropIfExists('admin_warmup_logs');
        Schema::dropIfExists('admin_ip_pool');
        Schema::dropIfExists('admin_smtp_nodes');
    }
};
