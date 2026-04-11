<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('config_change_logs')) {
            Schema::create('config_change_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('config_key');
                $table->json('old_value')->nullable();
                $table->json('new_value');
                $table->string('status')->default('applied');
                $table->text('reason')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->index(['config_key', 'created_at'], 'config_change_logs_key_time_idx');
            });
        }

        if (! Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('event_type');
                $table->string('severity')->default('low');
                $table->text('message');
                $table->json('context')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->index(['event_type', 'created_at'], 'audit_logs_type_time_idx');
                $table->index(['severity', 'created_at'], 'audit_logs_severity_time_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('config_change_logs');
    }
};
