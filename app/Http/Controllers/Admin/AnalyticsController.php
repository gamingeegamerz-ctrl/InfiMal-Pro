<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Services\AnalyticsService;

class AnalyticsController extends Controller
{
    private function ensureAdmin(): void
    {
        abort_unless(auth()->check() && (bool) auth()->user()->is_admin, 403);
    }

    public function users(AnalyticsService $analyticsService)
    {
        $this->ensureAdmin();
        return response()->json($analyticsService->adminPerUserStats());
    }

    public function userDetail(int $userId)
    {
        $this->ensureAdmin();
        return response()->json(
            EmailLog::where('user_id', $userId)
                ->latest()
                ->paginate(100)
        );
    }
}
