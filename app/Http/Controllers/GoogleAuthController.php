<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
                    'google_password_set' => false,
                    'payment_status' => 'unpaid',
                    'is_paid' => false,
                    'license_status' => 'inactive',
                    'campaign_count' => 0,
                    'email_sent' => 0,
                    'onboarding_step' => 'google_setup_required',
                    'accepted_terms_at' => null,
                ]
            );

            $user->forceFill([
                'google_id' => $googleUser->getId(),
                'last_login_at' => now(),
            ])->save();

            Auth::login($user, true);

            if (! $user->google_password_set || ! $user->accepted_terms_at) {
                return redirect()->route('google.complete.prompt');
            }

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

    public function setupPrompt(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user, 401);

        if ($request->expectsJson()) {
            return response()->json([
                'requires_password_setup' => ! $user->google_password_set,
                'requires_terms_acceptance' => ! (bool) $user->accepted_terms_at,
            ]);
        }

        return redirect()->route('register')->with('info', 'Complete Google signup: set password and accept terms.');
    }

    public function completeSetup(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'accept_terms' => ['required', 'accepted'],
        ]);

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'google_password_set' => true,
            'accepted_terms_at' => now(),
            'onboarding_step' => 'payment_required',
        ])->save();

        return redirect()->route('payment')->with('success', 'Google signup completed. Continue to payment.');
    }
}
