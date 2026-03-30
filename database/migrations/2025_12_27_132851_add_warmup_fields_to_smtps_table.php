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
        if (!Schema::hasTable('smtps')) {
            return;
        }

        Schema::table('smtps', function (Blueprint $table) {
            // Warmup / hourly control
            if (!Schema::hasColumn('smtps', 'hourly_sent')) {
                $table->integer('hourly_sent')->default(0)->after('sent_today');
            }

            if (!Schema::hasColumn('smtps', 'last_hour_at')) {
                $table->timestamp('last_hour_at')->nullable()->after('hourly_sent');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('smtps')) {
            return;
        }

        Schema::table('smtps', function (Blueprint $table) {
            if (Schema::hasColumn('smtps', 'hourly_sent')) {
                $table->dropColumn('hourly_sent');
            }

            if (Schema::hasColumn('smtps', 'last_hour_at')) {
                $table->dropColumn('last_hour_at');
            }
        });
    }
};
