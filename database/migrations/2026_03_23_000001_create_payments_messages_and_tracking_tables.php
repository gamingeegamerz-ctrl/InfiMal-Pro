<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('payment_id')->unique();
                $table->string('plan')->default('InfiMal Pro');
                $table->decimal('amount', 10, 2);
                $table->string('currency', 3)->default('USD');
                $table->string('status')->default('pending');
                $table->string('payment_method')->default('paypal');
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('workspace_id')->nullable()->constrained()->nullOnDelete();
                $table->string('name');
                $table->string('subject');
                $table->longText('content');
                $table->string('type')->default('email');
                $table->string('category')->nullable();
                $table->boolean('is_template')->default(true);
                $table->unsignedInteger('used_count')->default(0);
                $table->json('variables')->nullable();
                $table->string('thumbnail')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }

        foreach (['opens', 'clicks', 'bounces'] as $tableName) {
            if (! Schema::hasTable($tableName)) {
                Schema::create($tableName, function (Blueprint $table) use ($tableName): void {
                    $table->id();
                    $table->foreignId('email_log_id')->nullable()->constrained('email_logs')->nullOnDelete();
                    $table->foreignId('campaign_id')->nullable()->constrained('campaigns')->nullOnDelete();
                    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                    if ($tableName === 'clicks') {
                        $table->text('url')->nullable();
                    }
                    if ($tableName === 'bounces') {
                        $table->text('reason')->nullable();
                    }
                    $table->timestamps();
                });
            }
        }

        Schema::table('licenses', function (Blueprint $table): void {
            if (! Schema::hasColumn('licenses', 'status')) {
                $table->string('status')->default('active')->after('duration_days');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bounces');
        Schema::dropIfExists('clicks');
        Schema::dropIfExists('opens');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('payments');
    }
};
