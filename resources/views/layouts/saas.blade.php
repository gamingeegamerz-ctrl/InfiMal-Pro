<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'InfiMal')</title>
    <script>
        (() => {
            const saved = localStorage.getItem('infimal-theme') || 'light';
            document.documentElement.classList.toggle('dark', saved === 'dark');
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
</head>
<body class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
<div class="min-h-full">
    <div class="border-b border-slate-200 bg-white/95 backdrop-blur dark:border-slate-800 dark:bg-slate-900/95">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <div>
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600 dark:text-blue-400">InfiMal</a>
                <p class="text-sm text-slate-500 dark:text-slate-400">Production-ready email marketing SaaS</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleTheme()" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-medium dark:border-slate-700">
                    <span id="theme-label">Theme</span>
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white dark:bg-slate-100 dark:text-slate-900">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <div class="mx-auto grid max-w-7xl gap-6 px-6 py-6 lg:grid-cols-[240px_minmax(0,1fr)]">
        <aside class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <nav class="space-y-2 text-sm font-medium">
                @php($links = [
                    ['route' => 'dashboard', 'label' => 'Dashboard'],
                    ['route' => 'campaigns.index', 'label' => 'Campaigns'],
                    ['route' => 'subscribers.index', 'label' => 'Subscribers'],
                    ['route' => 'messages.index', 'label' => 'Messages'],
                    ['route' => 'smtp.index', 'label' => 'SMTP Settings'],
                    ['route' => 'analytics.index', 'label' => 'Analytics'],
                    ['route' => 'billing', 'label' => 'Billing'],
                    ['route' => 'profile.index', 'label' => 'Profile'],
                ])
                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}" class="block rounded-xl px-4 py-3 {{ request()->routeIs(str_replace('.index', '.*', $link['route'])) || request()->routeIs($link['route']) ? 'bg-blue-600 text-white' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' }}">{{ $link['label'] }}</a>
                @endforeach
            </nav>
        </aside>

        <main class="space-y-6">
            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700 dark:border-rose-900 dark:bg-rose-950/40 dark:text-rose-300">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
<script>
function toggleTheme() {
    const root = document.documentElement;
    const next = root.classList.contains('dark') ? 'light' : 'dark';
    root.classList.toggle('dark', next === 'dark');
    localStorage.setItem('infimal-theme', next);
    updateThemeLabel();
}
function updateThemeLabel() {
    document.getElementById('theme-label').textContent = document.documentElement.classList.contains('dark') ? 'Dark mode' : 'Light mode';
}
updateThemeLabel();
</script>
</body>
</html>
