<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\EmailLog;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        return response()->json($this->buildPayload());
    }

    public function campaigns()
    {
        return response()->json([
            'campaigns' => Campaign::where('user_id', Auth::id())
                ->latest()
                ->get(),
        ]);
    }

    public function subscribers()
    {
        return response()->json([
            'subscribers' => Subscriber::where('user_id', Auth::id())
                ->latest()
                ->limit(200)
                ->get(),
        ]);
    }

    public function reports()
    {
        return response()->json($this->buildPayload());
    }

    public function export()
    {
        return response()->json($this->buildPayload());
    }

    private function buildPayload(): array
    {
        $base = EmailLog::where('user_id', Auth::id());

        return [
            'total_sent' => (clone $base)->count(),
            'opens' => (clone $base)->where('opened', true)->count(),
            'clicks' => (clone $base)->where('clicked', true)->count(),
            'bounces' => (clone $base)->where('status', 'bounced')->count(),
            'recent_activity' => (clone $base)->latest()->limit(50)->get(),
        ];
    }
}
