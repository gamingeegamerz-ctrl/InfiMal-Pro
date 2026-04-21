<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
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

            $user = User::where('email', $googleUser->getEmail())->first();

            if (! $user) {
                $user = User::create([
                    'name' => $googleUser->getName() ?: 'Google User',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(bin2hex(random_bytes(32))),
                    'payment_status' => 'unpaid',
                    'is_paid' => false,
                    'is_verified' => false,
                    'onboarding_step' => 'payment_required',
                ]);
            } else {
                $user->forceFill([
                    'google_id' => $googleUser->getId(),
                ])->save();
            }

            $user->forceFill(['last_login_at' => now()])->save();

            Auth::login($user, true);

            return $this->redirectAfterAuthentication($user);
        } catch (\Throwable) {
            return redirect()->route('login')->with('error', 'Google login failed.');
        }
    }

    protected function redirectAfterAuthentication(User $user): RedirectResponse
    {
        if ($user->is_paid && $user->is_verified) {
            return redirect()->intended(route('dashboard'));
        }

        if (! $user->is_paid) {
            return redirect()->route('payment');
        }

        return redirect()->route('otp.verify.form');
    }

    public function onboardingForm(Request $request): RedirectResponse
    {
        return redirect()->route('payment');
    }

    public function completeOnboarding(Request $request): RedirectResponse
    {
        return redirect()->route('payment');
    }

    public function setupPrompt(Request $request): RedirectResponse
    {
        return redirect()->route('payment');
    }

    public function completeSetup(Request $request): RedirectResponse
    {
        return redirect()->route('payment');
    }
}
