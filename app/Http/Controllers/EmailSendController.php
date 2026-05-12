<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\LimitService;
use App\Services\EmailDispatcher;
use App\Models\License;
use App\Models\SMTPAccount;
use App\Services\EmailRateLimiter;
use App\Services\SesService;
use App\Services\SpamKeywordChecker;

class EmailSendController extends Controller
{
    public function __construct(
        protected LimitService $limitService,
        protected EmailRateLimiter $emailRateLimiter,
        protected SpamKeywordChecker $spamKeywordChecker,
        protected SesService $sesService,
    ) {
    }

    /**
     * Send emails (API endpoint). Supports Infimail SES payloads:
     * - from: sender address
     * - campaign_id: optional campaign ID
     * - emails: [{to, subject, body|html_body}]
     */
    public function send(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        if ($user->suspended) {
            return response()->json([
                'status' => 'error',
                'message' => $user->suspension_reason ?: 'Your sending is suspended. Please contact support.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'from' => 'required|email',
            'campaign_id' => 'nullable|integer|exists:campaigns,id',
            'emails' => 'required|array|min:1',
            'emails.*.to' => 'required|email',
            'emails.*.subject' => 'required|string|max:255',
            'emails.*.body' => 'required_without:emails.*.html_body|string',
            'emails.*.html_body' => 'required_without:emails.*.body|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $emails = $request->input('emails');
        $count = count($emails);

        foreach ($emails as $email) {
            $htmlBody = $email['html_body'] ?? $email['body'];
            if ($this->spamKeywordChecker->containsSuspiciousKeyword($email['subject'], $htmlBody)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send: Content contains suspicious keywords.',
                ], 422);
            }
        }

        if (! $this->emailRateLimiter->canSendNow($user, $count)) {
            foreach ($emails as $email) {
                $this->emailRateLimiter->queueForLater([
                    'user_id' => $user->id,
                    'from' => $request->input('from'),
                    'to' => $email['to'],
                    'subject' => $email['subject'],
                    'html_body' => $email['html_body'] ?? $email['body'],
                    'campaign_id' => $request->input('campaign_id'),
                ]);
            }

            return response()->json([
                'status' => 'queued',
                'message' => 'Your email is queued and will be sent shortly.',
                'queued' => $count,
            ], 202);
        }

        try {
            foreach ($emails as $email) {
                $this->sesService->sendEmail(
                    $request->input('from'),
                    $email['to'],
                    $email['subject'],
                    $email['html_body'] ?? $email['body'],
                    $user->id,
                    $request->integer('campaign_id') ?: null,
                );
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Emails sent successfully',
                'sent' => $count,
            ]);
        } catch (\Throwable $e) {
            report($e);

            foreach ($emails as $email) {
                $this->emailRateLimiter->queueForLater([
                    'user_id' => $user->id,
                    'from' => $request->input('from'),
                    'to' => $email['to'],
                    'subject' => $email['subject'],
                    'html_body' => $email['html_body'] ?? $email['body'],
                    'campaign_id' => $request->input('campaign_id'),
                ]);
            }

            return response()->json([
                'status' => 'queued',
                'message' => 'Your email is queued and will be sent shortly.',
                'queued' => $count,
            ], 202);
        }
    }

    /**
     * Health check
     */
    public function health()
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'Infimal SMTP'
        ]);
    }

    /**
     * Get SMTP credentials (for UI display)
     */
    public function smtpCredentials()
    {
        $user = Auth::user();

        $smtp = SMTPAccount::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$smtp) {
            return response()->json([
                'status' => 'error',
                'message' => 'SMTP not available'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'host' => env('POSTAL_SMTP_HOST'),
                'port' => env('POSTAL_SMTP_PORT'),
                'username' => $smtp->smtp_username
                // password intentionally not returned
            ]
        ]);
    }

    /**
     * Get current email limits
     */
    public function limits()
    {
        $user = Auth::user();

        $limit = $user->emailLimit;

        if (!$limit) {
            return response()->json([
                'status' => 'error',
                'message' => 'Limits not initialized'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'daily_limit' => $limit->daily_limit,
                'sent_today' => $limit->emails_sent_today,
                'reputation' => $limit->reputation_score,
                'blocked' => (bool) $limit->is_blocked
            ]
        ]);
    }
}
