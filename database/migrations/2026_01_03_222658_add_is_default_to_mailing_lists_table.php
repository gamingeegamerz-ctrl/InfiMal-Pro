<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('mailing_lists')) {
            return;
        }

        Schema::table('mailing_lists', function (Blueprint $table) {
            if (!Schema::hasColumn('mailing_lists', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('is_public');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('mailing_lists')) {
            return;
        }

        Schema::table('mailing_lists', function (Blueprint $table) {
            if (Schema::hasColumn('mailing_lists', 'is_default')) {
                $table->dropColumn('is_default');
            }
        });
    }
};
