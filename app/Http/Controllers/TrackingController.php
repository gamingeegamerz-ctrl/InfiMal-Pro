<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TrackingController extends Controller
{
    public function openById(int $id): Response
    {
        EmailLog::whereKey($id)
            ->whereNull('opened_at')
            ->update(['opened' => true, 'opened_at' => now()]);

        return $this->pixel();
    }

    public function clickById(Request $request, int $id): RedirectResponse
    {
        EmailLog::whereKey($id)
            ->whereNull('clicked_at')
            ->update(['clicked' => true, 'clicked_at' => now()]);

        return redirect()->away((string) $request->query('url', '/'));
    }

    public function trackOpen(Request $request): Response
    {
        return $request->filled('id') ? $this->openById((int) $request->query('id')) : $this->pixel();
    }

    public function trackClick(Request $request): RedirectResponse
    {
        return $request->filled('id')
            ? $this->clickById($request, (int) $request->query('id'))
            : redirect((string) $request->query('url', '/'));
    }

    public function trackBounce(Request $request)
    {
        $request->validate(['message_id' => 'required|string']);

        EmailLog::where('message_id', $request->string('message_id'))
            ->whereNull('bounced_at')
            ->update(['status' => 'bounced', 'bounced_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function unsubscribe(Request $request)
    {
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
        $pixel = '<img src="' . url('/track/open/' . $logId . '.png') . '" width="1" height="1" style="display:none;" />';

        if (stripos($htmlContent, '</body>') !== false) {
            $htmlContent = str_ireplace('</body>', $pixel . '</body>', $htmlContent);
        } else {
            $htmlContent .= $pixel;
        }

        return preg_replace_callback('/<a\s+([^>]*href=["\']([^"\']+)["\'][^>]*)>/i', function ($matches) use ($logId) {
            $originalUrl = $matches[2];
            $trackingUrl = url('/track/click/' . $logId . '?url=' . urlencode($originalUrl));

            return str_replace($originalUrl, $trackingUrl, $matches[0]);
        }, $htmlContent) ?? $htmlContent;
    }
}
