<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('subscribers')) return;

        Schema::table('subscribers', function (Blueprint $table): void {
            if (!Schema::hasColumn('subscribers', 'tags')) $table->json('tags')->nullable();
        });

        Schema::table('subscribers', function (Blueprint $table): void {
            $table->index(['user_id', 'email']);
            $table->index(['user_id', 'list_id', 'status']);
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('subscribers')) return;

        Schema::table('subscribers', function (Blueprint $table): void {
            if (Schema::hasColumn('subscribers', 'tags')) $table->dropColumn('tags');
        });
    }
};
