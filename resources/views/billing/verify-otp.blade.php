<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify OTP - InfiMal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: "Inter", sans-serif; }
        .rainbow-gradient {
            background: linear-gradient(45deg, #FF6B6B, #FF8E53, #FFD166, #06D6A0, #118AB2, #073B4C);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            
            <!-- Brand Logo -->
            <div class="flex justify-center mb-6">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('logo.png') }}" alt="INFIMAL" class="h-12 w-auto">
                </a>
            </div>

            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">OTP Verification</h2>
                <p class="text-gray-600 mt-1">
                    We sent a 6-digit OTP to <strong>{{ auth()->user()->email }}</strong>
                </p>
            </div>

            @if (session('success'))
                <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 text-sm">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('otp.verify.submit') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Enter OTP</label>
                    <input type="text" name="otp" maxlength="6" inputmode="numeric" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-center text-2xl tracking-widest"
                           placeholder="123456">
                </div>

                <button type="submit" 
                        class="w-full rainbow-gradient text-white py-3 px-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transition">
                    Verify & Continue
                </button>
            </form>

            <form method="POST" action="{{ route('otp.verify.resend') }}" class="mt-4">
                @csrf
                <button type="submit" 
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 px-4 rounded-xl font-medium transition">
                    Resend OTP
                </button>
            </form>

            <div class="text-center mt-6">
                <a href="{{ route('payment') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    ? Back to Payment
                </a>
            </div>
        </div>
    </div>
</body>
</html>