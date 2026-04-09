<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\LimitService;
use App\Services\EmailDispatcher;
use App\Models\License;
use App\Models\SMTPAccount;

class EmailSendController extends Controller
{
    protected LimitService $limitService;

    public function __construct(LimitService $limitService)
    {
        $this->limitService = $limitService;
    }

    /**
     * Send emails (API endpoint)
     */
    public function send(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        // License check
        $license = License::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$license) {
            return response()->json([
                'status' => 'error',
                'message' => 'License inactive or blocked'
            ], 403);
        }

        // SMTP check
        $smtp = SMTPAccount::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$smtp) {
            return response()->json([
                'status' => 'error',
                'message' => 'SMTP access disabled'
            ], 403);
        }

        // Validate input
        $validator = Validator::make($request->all(), [
            'emails' => 'required|array|min:1',
            'emails.*.to' => 'required|email',
            'emails.*.subject' => 'required|string|max:255',
            'emails.*.body' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $emails = $request->input('emails');
        $count  = count($emails);

        try {
            // LIMIT + SPIKE CHECK
            $this->limitService->canSend($user->id, $count);

            foreach ($emails as $email) {
                EmailDispatcher::dispatch([
                    'user_id' => $user->id,
                    'to' => $email['to'],
                    'subject' => $email['subject'],
                    'body' => $email['body'],
                ]);
            }

            $this->limitService->registerSend($user->id, $count);

            return response()->json([
                'status' => 'success',
                'message' => 'Emails queued successfully',
                'queued' => $count
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 429);
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
