<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PayPalController extends Controller
{
    public function token(): string
    {
        $res = Http::asForm()
            ->withBasicAuth(
                config('services.paypal.sandbox_client_id'),
                config('services.paypal.sandbox_secret')
            )
            ->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ])
            ->throw();

        return (string) $res->json('access_token');
    }

    public function createOrder(Request $request)
    {
        $token = $this->token();

        $res = Http::withToken($token)
            ->post('https://api-m.sandbox.paypal.com/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'custom_id' => (string) $request->user()->id,
                    'description' => 'InfiMal Pro',
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => '299.00',
                    ],
                ]],
            ])
            ->throw();

        return $res->json();
    }

    public function captureOrder(string $id)
    {
        $token = $this->token();

        $res = Http::withToken($token)
            ->post("https://api-m.sandbox.paypal.com/v2/checkout/orders/{$id}/capture")
            ->throw();

        return response()->json([
            'success' => true,
            'status' => $res->json('status'),
            'message' => 'Order captured. Access will be granted only after verified webhook processing.',
        ]);
    }
}
