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
        EmailLog::whereKey($id)->update(['opened' => true]);

        return $this->pixel();
    }

    public function clickById(Request $request, int $id): RedirectResponse
    {
        EmailLog::whereKey($id)->update(['clicked' => true]);

        $url = (string) $request->query('url', '/');

        return redirect()->away($url);
    }

    // Backward-compatible endpoints used by existing links/routes
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

        return redirect($request->query('url', '/'));
    }

    public function trackBounce(Request $request)
    {
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
