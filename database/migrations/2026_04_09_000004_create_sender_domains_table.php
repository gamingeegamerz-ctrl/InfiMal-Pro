<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sender_domains')) {
            return;
        }

        Schema::create('sender_domains', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('domain');
            $table->string('verification_token');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->json('dns_records')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'domain']);
            $table->index(['user_id', 'is_verified']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sender_domains');
    }
};
