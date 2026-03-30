<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_paid')) {
                $table->boolean('is_paid')->default(false);
            }

            if (!Schema::hasColumn('users', 'license_key')) {
                $table->string('license_key')->nullable();
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_paid')) {
                $table->dropColumn('is_paid');
            }

            if (Schema::hasColumn('users', 'license_key')) {
                $table->dropColumn('license_key');
            }
        });
    }
};
