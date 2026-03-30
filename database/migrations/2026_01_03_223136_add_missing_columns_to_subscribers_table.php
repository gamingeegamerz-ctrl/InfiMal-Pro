<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('subscribers')) {
            return;
        }

        Schema::table('subscribers', function (Blueprint $table) {
            // Intentionally empty migration kept for timestamp compatibility.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('subscribers')) {
            return;
        }

        Schema::table('subscribers', function (Blueprint $table) {
            // Intentionally empty migration kept for timestamp compatibility.
        });
    }
};
