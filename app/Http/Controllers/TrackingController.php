<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use App\Models\SMTPAccount;
use App\Models\Subscriber;
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
        $log = EmailLog::find($id);

        if ($log && ! $log->opened_at) {
            $log->forceFill(['opened' => true, 'opened_at' => now()])->save();
            $this->insertEvent($log, 'opened');
            $this->incrementCampaignCounter($log->campaign_id, 'total_opened');
            $this->reputation->recordEvent($log->id, 'opened');
        }

        return $this->pixel();
    }

    public function clickById(Request $request, int $id): RedirectResponse
    {
        $destination = (string) $request->query('url', '/');
        $log = EmailLog::find($id);

        if ($log && ! $log->clicked_at) {
            $log->forceFill(['clicked' => true, 'clicked_at' => now()])->save();
            $this->insertEvent($log, 'clicked', ['url' => $destination]);
            $this->incrementCampaignCounter($log->campaign_id, 'total_clicked');
            $this->reputation->recordEvent($log->id, 'clicked');
        }

        return redirect()->away($destination);
    }

    public function trackOpen(Request $request): Response
    {
        return $request->filled('id') ? $this->openById((int) $request->query('id')) : $this->pixel();
    }

    public function trackClick(Request $request): RedirectResponse
    {
        if ($request->filled('id')) {
            return $this->clickById($request, (int) $request->query('id'));
        }

        return redirect()->away((string) $request->query('url', '/'));
    }

    public function trackDelivered(Request $request): JsonResponse
    {
        $request->validate([
            'email_log_id' => ['nullable', 'integer'],
            'message_id' => ['nullable', 'string'],
        ]);

        $log = $this->resolveEmailLog($request);

        if (! $log) {
            return response()->json(['success' => false, 'message' => 'Missing delivery identifier.'], 422);
        }

        $log->forceFill(['status' => 'delivered'])->save();
        $this->insertEvent($log, 'delivered');
        $this->reputation->recordEvent($log->id, 'delivered');

        return response()->json(['success' => true]);
    }

    public function trackBounce(Request $request): JsonResponse
    {
        $request->validate([
            'email_log_id' => ['nullable', 'integer'],
            'message_id' => ['nullable', 'string'],
            'reason' => ['nullable', 'string', 'max:1000'],
            'type' => ['nullable', 'in:soft,hard'],
        ]);

        $log = $this->resolveEmailLog($request);

        if (! $log) {
            return response()->json(['success' => false, 'message' => 'Missing bounce identifier.'], 422);
        }

        $type = $request->input('type', 'hard');

        $log->forceFill([
            'status' => $type === 'soft' ? 'failed' : 'bounced',
            'bounced_at' => now(),
            'error_message' => $request->input('reason'),
        ])->save();

        $this->insertEvent($log, $type === 'soft' ? 'soft_bounce' : 'hard_bounce', ['reason' => $request->input('reason')]);
        $this->incrementCampaignCounter($log->campaign_id, 'total_bounced');

        if ($log->recipient_email && $type === 'hard') {
            Subscriber::where('user_id', $log->user_id)
                ->where('email', $log->recipient_email)
                ->update(['status' => 'suppressed']);
        }

        $this->reputation->recordEvent($log->id, $type === 'soft' ? 'soft_bounce' : 'hard_bounce', $request->input('reason'));
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

        $log = $this->resolveEmailLog($request);

        if (! $log) {
            return response()->json(['success' => false, 'message' => 'Missing complaint identifier.'], 422);
        }

        $log->forceFill([
            'status' => 'failed',
            'complained_at' => now(),
            'error_message' => $request->input('reason', 'Recipient complaint'),
        ])->save();

        $this->insertEvent($log, 'complaint', ['reason' => $request->input('reason')]);

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

        $log = $this->resolveEmailLog($request);

        if (! $log) {
            return response()->json(['success' => false, 'message' => 'Missing reply identifier.'], 422);
        }

        $log->forceFill(['replied_at' => now()])->save();
        $this->insertEvent($log, 'replied');
        $this->reputation->recordEvent($log->id, 'replied');

        return response()->json(['success' => true]);
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

    private function resolveEmailLog(Request $request): ?EmailLog
    {
        if ($request->filled('email_log_id')) {
            return EmailLog::find((int) $request->input('email_log_id'));
        }

        if ($request->filled('message_id')) {
            return EmailLog::where('message_id', (string) $request->input('message_id'))->first();
        }

        return null;
    }

    private function insertEvent(EmailLog $log, string $event, array $metadata = []): void
    {
        $history = is_array($log->metadata) ? $log->metadata : [];
        $history['events'] = $history['events'] ?? [];
        $history['events'][] = array_filter([
            'event' => $event,
            'at' => now()->toIso8601String(),
            'metadata' => $metadata ?: null,
        ]);

        $log->forceFill(['metadata' => $history])->save();
    }

    private function incrementCampaignCounter(?int $campaignId, string $column): void
    {
        if (! $campaignId || ! DB::getSchemaBuilder()->hasColumn('campaigns', $column)) {
            return;
        }

        DB::table('campaigns')->where('id', $campaignId)->increment($column);
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
            $smtp->update([
                'is_active' => false,
                'validation_status' => 'risky',
                'validation_message' => 'Auto-paused due to bounce/complaint anomaly',
            ]);
        }
    }

    private function pixel(): Response
    {
        $pixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO7Zr90AAAAASUVORK5CYII=');

        return response($pixel, 200)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
}
