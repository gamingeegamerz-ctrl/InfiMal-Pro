<?php

namespace App\Http\Controllers;

use App\Models\SMTPAccount;
use App\Services\ReputationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostalWebhookController extends Controller
{
    public function __construct(private readonly ReputationService $reputationService)
    {
    }

    public function handle(Request $request)
    {
        $event = (string) $request->input('event');
        $payload = $request->input('payload', []);

        if (! isset($payload['credential_id'])) {
            return response()->json(['ignored' => true]);
        }

        $smtp = SMTPAccount::find($payload['credential_id']);
        if (! $smtp) {
            return response()->json(['ignored' => true]);
        }

        $penalty = match ($event) {
            'MessageHardBounce' => 10,
            'MessageSpamComplaint' => 25,
            'MessageSoftBounce' => 3,
            default => 0,
        };

        if ($penalty === 0) {
            return response()->json(['ignored' => true]);
        }

        $smtp->reputation_score = max(0, ((int) $smtp->reputation_score) - $penalty);
        if ($smtp->reputation_score < 40) {
            $smtp->is_active = false;
        }
        $smtp->save();

        if ($event === 'MessageSpamComplaint') {
            $this->reputationService->applyComplaintPenalty($smtp->id);
        }
        if (str_contains($event, 'Bounce')) {
            $this->reputationService->applyBouncePenalty($smtp->id);
        }

        Log::info('Postal webhook processed', [
            'event' => $event,
            'smtp_id' => $smtp->id,
            'new_score' => $smtp->reputation_score,
            'disabled' => ! $smtp->is_active,
        ]);

        return response()->json([
            'status' => 'processed',
            'event' => $event,
            'smtp_id' => $smtp->id,
            'reputation' => $smtp->reputation_score,
        ]);
    }
}
