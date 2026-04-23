<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->is_paid) {
            return redirect()->route('payment')->with('error', 'Complete payment first.');
        }

        if (! $user->is_verified) {
            return redirect()->route('otp.verify.form')->with('error', 'Please verify OTP to continue.');
        }

        return redirect()->route('dashboard');
    }
}
