<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('workspaces')) {
            return;
        }

        Schema::table('workspaces', function (Blueprint $table) {
            if (!Schema::hasColumn('workspaces', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('workspaces')) {
            return;
        }

        Schema::table('workspaces', function (Blueprint $table) {
            if (Schema::hasColumn('workspaces', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
