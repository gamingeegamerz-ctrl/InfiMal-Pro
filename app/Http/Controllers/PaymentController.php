<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    private const PRICE = '299.00';
    private const PRODUCT = 'InfiMal Pro';

    public function createOrder(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        $accessToken = $this->paypalToken();
        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($this->paypalBaseUrl().'/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => 'user-'.$user->id,
                    'custom_id' => (string) $user->id,
                    'description' => self::PRODUCT,
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => self::PRICE,
                    ],
                ]],
                'application_context' => [
                    'brand_name' => config('app.name', 'InfiMal'),
                    'landing_page' => 'LOGIN',
                    'user_action' => 'PAY_NOW',
                    'return_url' => route('payment.success'),
                    'cancel_url' => route('billing.cancel'),
                ],
            ]);

        abort_unless($response->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, $response->json('message', 'Unable to create PayPal order.'));

        $payload = $response->json();
        $approvalUrl = collect($payload['links'] ?? [])->firstWhere('rel', 'approve')['href'] ?? null;
        abort_unless($approvalUrl, Response::HTTP_UNPROCESSABLE_ENTITY, 'PayPal approval URL missing.');

        Payment::updateOrCreate(
            ['payment_id' => $payload['id']],
            [
                'user_id' => $user->id,
                'plan' => self::PRODUCT,
                'amount' => 299.00,
                'currency' => 'USD',
                'status' => 'pending',
                'payment_method' => 'paypal',
                'metadata' => [
                    'create_order' => $payload,
                    'approval_url' => $approvalUrl,
                ],
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $payload['id'],
                'approval_url' => $approvalUrl,
            ]);
        }

        return redirect()->away($approvalUrl);
    }

    public function success(Request $request): RedirectResponse
    {
        $user = $request->user();
        $orderId = (string) ($request->query('token') ?: $request->query('order_id'));
        abort_if($orderId === '', Response::HTTP_UNPROCESSABLE_ENTITY, 'Missing PayPal order reference.');

        $payment = Payment::where('payment_id', $orderId)->where('user_id', $user->id)->firstOrFail();

        if ($payment->status !== 'completed') {
            $accessToken = $this->paypalToken();
            $orderResponse = Http::withToken($accessToken)
                ->acceptJson()
                ->get($this->paypalBaseUrl().'/v2/checkout/orders/'.$orderId);

            abort_unless($orderResponse->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Unable to verify PayPal order.');

            $order = $orderResponse->json();
            abort_unless(
                data_get($order, 'purchase_units.0.custom_id') === (string) $user->id
                && data_get($order, 'purchase_units.0.amount.value') === self::PRICE,
                Response::HTTP_FORBIDDEN,
                'Payment verification failed.'
            );

            $captureResponse = Http::withToken($accessToken)
                ->acceptJson()
                ->post($this->paypalBaseUrl().'/v2/checkout/orders/'.$orderId.'/capture');

            abort_unless($captureResponse->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, $captureResponse->json('message', 'Unable to capture PayPal payment.'));

            $this->finalizeSuccessfulPayment($user, $orderId, [
                'verify_order' => $order,
                'capture_order' => $captureResponse->json(),
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Payment verified successfully. Your InfiMal Pro license is now active.');
    }

    public function cancel(): RedirectResponse
    {
        return redirect()->route('billing')->with('error', 'Payment was cancelled. Your account remains unpaid until PayPal confirms a successful payment.');
    }

    public function webhook(Request $request): Response
    {
        if (! $this->verifyWebhook($request)) {
            return response('invalid', 422);
        }

        $resource = $request->input('resource', []);
        $orderId = $resource['supplementary_data']['related_ids']['order_id'] ?? $resource['id'] ?? null;
        $customId = data_get($resource, 'purchase_units.0.custom_id') ?? data_get($resource, 'custom_id');
        $user = User::find($customId);

        if ($user && $orderId) {
            $this->finalizeSuccessfulPayment($user, $orderId, $request->all());
        }

        return response('ok', 200);
    }

    private function finalizeSuccessfulPayment(User $user, string $orderId, array $payload): void
    {
        DB::transaction(function () use ($user, $orderId, $payload): void {
            $payment = Payment::updateOrCreate(
                ['payment_id' => $orderId],
                [
                    'user_id' => $user->id,
                    'plan' => self::PRODUCT,
                    'amount' => 299.00,
                    'currency' => 'USD',
                    'status' => 'completed',
                    'payment_method' => 'paypal',
                    'metadata' => $payload,
                ]
            );

            $license = License::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'license_key' => License::generateLicenseKey(),
                    'plan_type' => self::PRODUCT,
                    'price' => 299.00,
                    'duration_days' => 0,
                    'status' => 'active',
                    'is_active' => true,
                    'is_lifetime' => true,
                    'expires_at' => null,
                    'features' => [
                        'campaigns', 'subscribers', 'smtp', 'analytics', 'messages',
                    ],
                ]
            );

            $user->forceFill([
                'is_paid' => true,
                'payment_status' => 'paid',
                'plan_name' => self::PRODUCT,
                'paid_at' => now(),
                'payment_date' => now(),
                'payment_amount' => 299.00,
                'transaction_id' => $orderId,
                'license_key' => $license->license_key,
                'license_status' => 'active',
                'remember_token' => Str::random(60),
            ])->save();

            if (Schema::hasTable('sessions')) {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }

            Auth::loginUsingId($user->id);
            session()->regenerate();
            $payment->refresh();
        });
    }

    private function paypalToken(): string
    {
        $response = Http::asForm()
            ->withBasicAuth(config('services.paypal.client_id'), config('services.paypal.secret'))
            ->post($this->paypalBaseUrl().'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        abort_unless($response->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, 'PayPal authentication failed.');

        return (string) $response->json('access_token');
    }

    private function verifyWebhook(Request $request): bool
    {
        $webhookId = config('services.paypal.webhook_id');
        if (! $webhookId) {
            return false;
        }

        $response = Http::withToken($this->paypalToken())
            ->acceptJson()
            ->post($this->paypalBaseUrl().'/v1/notifications/verify-webhook-signature', [
                'auth_algo' => $request->header('PAYPAL-AUTH-ALGO'),
                'cert_url' => $request->header('PAYPAL-CERT-URL'),
                'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
                'transmission_sig' => $request->header('PAYPAL-TRANSMISSION-SIG'),
                'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
                'webhook_id' => $webhookId,
                'webhook_event' => $request->all(),
            ]);

        return $response->successful() && $response->json('verification_status') === 'SUCCESS';
    }

    private function paypalBaseUrl(): string
    {
        return config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }
}
