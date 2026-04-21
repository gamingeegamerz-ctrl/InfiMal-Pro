<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            Log::channel('security')->warning('Login failed', [
                'email' => $credentials['email'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->withErrors(['email' => 'Invalid credentials provided.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        $user = $request->user();

        $user->forceFill(['last_login_at' => now()])->save();

        Log::channel('security')->info('Login succeeded', ['user_id' => $user->id, 'ip' => $request->ip()]);

        return $this->authenticated($request, $user);
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'payment_status' => 'unpaid',
            'is_paid' => false,
            'plan_name' => 'InfiMal Pro',
            'license_status' => 'inactive',
            'campaign_count' => 0,
            'email_sent' => 0,
            'onboarding_step' => 'payment_required',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('payment')
            ->with('success', 'Account created. Complete payment to continue.');
    }

    public function forgotPassword(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        return back()->with('status', 'Password reset link sent!');
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        return redirect()
            ->route('login')
            ->with('status', 'Password reset successfully!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Logged out successfully!');
    }
    protected function authenticated(Request $request, User $user): RedirectResponse
    {
        if ($user->is_paid && $user->is_verified) {
            return redirect()->intended(route('dashboard'));
        }

        if (! $user->is_paid) {
            return redirect()->route('payment');
        }

        return redirect()->route('otp.verify.form');
    }


        if ($user->hasPaidAccess()) {
            return redirect()->route('dashboard');
        }

        if ($user->hasPaid() && ! $user->otp_verified_at) {
            return redirect()->route('otp.verify.form');
        }

        return redirect()->route('billing');
    }

}
