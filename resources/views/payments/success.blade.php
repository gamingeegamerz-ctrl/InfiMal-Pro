<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Payment Successful - InfiMal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
</head>
<body class="font-sans bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="flex justify-center mb-6">
            <a href="{{ url('/') }}">
                <img src="{{ asset('logo.png') }}" alt="INFIMAL" class="h-12 w-auto">
            </a>
        </div>
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment Successful!</h1>
        <p class="text-gray-600 mb-6">Thank you for your payment. Your InfiMal Pro subscription is now active.</p>
        
        @if(isset($checkoutId))
        <div class="bg-gray-100 p-3 rounded-lg mb-4">
            <p class="text-sm text-gray-600">Transaction ID: <span class="font-mono">{{ $checkoutId }}</span></p>
        </div>
        @endif
        
        <div class="space-y-3">
            <a href="/dashboard" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-200 block text-center">
                Go to Dashboard
            </a>
            <a href="/" class="w-full border border-gray-300 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-50 transition duration-200 block text-center">
                Return Home
            </a>
        </div>
    </div>
</body>
</html>
