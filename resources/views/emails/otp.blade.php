<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification - InfiMal</title>
    <style>
        /* Fallback for clients that support style tags */
        .rainbow-btn {
            background: linear-gradient(45deg, #FF6B6B, #FF8E53, #FFD166, #06D6A0, #118AB2, #073B4C);
            background-size: 400% 400%;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body style="margin: 0; padding: 20px; background: linear-gradient(135deg, #e0e7ff, #c7d2fe); font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <center style="width: 100%; table-layout: fixed;">
        <div style="max-width: 480px; margin: 0 auto;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="background: #ffffff; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden; width: 100%;">
                
                <!-- Logo Section (like login page) -->
                <tr>
                    <td style="padding: 32px 24px 16px 24px; text-align: center;">
                        <a href="{{ url('/') }}" style="text-decoration: none;">
                            <img src="{{ asset('logo.png') }}" alt="INFIMAL" style="height: 48px; width: auto; border: none;">
                        </a>
                    </td>
                </tr>
                
                <!-- Heading -->
                <tr>
                    <td style="padding: 0 24px 8px 24px; text-align: center;">
                        <h2 style="font-size: 26px; font-weight: 700; color: #111827; margin: 0;">OTP Verification</h2>
                        <p style="font-size: 15px; color: #4b5563; margin: 8px 0 0 0;">Enter the code to verify your email</p>
                    </td>
                </tr>
                
                <!-- Email display -->
                <tr>
                    <td style="padding: 8px 24px 0 24px; text-align: center;">
                        <p style="font-size: 14px; color: #6b7280; margin: 0;">
                            We sent a 6-digit OTP to <strong style="color: #4f46e5;">{{ $user->email ?? auth()->user()->email ?? 'your email' }}</strong>
                        </p>
                    </td>
                </tr>
                
                <!-- OTP Code Box (like login input card) -->
                <tr>
                    <td style="padding: 24px 24px 16px 24px;">
                        <div style="background: #f9fafb; border-radius: 20px; padding: 24px; text-align: center; border: 1px solid #e5e7eb;">
                            <span style="font-family: monospace; font-size: 52px; font-weight: 800; letter-spacing: 8px; color: #1f2937; background: white; padding: 12px 24px; border-radius: 16px; display: inline-block; border: 1px solid #e5e7eb;">
                                {{ $otpCode }}
                            </span>
                        </div>
                    </td>
                </tr>
                
                <!-- Info text -->
                <tr>
                    <td style="padding: 0 24px 16px 24px; text-align: center;">
                        <p style="font-size: 13px; color: #6b7280; margin: 0;">
                            🔒 This OTP is valid for <strong>10 minutes</strong><br>
                            If you didn't request this, please ignore this email.
                        </p>
                    </td>
                </tr>
                
                <!-- Rainbow Gradient Button (like login page) -->
                <tr>
                    <td style="padding: 8px 24px 32px 24px; text-align: center;">
                        <table cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 0 auto;">
                            <tr>
                                <td align="center" style="background: linear-gradient(45deg, #FF6B6B, #FF8E53, #FFD166, #06D6A0, #118AB2, #073B4C); background-size: 400% 400%; border-radius: 40px; padding: 0;">
                                    <a href="{{ url('/verify-otp') }}" style="display: inline-block; background: linear-gradient(45deg, #FF6B6B, #FF8E53, #FFD166, #06D6A0, #118AB2, #073B4C); background-size: 400% 400%; padding: 14px 32px; border-radius: 40px; color: white; font-weight: 600; text-decoration: none; font-size: 16px; text-align: center;">Verify Your Account</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                
                <!-- Footer -->
                <tr>
                    <td style="background: #f9fafb; padding: 24px; text-align: center; border-top: 1px solid #e5e7eb;">
                        <p style="font-size: 12px; color: #6b7280; margin: 0;">
                            &copy; {{ date('Y') }} InfiMal. All rights reserved.<br>
                            Secure email marketing platform
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </center>
</body>
</html>
