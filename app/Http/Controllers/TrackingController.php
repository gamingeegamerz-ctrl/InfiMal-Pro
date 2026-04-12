<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use App\Models\SMTPAccount;
use App\Models\Subscriber;
use App\Models\SMTPAccount;
use App\Services\EmailReputationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function __construct(private readonly EmailReputationService $reputation)
    {
    }

    public function openById(int $id): Response
    {
        $firstOpen = EmailLog::whereKey($id)
            ->whereNull('opened_at')
            ->update(['opened' => true, 'opened_at' => now()]) > 0;

        $log = EmailLog::find($id);

        if ($log && $firstOpen) {
            DB::table('opens')->insert([
                'email_log_id' => $log->id,
                'campaign_id' => $log->campaign_id,
                'user_id' => $log->user_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('campaigns')
                ->where('id', $log->campaign_id)
                ->increment('total_opened');

            $this->reputation->recordEvent($log->id, 'opened');
        }

        return $this->pixel();
    }

    public function clickById(Request $request, int $id): RedirectResponse
    {
        $firstClick = EmailLog::whereKey($id)
            ->whereNull('clicked_at')
            ->update(['clicked' => true, 'clicked_at' => now()]) > 0;

        $log = EmailLog::find($id);

        if ($log && $firstClick) {
            DB::table('clicks')->insert([
                'email_log_id' => $log->id,
                'campaign_id' => $log->campaign_id,
                'user_id' => $log->user_id,
                'url' => (string) $request->query('url', '/'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('campaigns')
                ->where('id', $log->campaign_id)
                ->increment('total_clicked');

            $this->reputation->recordEvent($log->id, 'clicked');
        }

        return redirect()->away((string) $request->query('url', '/'));
    }

    public function trackOpen(Request $request): Response
    {
        return $request->filled('id')
            ? $this->openById((int) $request->query('id'))
            : $this->pixel();
    }

    public function trackClick(Request $request): RedirectResponse
    {
        if ($request->filled('id')) {
            return $this->clickById($request, (int) $request->query('id'));
        }

        return redirect()->away((string) $request->query('url', '/'));
    }

    public function trackBounce(Request $request): JsonResponse
    {
        $log = null;

        if ($request->filled('email_log_id')) {
            $request->validate([
                'email_log_id' => ['required', 'integer'],
                'reason' => ['nullable', 'string', 'max:1000'],
                'type' => ['nullable', 'in:soft,hard'],
            ]);

            $log = EmailLog::findOrFail((int) $request->input('email_log_id'));
        } elseif ($request->filled('message_id')) {
            $request->validate([
                'message_id' => ['required', 'string'],
                'reason' => ['nullable', 'string', 'max:1000'],
                'type' => ['nullable', 'in:soft,hard'],
            ]);

            $log = EmailLog::where('message_id', (string) $request->input('message_id'))->firstOrFail();
        }

        if (! $log) {
            return response()->json(['success' => false, 'message' => 'Missing bounce identifier.'], 422);
        }

        $type = $request->input('type', 'hard');
        $eventType = $type === 'soft' ? 'soft_bounce' : 'hard_bounce';

        $log->update([
            'status' => $type === 'soft' ? 'failed' : 'bounced',
            'bounced_at' => now(),
            'error_message' => $request->input('reason'),
        ]);

        DB::table('bounces')->insert([
            'email_log_id' => $log->id,
            'campaign_id' => $log->campaign_id,
            'user_id' => $log->user_id,
            'reason' => $request->input('reason'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('campaigns')
            ->where('id', $log->campaign_id)
            ->increment('total_bounced');

        if ($log->recipient_email && $type === 'hard') {
            Subscriber::where('user_id', $log->user_id)
                ->where('email', $log->recipient_email)
                ->update(['status' => 'suppressed']);
        }

        $this->reputation->recordEvent($log->id, $eventType, $request->input('reason'));
        $this->autoPauseIfAnomaly($log->smtp_id);

        return response()->json(['success' => true]);
    }

    public function trackComplaint(Request $request): JsonResponse
    {
        $request->validate([
            'email_log_id' => ['nullable', 'integer'],
            'message_id' => ['nullable', 'string'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $log = $request->filled('email_log_id')
            ? EmailLog::findOrFail((int) $request->input('email_log_id'))
            : EmailLog::where('message_id', (string) $request->input('message_id'))->firstOrFail();

        $log->update([
            'status' => 'failed',
            'complained_at' => now(),
            'error_message' => $request->input('reason', 'Recipient complaint'),
        ]);

        $this->applyRealtimeReputationGuard($log->smtp_id, $log->campaign_id, 'bounce');

        if ($log->recipient_email) {
            Subscriber::where('user_id', $log->user_id)
                ->where('email', $log->recipient_email)
                ->update(['status' => 'suppressed']);
        }

        $this->reputation->recordEvent($log->id, 'complaint', $request->input('reason'));
        $this->autoPauseIfAnomaly($log->smtp_id);

        return response()->json(['success' => true]);
    }

    public function trackReply(Request $request): JsonResponse
    {
        $request->validate([
            'email_log_id' => ['nullable', 'integer'],
            'message_id' => ['nullable', 'string'],
        ]);

        $log = $request->filled('email_log_id')
            ? EmailLog::findOrFail((int) $request->input('email_log_id'))
            : EmailLog::where('message_id', (string) $request->input('message_id'))->firstOrFail();

        $log->update(['replied_at' => now()]);
        $this->reputation->recordEvent($log->id, 'replied');

        return response()->json(['success' => true]);
    }


    public function trackComplaint(Request $request): JsonResponse
    {
        $request->validate([
            'email_log_id' => ['nullable', 'integer'],
            'message_id' => ['nullable', 'string'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $log = $request->filled('email_log_id')
            ? EmailLog::findOrFail((int) $request->input('email_log_id'))
            : EmailLog::where('message_id', (string) $request->input('message_id'))->firstOrFail();

        $log->update([
            'status' => 'complaint',
            'error_message' => $request->input('reason'),
        ]);

        $this->applyRealtimeReputationGuard($log->smtp_id, $log->campaign_id, 'complaint');

        return response()->json(['success' => true]);
    }

    private function applyRealtimeReputationGuard(?int $smtpId, ?int $campaignId, string $event): void
    {
        if (! $smtpId) {
            return;
        }

        $penalty = $event === 'complaint' ? 10 : 3;
        SMTPAccount::where('id', $smtpId)->decrement('reputation_score', $penalty);

        $lastHour = EmailLog::where('smtp_id', $smtpId)
            ->where('created_at', '>=', now()->subHour())
            ->whereIn('status', ['bounced', 'complaint'])
            ->count();

        if ($lastHour >= 25) {
            SMTPAccount::where('id', $smtpId)->update(['is_active' => false]);
            if ($campaignId) {
                DB::table('campaigns')->where('id', $campaignId)->update(['status' => 'paused']);
            }
        }
    }

    public function unsubscribe(Request $request): Response
    {
        $email = (string) $request->query('email');
        $userId = $request->integer('user_id');

        if ($email && $userId) {
            Subscriber::where('user_id', $userId)
                ->where('email', $email)
                ->update([
                    'status' => 'unsubscribed',
                    'unsubscribed_at' => now(),
                ]);
        }

        return response('You have been unsubscribed.', 200);
    }

    private function autoPauseIfAnomaly(?int $smtpId): void
    {
        if (! $smtpId) {
            return;
        }

        $smtp = SMTPAccount::find($smtpId);
        if (! $smtp) {
            return;
        }

        if ((float) $smtp->bounce_rate >= 0.08 || (float) $smtp->complaint_rate >= 0.005) {
            $smtp->update(['is_active' => false, 'validation_status' => 'risky', 'validation_message' => 'Auto-paused due to bounce/complaint anomaly']);
        }
    }

    private function pixel(): Response
    {
        $pixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO7Zr90AAAAASUVORK5CYII=');

        return response($pixel, 200)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public static function processEmailContent(string $htmlContent, int $logId): string
    {
        $pixel = '<img src="' . url('/track/open/' . $logId . '.png') . '" width="1" height="1" style="display:none;" alt="" />';

        if (stripos($htmlContent, '</body>') !== false) {
            $htmlContent = str_ireplace('</body>', $pixel . '</body>', $htmlContent);
        } else {
            $htmlContent .= $pixel;
        }

        return preg_replace_callback('/<a\s+([^>]*href=["\']([^"\']+)["\'][^>]*)>/i', function (array $matches) use ($logId): string {
            $originalUrl = $matches[2];
            $trackingUrl = url('/track/click/' . $logId . '?url=' . urlencode($originalUrl));

            return str_replace($originalUrl, $trackingUrl, $matches[0]);
        }, $htmlContent) ?? $htmlContent;
    }
}
