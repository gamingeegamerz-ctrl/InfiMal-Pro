<?php

namespace App\Http\Controllers;

use App\Models\Bounce;
use App\Models\Campaign;
use App\Models\Complaint;
use App\Models\EmailClick;
use App\Models\EmailOpen;
use App\Models\SentEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class AnalyticsController extends Controller
{
    public function dashboard(): RedirectResponse
    {
        return redirect()->route('dashboard');
    }

    public function campaignStats(int $campaignId): JsonResponse
    {
        $userId = Auth::id();
        Campaign::where('user_id', $userId)->findOrFail($campaignId);

        return response()->json([
            'campaign_id' => $campaignId,
            'last_7_days' => $this->summary($userId, 7, $campaignId),
            'last_30_days' => $this->summary($userId, 30, $campaignId),
            'recent_opens' => EmailOpen::where('user_id', $userId)->where('campaign_id', $campaignId)->latest('opened_at')->limit(25)->get(),
            'recent_clicks' => EmailClick::where('user_id', $userId)->where('campaign_id', $campaignId)->latest('clicked_at')->limit(25)->get(),
        ]);
    }

    public function index(): RedirectResponse { return $this->dashboard(); }
    public function campaigns(): RedirectResponse { return $this->dashboard(); }
    public function subscribers(): RedirectResponse { return $this->dashboard(); }
    public function reports(): RedirectResponse { return $this->dashboard(); }

    public function export()
    {
        return response()->json([
            'last_7_days' => $this->summary(Auth::id(), 7),
            'last_30_days' => $this->summary(Auth::id(), 30),
        ]);
    }

    private function summary(int $userId, int $days, ?int $campaignId = null): array
    {
        $since = now()->subDays($days);
        $sent = SentEmail::where('user_id', $userId)
            ->when($campaignId, fn ($query) => $query->where('campaign_id', $campaignId))
            ->where('sent_at', '>=', $since)
            ->count();

        $uniqueOpens = EmailOpen::where('user_id', $userId)
            ->when($campaignId, fn ($query) => $query->where('campaign_id', $campaignId))
            ->where('opened_at', '>=', $since)
            ->distinct('recipient_email')
            ->count('recipient_email');

        $uniqueClicks = EmailClick::where('user_id', $userId)
            ->when($campaignId, fn ($query) => $query->where('campaign_id', $campaignId))
            ->where('clicked_at', '>=', $since)
            ->distinct('recipient_email')
            ->count('recipient_email');

        $bounces = Bounce::where('user_id', $userId)
            ->when($campaignId, fn ($query) => $query->where('campaign_id', $campaignId))
            ->where('bounced_at', '>=', $since)
            ->count();

        $complaints = Complaint::where('user_id', $userId)
            ->when($campaignId, fn ($query) => $query->where('campaign_id', $campaignId))
            ->where('complained_at', '>=', $since)
            ->count();

        return [
            'days' => $days,
            'total_sends' => $sent,
            'unique_opens' => $uniqueOpens,
            'unique_clicks' => $uniqueClicks,
            'bounces' => $bounces,
            'complaints' => $complaints,
            'bounce_rate' => $sent > 0 ? round(($bounces / $sent) * 100, 2) : 0.0,
            'complaint_rate' => $sent > 0 ? round(($complaints / $sent) * 100, 2) : 0.0,
            'open_rate' => $sent > 0 ? round(($uniqueOpens / $sent) * 100, 2) : 0.0,
            'click_rate' => $sent > 0 ? round(($uniqueClicks / $sent) * 100, 2) : 0.0,
        ];
    }
}
