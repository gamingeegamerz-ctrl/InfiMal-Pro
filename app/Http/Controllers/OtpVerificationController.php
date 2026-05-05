<?php

namespace App\Http\Controllers;

use App\Services\OtpService;
use Illuminate\Http\Request;

class OtpVerificationController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showForm()
    {
        return view('billing.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|string|size:6']);

        $user = $request->user();
        
        if ($this->otpService->verifyOtp($user, $request->otp)) {
            return redirect()->route('dashboard')->with('success', 'Email verified successfully.');
        }
        
        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    public function resend(Request $request)
    {
        $user = $request->user();
        
        if ($this->otpService->generateAndSendOtp($user)) {
            return back()->with('success', 'OTP resent successfully.');
        }
        
        return back()->withErrors(['error' => 'Failed to send OTP.']);
    }
}
