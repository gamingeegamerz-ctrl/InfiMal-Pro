<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - InfiMal</title>
    <script>
        (() => { if (localStorage.getItem('infimal_theme') === 'dark') document.documentElement.classList.add('dark'); })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 dark:bg-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white dark:bg-slate-800 rounded-xl shadow p-6 space-y-4">
        <form method="POST" action="{{ route('otp.verify.submit') }}" class="space-y-4">
            @csrf
            <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Verify your account</h1>
            <p class="text-sm text-slate-600 dark:text-slate-300">Enter the 6-digit OTP sent to your email after payment.</p>
            <input name="otp" maxlength="6" required class="w-full border rounded-lg px-3 py-2 dark:bg-slate-700 dark:text-white" placeholder="123456" />
            @error('otp')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            <button class="w-full bg-blue-600 text-white py-2 rounded-lg">Verify OTP</button>
        </form>

        <form method="POST" action="{{ route('otp.verify.resend') }}">
            @csrf
            <button class="w-full border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 py-2 rounded-lg">Resend OTP</button>
        </form>
    </div>
</body>
</html>
