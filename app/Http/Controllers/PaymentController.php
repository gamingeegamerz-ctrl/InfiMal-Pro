<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function webhook(Request $request)
    {
        if (($request->alert_name ?? '') !== 'payment_succeeded') {
            return response('Ignored', 200);
        }

        $data = json_decode($request->passthrough, true);

        $user = User::find($data['user_id'] ?? null);
        if (!$user) {
            return response('User not found', 404);
        }

        $user->update([
            'payment_status' => 'paid',
            'license_key' => 'INFIMAL-' . strtoupper(Str::random(20)),
        ]);

        return response('OK', 200);
    }
}

