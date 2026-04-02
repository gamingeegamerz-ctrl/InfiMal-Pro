<!DOCTYPE html>
<html>
<head>
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
