<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_smtp_reputations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('smtp_id')->constrained('smtps')->cascadeOnDelete();
            $table->unsignedInteger('success_count')->default(0);
            $table->unsignedInteger('bounce_count')->default(0);
            $table->unsignedInteger('complaint_count')->default(0);
            $table->decimal('score', 5, 2)->default(100);
            $table->timestamp('last_event_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'smtp_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_smtp_reputations');
    }
};
