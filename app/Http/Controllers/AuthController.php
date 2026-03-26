<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = $request->user();

            if (!$user->hasPaid()) {
                return redirect()->route('payment');
            }

            if (!$user->otp_verified_at) {
                return redirect()->route('otp.verify.form');
            }

            return redirect()->route('dashboard')->with('success', 'Login successful.');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput($request->except('password'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->string('name'),
            'email' => $request->string('email'),
            'password' => Hash::make((string) $request->input('password')),
            'payment_status' => 'unpaid',
            'is_paid' => false,
            'license_status' => 'inactive',
        ]);

        Auth::login($user);

        return redirect()->route('payment')->with('success', 'Account created. Complete payment to continue.');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        return back()->with('status', 'Password reset link sent!');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        return redirect()->route('login')->with('status', 'Password reset successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }
}
