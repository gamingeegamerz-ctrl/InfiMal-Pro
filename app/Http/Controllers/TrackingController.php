<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function openById(int $id): Response
    {
        EmailLog::whereKey($id)
            ->whereNull('opened_at')
            ->update(['opened' => true, 'opened_at' => now()]);

        // Additional tracking data from codex branch
        $log = EmailLog::find($id);
        if ($log) {
            DB::table('opens')->insertOrIgnore([
                'email_log_id' => $log->id,
                'campaign_id' => $log->campaign_id,
                'user_id' => $log->user_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('campaigns')->where('id', $log->campaign_id)->increment('total_opened');
        }

        return $this->pixel();
    }

    public function clickById(Request $request, int $id): RedirectResponse
    {
        EmailLog::whereKey($id)
            ->whereNull('clicked_at')
            ->update(['clicked' => true, 'clicked_at' => now()]);

        // Additional tracking data from codex branch
        $log = EmailLog::find($id);
        if ($log) {
            DB::table('clicks')->insert([
                'email_log_id' => $log->id,
                'campaign_id' => $log->campaign_id,
                'user_id' => $log->user_id,
                'url' => (string) $request->query('url', '/'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('campaigns')->where('id', $log->campaign_id)->increment('total_clicked');
        }

        return redirect()->away((string) $request->query('url', '/'));
    }

    public function trackOpen(Request $request): Response
    {
        if ($request->filled('id')) {
            return $this->openById((int) $request->query('id'));
        }
        
        return $this->pixel();
    }

    public function trackClick(Request $request): RedirectResponse
    {
        if ($request->filled('id')) {
            return $this->clickById($request, (int) $request->query('id'));
        }
        
        return redirect((string) $request->query('url', '/'));
    }

    public function trackBounce(Request $request)
    {
        // Handle bounce from main branch style
        if ($request->has('message_id')) {
            $request->validate(['message_id' => 'required|string']);

            EmailLog::where('message_id', $request->string('message_id'))
                ->whereNull('bounced_at')
                ->update(['status' => 'bounced', 'bounced_at' => now()]);
        }
        
        // Handle bounce from codex branch style
        if ($request->has('email_log_id')) {
            $validated = $request->validate([
                'email_log_id' => ['required', 'integer'],
                'reason' => ['nullable', 'string', 'max:1000'],
            ]);

            $log = EmailLog::findOrFail($validated['email_log_id']);
            $log->update(['status' => 'bounced', 'error_message' => $validated['reason'] ?? null]);

            DB::table('bounces')->insert([
                'email_log_id' => $log->id,
                'campaign_id' => $log->campaign_id,
                'user_id' => $log->user_id,
                'reason' => $validated['reason'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('campaigns')->where('id', $log->campaign_id)->increment('total_bounced');

            if ($log->recipient_email) {
                Subscriber::where('user_id', $log->user_id)->where('email', $log->recipient_email)->update(['status' => 'bounced']);
            }
        }

        return response()->json(['success' => true]);
    }

    public function unsubscribe(Request $request): Response
    {
        $email = (string) $request->query('email');
        $userId = $request->integer('user_id');

        if ($email && $userId) {
            Subscriber::where('user_id', $userId)->where('email', $email)->update([
                'status' => 'unsubscribed',
                'unsubscribed_at' => now(),
            ]);
        }

        return response('You have been unsubscribed.', 200);
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
            $htmlContent = str_ireplace('</body>', $pixel.'</body>', $htmlContent);
        } else {
            $htmlContent .= $pixel;
        }

        return preg_replace_callback('/<a\s+([^>]*href=["\']([^"\']+)["\'][^>]*)>/i', function ($matches) use ($logId) {
            $originalUrl = $matches[2];
            $trackingUrl = url('/track/click/'.$logId.'?url='.urlencode($originalUrl));

            return str_replace($originalUrl, $trackingUrl, $matches[0]);
        }, $htmlContent) ?? $htmlContent;
    }
}