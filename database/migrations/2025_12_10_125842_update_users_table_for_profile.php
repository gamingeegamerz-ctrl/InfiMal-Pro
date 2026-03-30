<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            // Add missing columns
            $columnsToAdd = [
                'timezone' => ['string', 'default' => 'UTC'],
                'company' => ['string', 'nullable'],
                'phone' => ['string', 'nullable'],
                'address' => ['text', 'nullable'],
                'country' => ['string', 'nullable'],
                'avatar' => ['string', 'nullable'],
                'bio' => ['text', 'nullable'],
                'website' => ['string', 'nullable'],
                'preferences' => ['json', 'nullable'],
                'api_key' => ['string', 'nullable'],
                'two_factor_enabled' => ['boolean', 'default' => false],
                'last_login_at' => ['timestamp', 'nullable'],
                'last_login_ip' => ['string', 'nullable']
            ];
            
            foreach ($columnsToAdd as $column => $config) {
                if (!Schema::hasColumn('users', $column)) {
                    if ($config[0] === 'string') {
                        $table->string($column)->default($config['default'] ?? null)->nullable();
                    } elseif ($config[0] === 'text') {
                        $table->text($column)->nullable();
                    } elseif ($config[0] === 'json') {
                        $table->json($column)->nullable();
                    } elseif ($config[0] === 'boolean') {
                        $table->boolean($column)->default($config['default'] ?? false);
                    } elseif ($config[0] === 'timestamp') {
                        $table->timestamp($column)->nullable();
                    }
                }
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [
                'timezone', 'company', 'phone', 'address', 'country',
                'avatar', 'bio', 'website', 'preferences', 'api_key',
                'two_factor_enabled', 'last_login_at', 'last_login_ip'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
