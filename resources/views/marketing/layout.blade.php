<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seo['title'] ?? 'INFIMAL' }}</title>
    <meta name="description" content="{{ $seo['description'] ?? 'INFIMAL email marketing SaaS platform.' }}">
    <meta name="keywords" content="{{ $seo['keywords'] ?? 'email marketing tool, bulk email sender, SMTP email sending' }}">
    <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="INFIMAL">
    <meta property="og:title" content="{{ $seo['og_title'] ?? ($seo['title'] ?? 'INFIMAL') }}">
    <meta property="og:description" content="{{ $seo['og_description'] ?? ($seo['description'] ?? '') }}">
    <meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['twitter_title'] ?? ($seo['title'] ?? 'INFIMAL') }}">
    <meta name="twitter:description" content="{{ $seo['twitter_description'] ?? ($seo['description'] ?? '') }}">
    @if(!empty($seo['robots']))
        <meta name="robots" content="{{ $seo['robots'] }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-900">
    <header class="border-b">
        <nav class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-xl font-bold">INFIMAL</a>
            <div class="space-x-4 text-sm">
                <a href="{{ route('features') }}">Features</a>
                <a href="{{ route('pricing') }}">Pricing</a>
                <a href="{{ route('blog.index') }}">Blog</a>
                <a href="{{ route('login') }}">Login</a>
            </div>
        </nav>
    </header>

    @yield('content')

    <footer class="border-t mt-12">
        <div class="max-w-6xl mx-auto px-4 py-8 text-sm text-gray-600">
            <div class="flex justify-center mb-6">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('logo.png') }}" alt="INFIMAL" class="h-12 w-auto">
                </a>
            </div>
            <p>INFIMAL email marketing SaaS for bulk email sending and SMTP campaign automation.</p>
        </div>
    </footer>

    @if(!empty($schema ?? null))
    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>
    @endif
</body>
</html>
