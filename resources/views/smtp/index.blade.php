<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SMTP Settings - InfiMal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .rainbow-text {
            background: linear-gradient(90deg, #FF6B6B, #4ECDC4, #45B7D1, #96CEB4, #FFEAA7, #FF6B6B);
            background-size: 400% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: rainbow 8s ease infinite;
        }
        @keyframes rainbow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .dark .glass-card {
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .hover-glow:hover {
            box-shadow: 0 0 30px rgba(79, 70, 229, 0.2);
            transform: translateY(-2px);
        }
        .dark .hover-glow:hover {
            box-shadow: 0 0 30px rgba(165, 180, 252, 0.2);
        }
        .nav-link {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.15), rgba(147, 51, 234, 0.15));
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: -1;
        }
        .nav-link:hover::before {
            width: 100%;
        }
        .nav-link:hover {
            transform: translateX(4px);
        }
        .nav-link:hover .material-symbols-outlined {
            transform: scale(1.1) rotate(5deg);
        }
        .nav-link .material-symbols-outlined {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-link.active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.15), rgba(147, 51, 234, 0.15));
            border-left: 3px solid #3B82F6;
            transform: translateX(4px);
        }
        .dark .nav-link.active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.25), rgba(147, 51, 234, 0.25));
            border-left: 3px solid #60a5fa;
        }
        .nav-link.active .material-symbols-outlined {
            transform: scale(1.1);
        }
        aside {
            animation: slideInLeft 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .sidebar-logo {
            animation: fadeInDown 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes fadeInDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .nav-link {
            opacity: 0;
            animation: fadeInLeft 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        .nav-link:nth-child(1) { animation-delay: 0.1s; }
        .nav-link:nth-child(2) { animation-delay: 0.15s; }
        .nav-link:nth-child(3) { animation-delay: 0.2s; }
        .nav-link:nth-child(4) { animation-delay: 0.25s; }
        .nav-link:nth-child(5) { animation-delay: 0.3s; }
        .nav-link:nth-child(6) { animation-delay: 0.35s; }
        .nav-link:nth-child(7) { animation-delay: 0.4s; }
        .nav-link:nth-child(8) { animation-delay: 0.45s; }
        @keyframes fadeInLeft {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .logout-btn {
            opacity: 0;
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.5s forwards;
        }
        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .theme-toggle {
            position: relative;
            width: 60px;
            height: 32px;
            background: #e2e8f0;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .dark .theme-toggle {
            background: #475569;
        }
        .theme-toggle::before {
            content: '';
            position: absolute;
            width: 24px;
            height: 24px;
            background: white;
            border-radius: 50%;
            top: 4px;
            left: 4px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .dark .theme-toggle::before {
            transform: translateX(28px);
            background: #fbbf24;
        }
        .theme-toggle::after {
            content: '🌙';
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            opacity: 0.5;
            transition: opacity 0.3s ease;
        }
        .theme-toggle::before {
            content: '☀️';
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f59e0b;
        }
        .dark .theme-toggle::after {
            content: '☀️';
            left: 8px;
            right: auto;
            opacity: 0.7;
        }
        .dark .theme-toggle::before {
            content: '🌙';
            color: #fbbf24;
        }
        .status-badge {
            transition: all 0.3s ease;
        }
        .status-badge:hover {
            transform: scale(1.05);
        }
        .modal-overlay {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
        }
        .btn-loading {
            opacity: 0.7;
            pointer-events: none;
        }
    </style>
    <script>
        // Immediately apply dark mode before page render
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>
<body class="bg-white dark:bg-slate-900 text-gray-900 dark:text-slate-100 transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700 flex-shrink-0">
            <div class="flex flex-col h-full p-4">
                <!-- Logo -->
                <div class="sidebar-logo flex items-center gap-3 p-3 mb-8">
                    <div class="p-2 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 text-white">
                        <span class="material-symbols-outlined">all_inbox</span>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-xl font-bold rainbow-text">InfiMal</h1>
                        <p class="text-gray-500 dark:text-slate-400 text-xs font-medium">Email Management</p>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex flex-col gap-1 flex-1">
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/dashboard') }}">
                        <span class="material-symbols-outlined text-xl">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/subscribers') }}">
                        <span class="material-symbols-outlined text-xl">group</span>
                        <span>Subscribers</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/lists') }}">
                        <span class="material-symbols-outlined text-xl">list_alt</span>
                        <span>Lists</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/campaigns') }}">
                        <span class="material-symbols-outlined text-xl">campaign</span>
                        <span>Campaigns</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/messages') }}">
                        <span class="material-symbols-outlined text-xl">chat</span>
                        <span>Messages</span>
                    </a>
                    <a class="nav-link active flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/smtp') }}">
                        <span class="material-symbols-outlined text-xl">dns</span>
                        <span>SMTP Settings</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/billing') }}">
                        <span class="material-symbols-outlined text-xl">receipt_long</span>
                        <span>Billing</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/profile') }}">
                        <span class="material-symbols-outlined text-xl">person</span>
                        <span>Profile</span>
                    </a>
                </nav>
                
                <!-- Dark Mode Toggle -->
                <div class="pt-4 border-t border-gray-200 dark:border-slate-700 logout-btn flex items-center justify-between">
                    <div class="flex items-center gap-3 px-3 py-2.5">
                        <span class="material-symbols-outlined text-xl text-gray-600 dark:text-slate-400">dark_mode</span>
                        <span class="text-gray-600 dark:text-slate-400 font-medium text-sm">Theme</span>
                    </div>
                    <div class="theme-toggle" id="themeToggle"></div>
                </div>
                
                <!-- Logout -->
                <div class="pt-4 border-t border-gray-200 dark:border-slate-700 logout-btn">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-500 font-medium text-sm">
                            <span class="material-symbols-outlined text-xl">logout</span>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-slate-900">
            <!-- Top Bar -->
            <header class="bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700 sticky top-0 z-10">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 max-w-md">
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500">search</span>
                                <input type="text" id="searchInput" placeholder="Search SMTP configurations..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 placeholder-gray-500 dark:placeholder-slate-500" />
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="openAddModal()" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg font-semibold text-sm hover-glow transition-all duration-300">
                                <span class="material-symbols-outlined text-base align-middle mr-1">add</span>
                                Add SMTP
                            </button>
                            <button onclick="testAllSmtp()" class="border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-slate-300 px-6 py-2 rounded-lg font-semibold text-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition-all duration-300">
                                <span class="material-symbols-outlined text-base align-middle mr-1">play_arrow</span>
                                Test All
                            </button>
                            <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                                <span class="material-symbols-outlined text-gray-600 dark:text-slate-400">notifications</span>
                            </button>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6 space-y-6">
                <!-- Welcome Banner -->
                <div class="glass-card rounded-2xl p-8 shadow-lg border-2 border-blue-100 dark:border-slate-700 hover-glow transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">SMTP Configuration</h1>
                            <p class="text-gray-600 dark:text-slate-300 mb-4">Configure and manage your email delivery servers for reliable email sending.</p>
                            <div class="flex items-center gap-6">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-500 text-xl">verified</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium" id="totalConfigs">{{ $totalSmtp }} Configurations</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500 text-xl">check_circle</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium" id="activeConfigs">{{ $activeSmtp }} Active</span>
                                </div>
                                <div id="smtp-status" class="flex items-center gap-2">
                                    <span class="w-2 h-2 {{ $smtpStatus == 'Active' ? 'bg-green-500' : ($smtpStatus == 'Failed' ? 'bg-red-500' : 'bg-gray-400') }} rounded-full"></span>
                                    <span class="text-gray-600 dark:text-slate-300 text-xs font-medium">System Status: {{ $smtpStatus }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden lg:block">
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 rounded-2xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-6xl">dns</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">SMTP Configs</h3>
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">dns</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2" id="statTotal">{{ $totalSmtp }}</p>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium" id="statActive">{{ $activeSmtp }} active</p>
                    </div>
                    
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Sent Today</h3>
                            <div class="p-2 bg-green-100 dark:bg-green-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">send</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ number_format($usageStats['sent_today']) }}</p>
                        <p class="text-blue-600 dark:text-blue-400 text-sm font-medium">Today's usage</p>
                    </div>
                    
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">This Month</h3>
                            <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">calendar_month</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ number_format($usageStats['sent_this_month']) }}</p>
                        <p class="text-purple-600 dark:text-purple-400 text-sm font-medium">Monthly usage</p>
                    </div>
                    
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Success Rate</h3>
                            <div class="p-2 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">check_circle</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $usageStats['success_rate'] }}%</p>
                        <p class="text-emerald-600 dark:text-emerald-400 text-sm font-medium">Delivery success</p>
                    </div>
                </div>

                <!-- SMTP Table -->
                <div class="glass-card rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-gray-900 dark:text-white font-bold text-lg">SMTP Configurations</h3>
                        <button onclick="refreshData()" class="text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white text-sm font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">refresh</span>
                            Refresh
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-slate-700">
                                    <th class="text-left p-3 text-gray-600 dark:text-slate-400 font-semibold text-sm">Status</th>
                                    <th class="text-left p-3 text-gray-600 dark:text-slate-400 font-semibold text-sm">Name / Server</th>
                                    <th class="text-left p-3 text-gray-600 dark:text-slate-400 font-semibold text-sm">From Address</th>
                                    <th class="text-left p-3 text-gray-600 dark:text-slate-400 font-semibold text-sm">Daily Usage</th>
                                    <th class="text-left p-3 text-gray-600 dark:text-slate-400 font-semibold text-sm">Total Sent</th>
                                    <th class="text-left p-3 text-gray-600 dark:text-slate-400 font-semibold text-sm">Last Used</th>
                                    <th class="text-left p-3 text-gray-600 dark:text-slate-400 font-semibold text-sm">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="smtpTableBody">
                                @foreach($smtpSettings as $smtp)
                                <tr class="border-b border-gray-100 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors" data-id="{{ $smtp->id }}">
                                    <td class="p-3">
                                        <span class="status-badge inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $smtp->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $smtp->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                            {{ $smtp->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex flex-col">
                                            <p class="text-gray-900 dark:text-white font-medium">{{ $smtp->name ?? $smtp->host }}</p>
                                            <p class="text-gray-500 dark:text-slate-400 text-xs">{{ $smtp->host }}:{{ $smtp->port }} ({{ strtoupper($smtp->encryption) }})</p>
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex flex-col">
                                            <p class="text-gray-900 dark:text-white text-sm">{{ $smtp->from_address }}</p>
                                            <p class="text-gray-500 dark:text-slate-400 text-xs">{{ $smtp->from_name }}</p>
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex flex-col">
                                            <p class="text-gray-900 dark:text-white text-sm font-medium">{{ $smtp->sent_today ?? 0 }}/{{ $smtp->daily_limit ?? 500 }}</p>
                                            <div class="w-32 h-1.5 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden mt-1">
                                                @php
                                                    $usagePercentage = ($smtp->daily_limit ?? 500) > 0 ? (($smtp->sent_today ?? 0) / ($smtp->daily_limit ?? 500)) * 100 : 0;
                                                @endphp
                                                <div class="h-full {{ $usagePercentage > 80 ? 'bg-red-500' : ($usagePercentage > 50 ? 'bg-yellow-500' : 'bg-green-500') }} rounded-full" style="width: {{ min($usagePercentage, 100) }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        <p class="text-gray-900 dark:text-white text-sm font-medium">{{ number_format($smtp->total_sent ?? 0) }}</p>
                                        <p class="text-gray-500 dark:text-slate-400 text-xs">All time</p>
                                    </td>
                                    <td class="p-3">
                                        @if($smtp->last_used_at)
                                        <p class="text-gray-900 dark:text-white text-sm">{{ \Carbon\Carbon::parse($smtp->last_used_at)->format('M d, Y') }}</p>
                                        <p class="text-gray-500 dark:text-slate-400 text-xs">{{ \Carbon\Carbon::parse($smtp->last_used_at)->format('h:i A') }}</p>
                                        @else
                                        <p class="text-gray-500 dark:text-slate-400 text-sm">Never used</p>
                                        @endif
                                    </td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-1">
                                            <button onclick="testSmtp({{ $smtp->id }})" class="test-btn p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors" data-id="{{ $smtp->id }}" title="Test Connection">
                                                <span class="material-symbols-outlined text-blue-500 text-sm">play_arrow</span>
                                            </button>
                                            <button onclick="toggleSmtp({{ $smtp->id }})" class="toggle-btn p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors" data-id="{{ $smtp->id }}" title="{{ $smtp->is_active ? 'Deactivate' : 'Activate' }}">
                                                <span class="material-symbols-outlined text-yellow-500 text-sm">{{ $smtp->is_active ? 'toggle_off' : 'toggle_on' }}</span>
                                            </button>
                                            <button onclick="editSmtp({{ $smtp->id }})" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors" title="Edit">
                                                <span class="material-symbols-outlined text-emerald-500 text-sm">edit</span>
                                            </button>
                                            <button onclick="deleteSmtp({{ $smtp->id }})" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors" title="Delete">
                                                <span class="material-symbols-outlined text-red-500 text-sm">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($smtpSettings->count() == 0)
                    <div class="text-center py-12" id="emptyState">
                        <div class="w-20 h-20 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-4xl">dns</span>
                        </div>
                        <h4 class="text-gray-900 dark:text-white font-semibold text-lg mb-2">No SMTP Configurations</h4>
                        <p class="text-gray-500 dark:text-slate-400 text-sm mb-6">Add your first SMTP server to start sending emails</p>
                        <button onclick="openAddModal()" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg font-semibold text-sm hover-glow transition-all duration-300">
                            Add SMTP Configuration
                        </button>
                    </div>
                    @endif
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="glass-card rounded-2xl p-6 shadow-lg">
                        <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">Daily Email Usage (Last 7 Days)</h3>
                        <canvas id="dailyUsageChart" height="250"></canvas>
                    </div>
                    <div class="glass-card rounded-2xl p-6 shadow-lg">
                        <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">SMTP Distribution</h3>
                        <canvas id="smtpDistributionChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit Modal -->
    <div id="smtpModal" class="fixed inset-0 z-50 hidden items-center justify-center modal-overlay" onclick="closeModal(event)">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="p-6 border-b border-gray-200 dark:border-slate-700 flex items-center justify-between">
                <h3 id="modalTitle" class="text-gray-900 dark:text-white font-bold text-xl">Add SMTP Configuration</h3>
                <button onclick="closeModal()" class="p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                    <span class="material-symbols-outlined text-gray-500 dark:text-slate-400">close</span>
                </button>
            </div>
            <form id="smtpForm" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="smtpId" name="smtp_id" value="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Name (Optional)</label>
                        <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-900 text-gray-900 dark:text-white" placeholder="e.g., Gmail SMTP">
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">From Name</label>
                        <input type="text" id="from_name" name="from_name" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-900 text-gray-900 dark:text-white" placeholder="Your Company Name">
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">From Email Address *</label>
                    <input type="email" id="from_address" name="from_address" required class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-900 text-gray-900 dark:text-white" placeholder="sender@yourdomain.com">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">SMTP Host *</label>
                        <input type="text" id="host" name="host" required class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-900 text-gray-900 dark:text-white" placeholder="smtp.gmail.com">
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Port *</label>
                        <input type="number" id="port" name="port" required class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-900 text-gray-900 dark:text-white" placeholder="587">
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Encryption *</label>
                        <select id="encryption" name="encryption" required class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-900 text-gray-900 dark:text-white">
                            <option value="tls">TLS</option>
                            <option value="ssl">SSL</option>
                            <option value="none">None</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Username *</label>
                        <input type="text" id="username" name="username" required class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-900 text-gray-900 dark:text-white" placeholder="your-email@gmail.com">
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Password</label>
                        <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-900 text-gray-900 dark:text-white">
                        <p class="text-gray-500 dark:text-slate-400 text-xs mt-1" id="passwordHint">Leave blank to keep existing password</p>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Daily Limit *</label>
                    <input type="number" id="daily_limit" name="daily_limit" required value="500" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-900 text-gray-900 dark:text-white">
                    <p class="text-gray-500 dark:text-slate-400 text-xs mt-1">Maximum emails per day for this SMTP</p>
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked class="rounded border-gray-300 dark:border-slate-600">
                    <label class="text-gray-700 dark:text-slate-300 text-sm">Activate this SMTP immediately</label>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-slate-700">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">Cancel</button>
                    <button type="submit" id="submitBtn" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover-glow transition-all duration-300">Save Configuration</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const authToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        let isLoading = false;
        let dailyChart = null;
        let distChart = null;

        // Dark/Light Mode Toggle
        function initThemeToggle() {
            const themeToggle = document.getElementById('themeToggle');
            
            themeToggle.addEventListener('click', function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
                updateChartsTheme();
            });
        }

        // Update charts theme colors
        function updateChartsTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#f1f5f9' : '#1e2937';
            const gridColor = isDark ? '#334155' : '#e2e8f0';
            const tickColor = isDark ? '#94a3b8' : '#64748b';
            
            if (dailyChart) {
                dailyChart.options.plugins.legend.labels.color = textColor;
                dailyChart.options.scales.y.ticks.color = tickColor;
                dailyChart.options.scales.y.grid.color = gridColor;
                dailyChart.options.scales.x.ticks.color = tickColor;
                dailyChart.options.scales.x.grid.color = gridColor;
                dailyChart.update();
            }
            
            if (distChart) {
                distChart.options.plugins.legend.labels.color = textColor;
                distChart.update();
            }
        }

        function showToast(title, message, type = 'success') {
            const isDark = document.documentElement.classList.contains('dark');
            Swal.fire({
                title: title,
                text: message,
                icon: type,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f1f5f9' : '#1e293b'
            });
        }

        // Charts
        function initCharts() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#f1f5f9' : '#1e2937';
            const gridColor = isDark ? '#334155' : '#e2e8f0';
            const tickColor = isDark ? '#94a3b8' : '#64748b';
            
            const dailyLabels = {!! json_encode($dailyLabels ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) !!};
            const dailyData = {!! json_encode($dailyUsageData ?? [0, 0, 0, 0, 0, 0, 0]) !!};
            
            const dailyCtx = document.getElementById('dailyUsageChart')?.getContext('2d');
            if (dailyCtx) {
                dailyChart = new Chart(dailyCtx, {
                    type: 'bar',
                    data: {
                        labels: dailyLabels,
                        datasets: [{
                            label: 'Emails Sent',
                            data: dailyData,
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderColor: '#3b82f6',
                            borderWidth: 1,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { 
                                labels: { color: textColor } 
                            }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                ticks: { color: tickColor }, 
                                grid: { color: gridColor } 
                            },
                            x: { 
                                ticks: { color: tickColor }, 
                                grid: { color: gridColor } 
                            }
                        }
                    }
                });
            }

            const distCtx = document.getElementById('smtpDistributionChart')?.getContext('2d');
            if (distCtx) {
                const smtpNames = {!! json_encode($smtpSettings->pluck('name')->map(fn($n, $i) => $n ?: 'SMTP ' . ($i + 1))) !!};
                const smtpData = {!! json_encode($smtpSettings->pluck('total_sent')) !!};
                
                if (smtpData.length > 0) {
                    distChart = new Chart(distCtx, {
                        type: 'doughnut',
                        data: {
                            labels: smtpNames,
                            datasets: [{
                                data: smtpData,
                                backgroundColor: ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444', '#06b6d4', '#ec4899'],
                                borderWidth: 0,
                                hoverOffset: 10
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: { 
                                    position: 'bottom', 
                                    labels: { color: textColor } 
                                }
                            }
                        }
                    });
                }
            }
        }

        // Modal Functions
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add SMTP Configuration';
            document.getElementById('smtpForm').reset();
            document.getElementById('smtpId').value = '';
            document.getElementById('password').value = '';
            document.getElementById('password').removeAttribute('required');
            document.getElementById('passwordHint').style.display = 'block';
            document.getElementById('is_active').checked = true;
            document.getElementById('daily_limit').value = 500;
            document.getElementById('smtpModal').classList.remove('hidden');
            document.getElementById('smtpModal').classList.add('flex');
        }

        function closeModal(event) {
            if (!event || event.target.id === 'smtpModal' || (event.target.closest && event.target.closest('#smtpModal') === null)) {
                document.getElementById('smtpModal').classList.add('hidden');
                document.getElementById('smtpModal').classList.remove('flex');
            }
        }

        // SMTP Actions
        async function testSmtp(id) {
            const btn = document.querySelector(`.test-btn[data-id="${id}"]`);
            if (!btn) return;
            
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<span class="material-symbols-outlined text-blue-500 text-sm animate-spin">progress_activity</span>';
            btn.disabled = true;
            
            try {
                const response = await fetch(`/smtp/${id}/test`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': authToken, 'Content-Type': 'application/json' }
                });
                const data = await response.json();
                
                if (data.success) {
                    showToast('Success!', 'SMTP connection successful!', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('Connection Failed', data.message || 'Failed to connect to SMTP server', 'error');
                }
            } catch (error) {
                showToast('Error', 'Network error occurred', 'error');
            } finally {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        }

        async function toggleSmtp(id) {
            try {
                const response = await fetch(`/smtp/${id}/toggle`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': authToken, 'Content-Type': 'application/json' }
                });
                const data = await response.json();
                
                if (data.success) {
                    showToast('Success', 'SMTP status updated', 'success');
                    setTimeout(() => location.reload(), 500);
                }
            } catch (error) {
                showToast('Error', 'Failed to update status', 'error');
            }
        }

        async function deleteSmtp(id) {
            const isDark = document.documentElement.classList.contains('dark');
            const result = await Swal.fire({
                title: 'Delete SMTP Configuration?',
                text: 'This action cannot be undone. All email logs will remain but this SMTP will be removed.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!',
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f1f5f9' : '#1e2937'
            });
            
            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/smtp/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': authToken, 'Content-Type': 'application/json' }
                    });
                    
                    if (response.ok) {
                        showToast('Deleted!', 'SMTP configuration deleted', 'success');
                        setTimeout(() => location.reload(), 500);
                    }
                } catch (error) {
                    showToast('Error', 'Failed to delete', 'error');
                }
            }
        }

        async function editSmtp(id) {
            try {
                const response = await fetch(`/smtp/${id}`);
                const data = await response.json();
                
                document.getElementById('modalTitle').textContent = 'Edit SMTP Configuration';
                document.getElementById('smtpId').value = data.id;
                document.getElementById('name').value = data.name || '';
                document.getElementById('from_name').value = data.from_name || '';
                document.getElementById('from_address').value = data.from_address || '';
                document.getElementById('host').value = data.host || '';
                document.getElementById('port').value = data.port || '';
                document.getElementById('encryption').value = data.encryption || 'tls';
                document.getElementById('username').value = data.username || '';
                document.getElementById('daily_limit').value = data.daily_limit || 500;
                document.getElementById('is_active').checked = data.is_active || false;
                document.getElementById('password').value = '';
                document.getElementById('password').removeAttribute('required');
                document.getElementById('passwordHint').style.display = 'block';
                document.getElementById('smtpModal').classList.remove('hidden');
                document.getElementById('smtpModal').classList.add('flex');
            } catch (error) {
                showToast('Error', 'Failed to load SMTP data', 'error');
            }
        }

        async function testAllSmtp() {
            const isDark = document.documentElement.classList.contains('dark');
            const result = await Swal.fire({
                title: 'Test All SMTPs?',
                text: 'This will test all your SMTP configurations one by one.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, test all!',
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f1f5f9' : '#1e2937'
            });
            
            if (result.isConfirmed) {
                showToast('Testing', 'Testing all SMTP configurations...', 'info');
                const btns = document.querySelectorAll('.test-btn');
                for (const btn of btns) {
                    const id = btn.getAttribute('data-id');
                    await testSmtp(id);
                    await new Promise(r => setTimeout(r, 500));
                }
            }
        }

        function refreshData() {
            location.reload();
        }

        // Form Submit
        document.getElementById('smtpForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            if (isLoading) return;
            isLoading = true;
            
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="material-symbols-outlined text-sm animate-spin mr-1">progress_activity</span>Saving...';
            submitBtn.classList.add('btn-loading');
            
            const id = document.getElementById('smtpId').value;
            const url = id ? `/smtp/${id}` : '/smtp';
            const method = id ? 'PUT' : 'POST';
            
            const formData = new FormData(this);
            const data = {};
            for (let [key, value] of formData.entries()) {
                if (key === 'is_active') {
                    data[key] = value === 'on';
                } else if (key !== '_token' && key !== 'smtp_id') {
                    data[key] = value;
                }
            }
            
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: { 'X-CSRF-TOKEN': authToken, 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showToast('Success!', id ? 'SMTP updated successfully' : 'SMTP added successfully', 'success');
                    closeModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast('Error', result.message || 'Something went wrong', 'error');
                }
            } catch (error) {
                showToast('Error', 'Network error occurred', 'error');
            } finally {
                isLoading = false;
                submitBtn.innerHTML = originalText;
                submitBtn.classList.remove('btn-loading');
            }
        });

        // Search Filter
        document.getElementById('searchInput')?.addEventListener('input', function(e) {
            const search = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#smtpTableBody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(search)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            const emptyState = document.getElementById('emptyState');
            if (emptyState && rows.length > 0) {
                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        });

        // Highlight active sidebar link
        function highlightActiveLink() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.classList.remove('active');
                const href = link.getAttribute('href');
                if (href === currentPath || (href !== '/' && currentPath.startsWith(href.replace(/\/$/, '')))) {
                    link.classList.add('active');
                }
            });
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            initThemeToggle();
            initCharts();
            highlightActiveLink();
        });
    </script>
</body>
</html>
