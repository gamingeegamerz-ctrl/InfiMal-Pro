<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <title>Error - Subscribers</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f8d7da; color: #721c24; }
        .container { max-width: 800px; margin: 50px auto; background: white; padding: 30px; border-radius: 5px; }
        h1 { color: #721c24; }
        .error { background: #f8d7da; padding: 15px; border-radius: 5px; }
        a { color: #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="flex justify-center mb-6">
            <a href="{{ url('/') }}">
                <img src="{{ asset('logo.png') }}" alt="INFIMAL" class="h-12 w-auto">
            </a>
        </div>
        <h1>⚠️ Subscribers Page Error</h1>
        <div class="error">
            <strong>Error Message:</strong><br>
            {{ $message }}
        </div>
        <br>
        <a href="/dashboard">← Back to Dashboard</a>
    </div>
</body>
</html>
