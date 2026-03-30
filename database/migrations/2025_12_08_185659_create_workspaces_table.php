<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('workspaces')) {
            Schema::create('workspaces', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->string('slug')->unique();
                $table->json('settings')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
        
        // Add workspace_id to users table
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'workspace_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('workspace_id')->nullable()->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'workspace_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('workspace_id');
            });
        }
        Schema::dropIfExists('workspaces');
    }
};
