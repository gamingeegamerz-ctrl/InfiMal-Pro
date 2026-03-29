<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            return back()->withErrors(['email' => 'Invalid credentials provided.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route($request->user()->hasPaid() && $request->user()->hasActiveLicense() ? 'dashboard' : 'billing');
        }

        $request->session()->regenerate();

        return redirect()->route($request->user()->hasPaid() && $request->user()->hasActiveLicense() ? 'dashboard' : 'billing');
            return back()
                ->withErrors(['email' => 'Invalid credentials provided.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();
        $user = $request->user();

        // Payment check
        if (! $user->hasPaid()) {
            return redirect()->route('payment');
        }

        // License check
        if (! $user->hasActiveLicense()) {
            return redirect()->route('billing');
        }

        // OTP check
        if (! $user->otp_verified_at) {
            return redirect()->route('otp.verify.form');
        }

        return redirect()->route('dashboard');
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
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('billing')->with('success', 'Account created. Complete your $299 payment to unlock the platform.');
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

        return redirect()->route('login');
        return redirect()
            ->route('login')
            ->with('success', 'Logged out successfully!');
    }
}