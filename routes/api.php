<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmailSendController;
use App\Http\Controllers\PostalWebhookController;
use App\Http\Controllers\PaddleWebhookController;
use App\Http\Controllers\SenderDomainController;

use App\Models\SMTPAccount;
use App\Models\EmailLimit;
use App\Models\License;

/*
|--------------------------------------------------------------------------
| PUBLIC WEBHOOKS (NO AUTH)
|--------------------------------------------------------------------------
*/

// Paddle webhook
Route::post('/webhook/paddle', [PaddleWebhookController::class, 'handle'])
    ->name('paddle.webhook');

// Postal webhook (IMPORTANT)
Route::post('/webhooks/postal', [PostalWebhookController::class, 'handle'])
    ->name('postal.webhook');

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'Infimal SMTP',
        'timestamp' => now(),
    ]);
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (SANCTUM)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // SMTP credentials
    Route::get('/smtp/credentials', function (Request $request) {
        $user = $request->user();

        $license = License::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$license) {
            return response()->json(['error' => 'No active license found'], 403);
        }

        $smtp = SMTPAccount::where('user_id', $user->id)->first();

        if (!$smtp || !$smtp->is_active) {
            return response()->json(['error' => 'SMTP account not active'], 403);
        }

        return response()->json([
            'smtp_host' => $smtp->smtp_host,
            'smtp_port' => $smtp->smtp_port,
            'smtp_username' => $smtp->smtp_username,
            'smtp_password' => $smtp->smtp_password,
            'created_at' => $smtp->created_at,
        ]);
    });

    // SMTP test
    Route::post('/smtp/test', function (Request $request) {
        $smtp = SMTPAccount::where('user_id', $request->user()->id)->first();

        if (!$smtp) {
            return response()->json(['error' => 'SMTP not configured'], 404);
        }

        try {
            $transport = new \Swift_SmtpTransport(
                $smtp->smtp_host,
                $smtp->smtp_port,
                'tls'
            );

            $transport->setUsername($smtp->smtp_username);
            $transport->setPassword($smtp->smtp_password);

            (new \Swift_Mailer($transport))->getTransport()->start();

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    });

    // Email sending
    Route::post('/emails/send', [EmailSendController::class, 'send']);


    Route::get('/domains', [SenderDomainController::class, 'index']);
    Route::post('/domains', [SenderDomainController::class, 'store']);
    Route::post('/domains/{domain}/verify', [SenderDomainController::class, 'verify']);
    Route::delete('/domains/{domain}', [SenderDomainController::class, 'destroy']);

    // Limits
    Route::get('/limits', function (Request $request) {
        $limit = EmailLimit::firstOrCreate(
            ['user_id' => $request->user()->id],
            [
                'daily_limit' => 1000000,
                'emails_sent_today' => 0,
                'reputation_score' => 100,
                'violation_count' => 0,
                'is_blocked' => false,
            ]
        );

        return response()->json([
            'daily_limit' => $limit->daily_limit,
            'sent_today' => $limit->emails_sent_today,
            'remaining' => max(0, $limit->daily_limit - $limit->emails_sent_today),
            'reputation' => $limit->reputation_score,
            'blocked' => $limit->is_blocked,
        ]);
    });

    // Stats
    Route::get('/stats', function (Request $request) {
        $logs = \App\Models\EmailLog::where('user_id', $request->user()->id)
            ->latest()
            ->limit(30)
            ->get();

        return response()->json([
            'total_sent' => $logs->sum('sent_count'),
            'bounces' => $logs->sum('bounce_count'),
            'spam' => $logs->sum('spam_complaints'),
        ]);
    });

    // License
    Route::get('/license', function (Request $request) {
        $license = License::where('user_id', $request->user()->id)->first();

        return response()->json([
            'has_license' => (bool) $license,
            'license' => $license,
        ]);
    });
});

/*
|--------------------------------------------------------------------------
| RATE LIMITED API VERSION
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'throttle:60,1'])
    ->prefix('v1')
    ->group(function () {
        Route::post('/emails/send', [EmailSendController::class, 'send']);
    });
