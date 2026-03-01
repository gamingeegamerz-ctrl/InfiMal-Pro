<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('smtps', function (Blueprint $table) {
            if (!Schema::hasColumn('smtps', 'name')) {
                $table->string('name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('smtps', 'encryption')) {
                $table->enum('encryption', ['tls', 'ssl', 'none'])->default('tls')->after('smtp_password');
            }
            if (!Schema::hasColumn('smtps', 'from_email')) {
                $table->string('from_email')->nullable()->after('encryption');
            }
            if (!Schema::hasColumn('smtps', 'from_name')) {
                $table->string('from_name')->nullable()->after('from_email');
            }
            if (!Schema::hasColumn('smtps', 'daily_limit')) {
                $table->unsignedInteger('daily_limit')->default(500)->after('from_name');
            }
            if (!Schema::hasColumn('smtps', 'per_minute_limit')) {
                $table->unsignedInteger('per_minute_limit')->default(30)->after('daily_limit');
            }
            if (!Schema::hasColumn('smtps', 'warmup_enabled')) {
                $table->boolean('warmup_enabled')->default(true)->after('per_minute_limit');
            }
            if (!Schema::hasColumn('smtps', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('is_active');
            }
        });

        if (!Schema::hasTable('smtp_settings')) {
            return;
        }

        DB::table('smtp_settings')->orderBy('id')->chunkById(200, function ($rows): void {
            foreach ($rows as $row) {
                $existing = DB::table('smtps')
                    ->where('user_id', $row->user_id)
                    ->where('smtp_host', $row->host)
                    ->where('smtp_port', $row->port)
                    ->where('smtp_username', $row->username)
                    ->first();

                if ($existing) {
                    continue;
                }

                DB::table('smtps')->insert([
                    'user_id' => $row->user_id,
                    'name' => $row->name,
                    'smtp_host' => $row->host,
                    'smtp_port' => $row->port,
                    'smtp_username' => $row->username,
                    'smtp_password' => $row->password ? Crypt::encryptString($row->password) : null,
                    'encryption' => in_array($row->encryption, ['tls', 'ssl', 'none'], true) ? $row->encryption : 'tls',
                    'from_email' => $row->from_address,
                    'from_name' => $row->from_name,
                    'daily_limit' => (int) ($row->daily_limit ?? 500),
                    'per_minute_limit' => 30,
                    'warmup_enabled' => true,
                    'is_default' => false,
                    'is_active' => (bool) ($row->is_active ?? true),
                    'sent_today' => (int) ($row->sent_today ?? 0),
                    'last_used_at' => $row->last_used_at,
                    'created_at' => $row->created_at ?? now(),
                    'updated_at' => $row->updated_at ?? now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        // non-destructive
    }
};
