<?php

namespace App\Http\Controllers;

use App\Models\Bounce;
use App\Models\Complaint;
use App\Models\EmailClick;
use App\Models\EmailOpen;
use App\Models\SentEmail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class SesWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $sns = json_decode($request->getContent(), true);

        if (! is_array($sns)) {
            return response()->json(['message' => 'Invalid SNS payload'], 400);
        }

        if (($sns['Type'] ?? null) === 'SubscriptionConfirmation') {
            if (! empty($sns['SubscribeURL'])) {
                Http::timeout(10)->get($sns['SubscribeURL']);
            }

            return response()->json(['message' => 'SNS subscription confirmed']);
        }

        if (($sns['Type'] ?? null) !== 'Notification' || empty($sns['Message'])) {
            return response()->json(['message' => 'Ignored SNS message']);
        }

        $event = json_decode($sns['Message'], true);
        if (! is_array($event)) {
            return response()->json(['message' => 'Invalid SES message'], 400);
        }

        try {
            $this->storeEvent($event);
        } catch (\Throwable $e) {
            Log::error('Failed to store SES event', ['exception' => $e, 'event' => $event]);
            return response()->json(['message' => 'Failed to process SES event'], 500);
        }

        return response()->json(['message' => 'ok']);
    }

    private function storeEvent(array $event): void
    {
        $mail = $event['mail'] ?? [];
        $tags = $mail['tags'] ?? [];
        $userId = (int) (Arr::first($tags['user_id'] ?? []) ?: 0);
        $campaignId = (int) (Arr::first($tags['campaign_id'] ?? []) ?: 0) ?: null;
        $messageId = $mail['messageId'] ?? null;
        $recipient = $this->recipientEmail($event);
        $sentEmail = $this->findSentEmail($messageId, $recipient, $userId);
        $eventType = strtolower($event['eventType'] ?? $event['notificationType'] ?? '');

        if ($userId <= 0 || ! $recipient) {
            Log::warning('SES event missing required tags or recipient', compact('eventType', 'messageId', 'recipient', 'userId'));
            return;
        }

        match ($eventType) {
            'send', 'delivery' => SentEmail::updateOrCreate(
                ['message_id' => $messageId, 'recipient_email' => $recipient],
                [
                    'user_id' => $userId,
                    'campaign_id' => $campaignId,
                    'from_email' => $mail['source'] ?? 'unknown@example.com',
                    'subject' => (string) ($mail['commonHeaders']['subject'] ?? ''),
                    'status' => $eventType === 'delivery' ? 'delivered' : 'sent',
                    'sent_at' => $this->timestamp($mail['timestamp'] ?? null),
                ]
            ),
            'open' => EmailOpen::create([
                'user_id' => $userId,
                'campaign_id' => $campaignId,
                'sent_email_id' => $sentEmail?->id,
                'message_id' => $messageId,
                'recipient_email' => $recipient,
                'ip_address' => $event['open']['ipAddress'] ?? null,
                'user_agent' => $event['open']['userAgent'] ?? null,
                'opened_at' => $this->timestamp($event['open']['timestamp'] ?? null),
            ]),
            'click' => EmailClick::create([
                'user_id' => $userId,
                'campaign_id' => $campaignId,
                'sent_email_id' => $sentEmail?->id,
                'message_id' => $messageId,
                'recipient_email' => $recipient,
                'url' => $event['click']['link'] ?? '',
                'ip_address' => $event['click']['ipAddress'] ?? null,
                'user_agent' => $event['click']['userAgent'] ?? null,
                'clicked_at' => $this->timestamp($event['click']['timestamp'] ?? null),
            ]),
            'bounce' => $this->storeBounce($event, $userId, $campaignId, $messageId, $recipient, $sentEmail),
            'complaint' => $this->storeComplaint($event, $userId, $campaignId, $messageId, $recipient, $sentEmail),
            default => Log::info('Unhandled SES event type', ['event_type' => $eventType]),
        };
    }

    private function storeBounce(array $event, int $userId, ?int $campaignId, ?string $messageId, string $recipient, ?SentEmail $sentEmail): void
    {
        $bounce = $event['bounce'] ?? [];
        $recipientInfo = $bounce['bouncedRecipients'][0] ?? [];

        Bounce::create([
            'user_id' => $userId,
            'campaign_id' => $campaignId,
            'sent_email_id' => $sentEmail?->id,
            'message_id' => $messageId,
            'recipient_email' => $recipient,
            'bounce_type' => $bounce['bounceType'] ?? null,
            'bounce_sub_type' => $bounce['bounceSubType'] ?? null,
            'diagnostic_code' => $recipientInfo['diagnosticCode'] ?? null,
            'bounced_at' => $this->timestamp($bounce['timestamp'] ?? null),
        ]);

        $sentEmail?->update(['status' => 'bounced']);
        User::whereKey($userId)->increment('bounce_count');
    }

    private function storeComplaint(array $event, int $userId, ?int $campaignId, ?string $messageId, string $recipient, ?SentEmail $sentEmail): void
    {
        $complaint = $event['complaint'] ?? [];

        Complaint::create([
            'user_id' => $userId,
            'campaign_id' => $campaignId,
            'sent_email_id' => $sentEmail?->id,
            'message_id' => $messageId,
            'recipient_email' => $recipient,
            'complaint_feedback_type' => $complaint['complaintFeedbackType'] ?? null,
            'user_agent' => $complaint['userAgent'] ?? null,
            'complained_at' => $this->timestamp($complaint['timestamp'] ?? null),
        ]);

        $sentEmail?->update(['status' => 'complained']);
        User::whereKey($userId)->increment('complaint_count');
    }

    private function recipientEmail(array $event): ?string
    {
        return $event['bounce']['bouncedRecipients'][0]['emailAddress']
            ?? $event['complaint']['complainedRecipients'][0]['emailAddress']
            ?? $event['mail']['destination'][0]
            ?? null;
    }

    private function findSentEmail(?string $messageId, ?string $recipient, int $userId): ?SentEmail
    {
        return SentEmail::query()
            ->where('user_id', $userId)
            ->when($messageId, fn ($query) => $query->where('message_id', $messageId))
            ->when($recipient, fn ($query) => $query->where('recipient_email', $recipient))
            ->latest('id')
            ->first();
    }

    private function timestamp(?string $value): Carbon
    {
        return $value ? Carbon::parse($value) : now();
    }
}
