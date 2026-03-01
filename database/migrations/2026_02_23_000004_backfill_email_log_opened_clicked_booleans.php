<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('email_logs', 'opened')) {
                $table->boolean('opened')->default(false)->after('status');
            }
            if (!Schema::hasColumn('email_logs', 'clicked')) {
                $table->boolean('clicked')->default(false)->after('opened');
            }
        });

        if (Schema::hasColumn('email_logs', 'opens_count')) {
            DB::table('email_logs')->where('opens_count', '>', 0)->update(['opened' => true]);
        }

        if (Schema::hasColumn('email_logs', 'clicks_count')) {
            DB::table('email_logs')->where('clicks_count', '>', 0)->update(['clicked' => true]);
        }
    }

    public function down(): void
    {
        // preserve data
    }
};
