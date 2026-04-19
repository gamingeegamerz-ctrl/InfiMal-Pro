<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify OTP - InfiMal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-white flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-2xl">
        <h1 class="text-2xl font-bold mb-2">OTP Verification</h1>
        <p class="text-slate-300 text-sm mb-6">
            Payment completed. We sent a 6-digit OTP to <span class="font-semibold">{{ $user->email }}</span>.
            Enter OTP to unlock dashboard access.
        </p>

        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-emerald-500/20 text-emerald-300 text-sm">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 rounded-lg bg-rose-500/20 text-rose-300 text-sm">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-rose-500/20 text-rose-300 text-sm">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('otp.verify.submit') }}" class="space-y-4">
            @csrf
            <label class="block">
                <span class="text-sm text-slate-300">6-digit OTP</span>
                <input type="text" name="otp" maxlength="6" inputmode="numeric" required class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="123456">
            </label>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 rounded-lg py-3 font-semibold">Verify & Continue</button>
        </form>

        <form method="POST" action="{{ route('otp.verify.resend') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full border border-slate-700 hover:bg-slate-800 rounded-lg py-3 font-semibold">Resend OTP</button>
        </form>

        <a href="{{ route('payment') }}" class="block text-center text-slate-400 text-sm mt-4 hover:text-white">Back to payment</a>
    </div>
</body>
</html>
