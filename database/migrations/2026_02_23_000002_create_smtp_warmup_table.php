<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('smtp_warmup', function (Blueprint $table) {
            $table->id();
            $table->foreignId('smtp_id')->constrained('smtps')->cascadeOnDelete();
            $table->unsignedInteger('warmup_day');
            $table->unsignedInteger('daily_limit');
            $table->timestamps();

            $table->unique(['smtp_id', 'warmup_day']);
            $table->index(['smtp_id', 'warmup_day']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smtp_warmup');
    }
};
