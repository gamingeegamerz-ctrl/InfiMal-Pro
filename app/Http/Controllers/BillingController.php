<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\License;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        if (! $user->is_paid) {
            return redirect()->route('payment')->with('error', 'Complete payment first.');
        }

        if (! $user->is_verified) {
            return redirect()->route('otp.verify.form')->with('error', 'Please verify OTP to continue.');
        if ($user->hasPaidAccess()) {
            return redirect()->route('dashboard')->with('success', 'You already have active access.');
        }

        if ($user->hasPaid() && ! $user->otp_verified_at) {
            return redirect()->route('otp.verify.form')->with('error', 'Please verify OTP to complete activation.');
        }

        $license = License::where('user_id', $user->id)->latest()->first();
        $payments = Payment::where('user_id', $user->id)->latest()->get();
        $invoice = Invoice::where('user_id', $user->id)->latest()->first();

        return view('billing.index', [
            'user' => $user,
            'license' => $license,
            'payments' => $payments,
            'invoice' => $invoice,
            'transaction_id' => $user->transaction_id,
            'payment_date' => optional($user->paid_at)->toDateString(),
            'price' => $user->payment_amount ?? 299.00,
            'planName' => 'InfiMal Pro',
            'paypalClientId' => config('services.paypal.client_id'),
            'paypalMode' => config('services.paypal.mode', 'sandbox'),
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
