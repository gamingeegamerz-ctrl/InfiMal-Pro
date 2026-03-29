<!DOCTYPE html>
<html lang="en">
<head>
    <script>(function(){if(localStorage.getItem('infimal_theme')==='dark'){document.documentElement.classList.add('dark');}})();</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - InfiMal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        /* Dark mode toggle button */
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
    </style>
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
                    <a class="nav-link active flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/dashboard') }}">
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
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/smtp') }}">
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
                                <input type="text" placeholder="Search campaigns, subscribers..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 placeholder-gray-500 dark:placeholder-slate-500" />
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="window.location.href='{{ url('/campaigns/create') }}'" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg font-semibold text-sm hover-glow transition-all duration-300">
                                New Campaign
                            </button>
                            <button onclick="window.location.href='{{ url('/subscribers/create') }}'" class="border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-slate-300 px-6 py-2 rounded-lg font-semibold text-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition-all duration-300">
                                Add Subscriber
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
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
                            <p class="text-gray-600 dark:text-slate-300 mb-4">Your email marketing dashboard with real-time analytics. Member since {{ Auth::user()->created_at->format('M Y') }}</p>
                            <div class="flex items-center gap-6">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined {{ Auth::user()->email_verified_at ? 'text-green-500' : 'text-yellow-500' }} text-xl">
                                        {{ Auth::user()->email_verified_at ? 'verified' : 'warning' }}
                                    </span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        {{ Auth::user()->email_verified_at ? 'Email Verified' : 'Email Not Verified' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500 text-xl">schedule</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        Last login: {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'First time' }}
                                    </span>
                                </div>
                                <div id="smtp-status" class="flex items-center gap-2">
                                    <span class="animate-pulse w-2 h-2 bg-gray-400 dark:bg-slate-600 rounded-full"></span>
                                    <span class="text-gray-600 dark:text-slate-300 text-xs font-medium">Checking SMTP...</span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden lg:block">
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 rounded-2xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-6xl">analytics</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Stat Card 1 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Total Subscribers</h3>
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">group</span>
                            </div>
                        </div>
                        <p id="totalSubscribers" class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ number_format($totalSubscribers ?? 0) }}</p>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">{{ ($subscriberGrowth ?? 0) >= 0 ? '+' : '' }}{{ $subscriberGrowth ?? 0 }}% growth</p>
                    </div>
                    
                    <!-- Stat Card 2 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Total Campaigns</h3>
                            <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">campaign</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $campaignsTotal ?? 0 }}</p>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">{{ ($campaignGrowth ?? 0) >= 0 ? '+' : '' }}{{ $campaignGrowth ?? 0 }}% growth</p>
                    </div>
                    
                    <!-- Stat Card 3 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Emails Sent</h3>
                            <div class="p-2 bg-green-100 dark:bg-green-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">send</span>
                            </div>
                        </div>
                        <p id="totalEmailsSent" class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ number_format($totalEmailsSent ?? 0) }}</p>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">{{ $emailsToday ?? 0 }} today</p>
                    </div>
                    
                    <!-- Stat Card 4 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Open Rate</h3>
                            <div class="p-2 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400">visibility</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $openRate ?? 0 }}%</p>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">{{ ($openGrowth ?? 0) >= 0 ? '+' : '' }}{{ $openGrowth ?? 0 }}% trend</p>
                    </div>
                    
                    <!-- Stat Card 5 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Click Rate</h3>
                            <div class="p-2 bg-red-100 dark:bg-red-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-red-600 dark:text-red-400">click</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $clickRate ?? 0 }}%</p>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">{{ number_format($totalClicks ?? 0) }} total</p>
                    </div>
                    
                    <!-- Stat Card 6 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Engagement</h3>
                            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400">trending_up</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $engagementRate ?? 0 }}%</p>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">{{ ($engagementGrowth ?? 0) >= 0 ? '+' : '' }}{{ $engagementGrowth ?? 0 }}% growth</p>
                    </div>
                </div>

                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Today's Activity -->
                    <div class="lg:col-span-1">
                        <div class="glass-card rounded-2xl p-6 shadow-lg border-2 border-blue-100 dark:border-slate-700">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-6">Today's Activity</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                                    <div>
                                        <p class="text-gray-600 dark:text-slate-300 text-sm font-medium">Emails Sent</p>
                                        <p id="emailsToday" class="text-gray-900 dark:text-white text-2xl font-bold">{{ $emailsToday ?? 0 }}</p>
                                    </div>
                                    <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">send</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-xl">
                                    <div>
                                        <p class="text-gray-600 dark:text-slate-300 text-sm font-medium">Opens</p>
                                        <p id="opensToday" class="text-gray-900 dark:text-white text-2xl font-bold">{{ $opensToday ?? 0 }}</p>
                                    </div>
                                    <div class="p-2 bg-green-100 dark:bg-green-900/50 rounded-lg">
                                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">visibility</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                                    <div>
                                        <p class="text-gray-600 dark:text-slate-300 text-sm font-medium">Clicks</p>
                                        <p id="clicksToday" class="text-gray-900 dark:text-white text-2xl font-bold">{{ $clicksToday ?? 0 }}</p>
                                    </div>
                                    <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg">
                                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">click</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl">
                                    <div>
                                        <p class="text-gray-600 dark:text-slate-300 text-sm font-medium">Active Campaigns</p>
                                        <p id="activeCampaigns" class="text-gray-900 dark:text-white text-2xl font-bold">{{ $activeCampaigns ?? 0 }}</p>
                                    </div>
                                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg">
                                        <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400">campaign</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="lg:col-span-2">
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-6">Quick Actions</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <a href="{{ url('/campaigns/create') }}" class="group p-6 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-xl hover:shadow-lg transition-all duration-300 hover-glow">
                                    <div class="p-3 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl w-fit mb-4">
                                        <span class="material-symbols-outlined text-white text-2xl">campaign</span>
                                    </div>
                                    <h4 class="text-gray-900 dark:text-white font-bold text-base mb-1">Create Campaign</h4>
                                    <p class="text-gray-600 dark:text-slate-300 text-sm">Start a new email campaign</p>
                                </a>
                                <a href="{{ url('/subscribers/import') }}" class="group p-6 bg-gradient-to-br from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 rounded-xl hover:shadow-lg transition-all duration-300 hover-glow">
                                    <div class="p-3 bg-gradient-to-br from-green-600 to-blue-600 rounded-xl w-fit mb-4">
                                        <span class="material-symbols-outlined text-white text-2xl">upload</span>
                                    </div>
                                    <h4 class="text-gray-900 dark:text-white font-bold text-base mb-1">Import Subscribers</h4>
                                    <p class="text-gray-600 dark:text-slate-300 text-sm">Upload your contact list</p>
                                </a>
                                <a href="{{ url('/smtp') }}" class="group p-6 bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-xl hover:shadow-lg transition-all duration-300 hover-glow">
                                    <div class="p-3 bg-gradient-to-br from-yellow-600 to-orange-600 rounded-xl w-fit mb-4">
                                        <span class="material-symbols-outlined text-white text-2xl">dns</span>
                                    </div>
                                    <h4 class="text-gray-900 dark:text-white font-bold text-base mb-1">SMTP Settings</h4>
                                    <p class="text-gray-600 dark:text-slate-300 text-sm">Configure email delivery</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const authToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        // Dark/Light Mode Toggle
        function initThemeToggle() {
            const themeToggle = document.getElementById('themeToggle');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const savedTheme = localStorage.getItem('infimal_theme');
            
            // Set initial theme
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            themeToggle.addEventListener('click', () => {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('infimal_theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('infimal_theme', 'dark');
                }
            });
        }

        async function loadSmtpStatus() {
            const statusEl = document.getElementById('smtp-status');
            try {
                const response = await fetch('/api/smtp/credentials', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': authToken
                    },
                    credentials: 'same-origin'
                });
                
                if (response.ok) {
                    const data = await response.json();
                    if (statusEl) {
                        statusEl.innerHTML = `
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span class="text-gray-600 dark:text-slate-300 text-xs font-medium">SMTP Connected</span>
                        `;
                    }
                } else if (response.status === 403) {
                    if (statusEl) {
                        statusEl.innerHTML = `
                            <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                            <span class="text-gray-600 dark:text-slate-300 text-xs font-medium">No License</span>
                        `;
                    }
                } else if (response.status === 404) {
                    if (statusEl) {
                        statusEl.innerHTML = `
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            <span class="text-gray-600 dark:text-slate-300 text-xs font-medium">SMTP Not Setup</span>
                        `;
                    }
                }
            } catch (error) {
                if (statusEl) {
                    statusEl.innerHTML = `
                        <span class="w-2 h-2 bg-gray-400 dark:bg-slate-600 rounded-full"></span>
                        <span class="text-gray-600 dark:text-slate-300 text-xs font-medium">SMTP Offline</span>
                    `;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initThemeToggle();
            setTimeout(loadSmtpStatus, 1000);
            
            // Highlight active sidebar link based on current URL
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                const href = link.getAttribute('href');
                if (href === currentPath || 
                    (href !== '/' && currentPath.startsWith(href.replace(/\/$/, '')))) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
