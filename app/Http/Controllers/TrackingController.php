<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function trackOpenById(int $id)
    {
        EmailLog::where('id', $id)->update(['opened' => true]);

        return $this->pixel();
    }

    public function trackClickById(Request $request, int $id)
    {
        EmailLog::where('id', $id)->update(['clicked' => true]);

        $url = $request->query('url', '/');

        return redirect()->away($url);
    }

    // Backward compatibility with existing query-string links
    public function trackOpen(Request $request)
    {
        if ($request->query('id')) {
            return $this->trackOpenById((int) $request->query('id'));
        }

        return $this->pixel();
    }

    public function trackClick(Request $request)
    {
        if ($request->query('id')) {
            return $this->trackClickById($request, (int) $request->query('id'));
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

    private function pixel()
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
