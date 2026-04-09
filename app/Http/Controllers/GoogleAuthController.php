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
        return Socialite::driver('google')
            ->scopes(['openid', 'email', 'profile'])
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

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
                    'onboarding_step' => 'google_profile_required',
                    'accepted_terms_at' => null,
                ]
            );

            $requiresGoogleOnboarding = ! $user->accepted_terms_at || $user->onboarding_step === 'google_profile_required';

            $user->forceFill([
                'google_id' => $googleUser->getId(),
                'last_login_at' => now(),
                'onboarding_step' => $requiresGoogleOnboarding ? 'google_profile_required' : ($user->onboarding_step ?: 'payment_required'),
            ])->save();

            Auth::login($user, true);

            if ($requiresGoogleOnboarding) {
                return redirect()->route('google.onboarding.form');
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

    public function onboardingForm(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->onboarding_step !== 'google_profile_required') {
            return redirect()->route('payment');
        }

        return response()->json([
            'message' => 'Complete Google signup with password and terms acceptance.',
            'required_fields' => ['name', 'password', 'password_confirmation', 'terms_accepted'],
            'next' => route('google.onboarding.complete'),
        ]);
    }

    public function completeOnboarding(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user, 401);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms_accepted' => ['required', 'accepted'],
        ]);

        $user->forceFill([
            'name' => $validated['name'],
            'password' => Hash::make($validated['password']),
            'accepted_terms_at' => now(),
            'onboarding_step' => 'payment_required',
        ])->save();

        $request->session()->put('onboarding_step', 'payment_required');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('payment'),
            ]);
        }

        return redirect()->route('payment')->with('success', 'Profile completed. Proceed to payment.');
    }
}
