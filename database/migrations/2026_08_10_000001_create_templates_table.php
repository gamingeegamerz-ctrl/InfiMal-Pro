<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('templates')) {
            if (!Schema::hasColumn('templates', 'user_id')) {
                Schema::table('templates', function (Blueprint $table): void {
                    $table->foreignId('user_id')->nullable()->after('id')->index();
                });
            }

            if (!Schema::hasColumn('templates', 'deleted_at')) {
                Schema::table('templates', function (Blueprint $table): void {
                    $table->softDeletes();
                });
            }

            return;
        }

        Schema::create('templates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('subject');
            $table->longText('content');
            $table->string('type')->default('email');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
