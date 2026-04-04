<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName() ?: 'Google User',
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(bin2hex(random_bytes(32))),
                    'payment_status' => 'unpaid',
                    'is_paid' => false,
                    'license_status' => 'inactive',
                    'campaign_count' => 0,
                    'email_sent' => 0,
                    'accepted_terms_at' => now(),
                ]
            );

            $user->forceFill([
                'google_id' => $googleUser->getId(),
                'last_login_at' => now(),
            ])->save();

            Auth::login($user, true);

            if (! $user->hasPaid()) {
                return redirect()->route('payment')->with('info', 'Complete payment to continue setup.');
            }

            if (! $user->otp_verified_at) {
                return redirect()->route('otp.verify.form');
            }

            return redirect()->route('dashboard');
        } catch (\Throwable) {
            return redirect()->route('login')->with('error', 'Google login failed.');
        }
    }
}
