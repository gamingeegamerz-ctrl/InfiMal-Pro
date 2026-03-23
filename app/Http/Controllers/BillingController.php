<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $license = License::where('user_id', $user->id)->latest()->first();
        $payments = Payment::where('user_id', $user->id)->latest()->get();

        return view('billing.index', [
            'user' => $user,
            'license' => $license,
            'payments' => $payments,
            'planName' => 'InfiMal Pro',
            'price' => 299.00,
            'features' => [
                'Unlimited email sending through your own SMTP accounts',
                'Campaign management and audience segmentation',
                'Open, click, and bounce analytics',
                'Per-user SMTP isolation and secure credential storage',
                'Lifetime access after verified one-time payment',
            ],
        ]);
    }
}
