<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AuditLogService
{
    public function record(string $eventType, string $message, array $context = [], string $severity = 'low', ?int $userId = null): void
    {
        DB::table('audit_logs')->insert([
            'user_id' => $userId,
            'event_type' => $eventType,
            'severity' => $severity,
            'message' => $message,
            'context' => json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'created_at' => now(),
        ]);
    }
}
