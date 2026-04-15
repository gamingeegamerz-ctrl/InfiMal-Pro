<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - InfiMal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <div class="flex justify-center mb-6">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('logo.png') }}" alt="INFIMAL" class="h-12 w-auto">
                </a>
            </div>
            <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Page Not Found</h2>
            <p class="text-gray-600 mb-8">The page you're looking for doesn't exist.</p>
            <a href="/" class="bg-blue-600 text-white px-6 py-3 rounded-lg">Go Home</a>
        </div>
    </div>
</body>
</html>
