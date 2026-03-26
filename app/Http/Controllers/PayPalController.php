<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PayPalController extends Controller
{
    private function token()
    {
        $res = Http::asForm()
            ->withBasicAuth(
                config('services.paypal.sandbox_client_id'),
                config('services.paypal.sandbox_secret')
            )
            ->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
                'grant_type' => 'client_credentials'
            ]);

        return $res['access_token'];
    }

    public function createOrder()
    {
        $token = $this->token();

        $res = Http::withToken($token)->post(
            'https://api-m.sandbox.paypal.com/v2/checkout/orders',
            [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => '299.00'
                    ]
                ]]
            ]
        );

        return $res->json();
    }

    public function captureOrder($id)
    {
        $token = $this->token();

        Http::withToken($token)
            ->post("https://api-m.sandbox.paypal.com/v2/checkout/orders/$id/capture");

        auth()->user()->update([
            'is_pro' => 1,
            'license_key' => 'INFIMAL-' . strtoupper(Str::random(16))
        ]);

        return response()->json(['success' => true]);
    }
}