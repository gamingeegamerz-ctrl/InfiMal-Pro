<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Campaigns - InfiMal</title>
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
            border: 1px solid rgba(255, 255, 255, 0.1);
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
        /* Campaign status badges */
        .campaign-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-draft { 
            background: rgba(107, 114, 128, 0.2); 
            color: #6b7280; 
        }
        .dark .status-draft { 
            background: rgba(107, 114, 128, 0.3); 
            color: #9ca3af; 
        }
        .status-scheduled { 
            background: rgba(59, 130, 246, 0.2); 
            color: #3b82f6; 
        }
        .dark .status-scheduled { 
            background: rgba(59, 130, 246, 0.3); 
            color: #60a5fa; 
        }
        .status-sending { 
            background: rgba(245, 158, 11, 0.2); 
            color: #f59e0b; 
        }
        .dark .status-sending { 
            background: rgba(245, 158, 11, 0.3); 
            color: #fbbf24; 
        }
        .status-sent { 
            background: rgba(34, 197, 94, 0.2); 
            color: #22c55e; 
        }
        .dark .status-sent { 
            background: rgba(34, 197, 94, 0.3); 
            color: #4ade80; 
        }
        .status-failed { 
            background: rgba(239, 68, 68, 0.2); 
            color: #ef4444; 
        }
        .dark .status-failed { 
            background: rgba(239, 68, 68, 0.3); 
            color: #f87171; 
        }
        /* Progress bar */
        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
        }
        .dark .progress-bar {
            background: #374151;
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s ease;
        }
        .progress-blue { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
        .progress-green { background: linear-gradient(90deg, #10b981, #34d399); }
        .progress-purple { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
        .progress-orange { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        /* Modal styles */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }
        .modal-hidden {
            display: none;
        }
        /* Campaign cards */
        .campaign-card {
            transition: all 0.3s ease;
        }
        .campaign-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .dark .campaign-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        .dark .skeleton {
            background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        /* Status indicators */
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        .status-draft-indicator { background: #9ca3af; }
        .status-scheduled-indicator { background: #3b82f6; }
        .status-sending-indicator { background: #f59e0b; }
        .status-sent-indicator { background: #10b981; }
        .status-failed-indicator { background: #ef4444; }
        /* Dropdown */
        .dropdown {
            position: relative;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px;
            z-index: 50;
            min-width: 160px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .dark .dropdown-content {
            background: #1f2937;
            border-color: #374151;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
        .dropdown:hover .dropdown-content {
            display: block;
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
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/dashboard') }}">
                        <span class="material-symbols-outlined text-xl">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/subscribers') }}">
                        <span class="material-symbols-outlined text-xl">group</span>
                        <span>Subscribers</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/lists') }}">
                        <span class="material-symbols-outlined text-xl">list_alt</span>
                        <span>Lists</span>
                    </a>
                    <a class="nav-link active flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 dark:text-white font-medium text-sm" href="{{ url('/campaigns') }}">
                        <span class="material-symbols-outlined text-xl">campaign</span>
                        <span>Campaigns</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/messages') }}">
                        <span class="material-symbols-outlined text-xl">chat</span>
                        <span>Messages</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/smtp') }}">
                        <span class="material-symbols-outlined text-xl">dns</span>
                        <span>SMTP Settings</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/billing') }}">
                        <span class="material-symbols-outlined text-xl">receipt_long</span>
                        <span>Billing</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/profile') }}">
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
                                <input type="text" id="searchCampaigns" placeholder="Search campaigns..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100" />
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <!-- FIX 1: New Campaign button now links to /campaigns/create -->
                            <a href="http://127.0.0.1:8000/campaigns/create" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg font-semibold text-sm hover-glow transition-all duration-300 inline-block">
                                <span class="material-symbols-outlined align-middle mr-2 text-sm">add</span>
                                New Campaign
                            </a>
                            <button onclick="showImportModal()" class="border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 px-6 py-2 rounded-lg font-semibold text-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition-all duration-300">
                                <span class="material-symbols-outlined align-middle mr-2 text-sm">upload</span>
                                Import CSV
                            </button>
                            <button onclick="showNotifications()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors relative" id="notificationBtn">
                                <span class="material-symbols-outlined text-gray-600 dark:text-slate-400">notifications</span>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
                            </button>
                            <div class="dropdown">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold cursor-pointer">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="dropdown-content">
                                    <a href="{{ url('/profile') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded">
                                        <span class="material-symbols-outlined text-sm">person</span>
                                        Profile
                                    </a>
                                    <a href="{{ url('/settings') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded">
                                        <span class="material-symbols-outlined text-sm">settings</span>
                                        Settings
                                    </a>
                                    <div class="border-t border-gray-200 dark:border-slate-700 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded">
                                            <span class="material-symbols-outlined text-sm">logout</span>
                                            Logout
                                        </button>
                                    </form>
                                </div>
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
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Campaign Management</h1>
                            <p class="text-gray-600 dark:text-slate-300 mb-4">Create, manage, and track your email campaigns with real-time analytics</p>
                            <div class="flex flex-wrap items-center gap-4">
                                <div class="flex items-center gap-2 bg-white dark:bg-slate-800 px-3 py-2 rounded-lg">
                                    <span class="material-symbols-outlined text-green-500 text-xl">verified</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        Total Campaigns: <span class="font-bold text-gray-900 dark:text-white">{{ $totalCampaigns ?? count($campaigns ?? []) }}</span>
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 bg-white dark:bg-slate-800 px-3 py-2 rounded-lg">
                                    <span class="material-symbols-outlined text-blue-500 text-xl">send</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        Sent: <span class="font-bold text-gray-900 dark:text-white">{{ $sentCount ?? 0 }}</span>
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 bg-white dark:bg-slate-800 px-3 py-2 rounded-lg">
                                    <span class="material-symbols-outlined text-purple-500 text-xl">trending_up</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        Avg Open Rate: <span class="font-bold text-gray-900 dark:text-white">{{ $avgOpenRate ?? 0 }}%</span>
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 bg-white dark:bg-slate-800 px-3 py-2 rounded-lg">
                                    <span class="material-symbols-outlined text-orange-500 text-xl">click</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        Avg Click Rate: <span class="font-bold text-gray-900 dark:text-white">{{ $avgClickRate ?? 0 }}%</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden lg:block">
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 rounded-2xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-6xl">campaign</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Stat Card 1 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300 campaign-card">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Total Campaigns</h3>
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">campaign</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $totalCampaigns ?? count($campaigns ?? []) }}</p>
                        <div class="flex items-center justify-between">
                            <p class="text-green-600 dark:text-green-400 text-sm font-medium">{{ $draftCount ?? 0 }} drafts</p>
                            <div class="flex items-center text-sm">
                                <span class="material-symbols-outlined text-green-500 text-sm mr-1">trending_up</span>
                                <span class="text-green-600 dark:text-green-400">+{{ $growthRate ?? 0 }}%</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stat Card 2 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300 campaign-card">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Active Campaigns</h3>
                            <div class="p-2 bg-green-100 dark:bg-green-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">rocket_launch</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $activeCount ?? 0 }}</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="status-indicator status-scheduled-indicator"></span>
                                <span class="text-gray-600 dark:text-slate-400 text-sm">{{ $scheduledCount ?? 0 }} scheduled</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="status-indicator status-sending-indicator"></span>
                                <span class="text-gray-600 dark:text-slate-400 text-sm">{{ $sendingCount ?? 0 }} sending</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stat Card 3 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300 campaign-card">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Avg. Open Rate</h3>
                            <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">visibility</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $avgOpenRate ?? 0 }}%</p>
                        <div class="space-y-2">
                            <div class="progress-bar">
                                <div class="progress-bar-fill progress-purple" style="width: {{ min($avgOpenRate ?? 0, 100) }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 dark:text-slate-400">
                                <span>Industry avg: 20%</span>
                                <span class="{{ ($avgOpenRate ?? 0) >= 20 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ ($avgOpenRate ?? 0) >= 20 ? '+' : '' }}{{ number_format(($avgOpenRate ?? 0) - 20, 1) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stat Card 4 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300 campaign-card">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Avg. Click Rate</h3>
                            <div class="p-2 bg-orange-100 dark:bg-orange-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-orange-600 dark:text-orange-400">click</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $avgClickRate ?? 0 }}%</p>
                        <div class="space-y-2">
                            <div class="progress-bar">
                                <div class="progress-bar-fill progress-orange" style="width: {{ min($avgClickRate ?? 0, 100) }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 dark:text-slate-400">
                                <span>Industry avg: 3%</span>
                                <span class="{{ ($avgClickRate ?? 0) >= 3 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ ($avgClickRate ?? 0) >= 3 ? '+' : '' }}{{ number_format(($avgClickRate ?? 0) - 3, 1) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Campaigns Table -->
                    <div class="lg:col-span-3">
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-gray-900 dark:text-white font-bold text-lg">All Campaigns</h3>
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <select id="statusFilter" onchange="filterByStatus(this.value)" class="bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-700 dark:text-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none pr-8">
                                            <option value="all">All Status</option>
                                            <option value="draft">Draft</option>
                                            <option value="scheduled">Scheduled</option>
                                            <option value="sending">Sending</option>
                                            <option value="sent">Sent</option>
                                            <option value="failed">Failed</option>
                                        </select>
                                        <span class="material-symbols-outlined absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">expand_more</span>
                                    </div>
                                    <div class="relative">
                                        <select id="typeFilter" onchange="filterByType(this.value)" class="bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-700 dark:text-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none pr-8">
                                            <option value="all">All Types</option>
                                            <option value="regular">Regular</option>
                                            <option value="automated">Automated</option>
                                            <option value="abtest">A/B Test</option>
                                            <option value="transactional">Transactional</option>
                                        </select>
                                        <span class="material-symbols-outlined absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">expand_more</span>
                                    </div>
                                    <button onclick="exportCampaigns()" class="flex items-center gap-2 px-3 py-2 border border-gray-300 dark:border-slate-700 rounded-lg text-gray-700 dark:text-slate-300 text-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                        <span class="material-symbols-outlined text-sm">download</span>
                                        Export
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Campaigns Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead>
                                        <tr class="text-gray-600 dark:text-slate-400 text-sm border-b border-gray-200 dark:border-slate-700">
                                            <th class="pb-3 text-left font-semibold pl-4">Campaign Name</th>
                                            <th class="pb-3 text-left font-semibold">Status</th>
                                            <th class="pb-3 text-left font-semibold">Recipients</th>
                                            <th class="pb-3 text-left font-semibold">Performance</th>
                                            <th class="pb-3 text-left font-semibold">Created</th>
                                            <th class="pb-3 text-left font-semibold pr-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="campaignsTableBody">
                                        @php
                                            // Ensure $campaigns is an array/collection
                                            $campaignsData = $campaigns ?? [];
                                            $campaignsCount = is_countable($campaignsData) ? count($campaignsData) : 0;
                                        @endphp
                                        
                                        @if($campaignsCount > 0)
                                            @foreach($campaignsData as $campaign)
                                            <tr class="border-b border-gray-100 dark:border-slate-800 last:border-0 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors campaign-card">
                                                <td class="py-4 pl-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/50">
                                                            @if(isset($campaign->type) && $campaign->type == 'automated')
                                                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm">schedule</span>
                                                            @elseif(isset($campaign->type) && $campaign->type == 'abtest')
                                                            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-sm">science</span>
                                                            @else
                                                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm">campaign</span>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <p class="text-gray-900 dark:text-white font-medium">{{ $campaign->name ?? 'Untitled Campaign' }}</p>
                                                            <p class="text-gray-600 dark:text-slate-400 text-sm">{{ $campaign->subject ?? 'No subject' }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-4">
                                                    <div class="flex items-center">
                                                        <span class="status-indicator status-{{ $campaign->status ?? 'draft' }}-indicator"></span>
                                                        <span class="campaign-status status-{{ $campaign->status ?? 'draft' }}">
                                                            {{ ucfirst($campaign->status ?? 'draft') }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="py-4">
                                                    <div>
                                                        <span class="text-gray-900 dark:text-white font-medium">{{ number_format($campaign->total_recipients ?? 0) }}</span>
                                                        <p class="text-gray-600 dark:text-slate-400 text-xs">
                                                            @if(isset($campaign->list) && $campaign->list)
                                                                {{ $campaign->list->name ?? 'No list' }}
                                                            @else
                                                                No list assigned
                                                            @endif
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="py-4">
                                                    <div class="flex items-center gap-4">
                                                        <div>
                                                            <p class="text-gray-900 dark:text-white text-sm font-medium">
                                                                {{ $campaign->opens_count ?? 0 }}
                                                                <span class="text-green-600 dark:text-green-400 text-xs">
                                                                    @if(($campaign->total_recipients ?? 0) > 0)
                                                                        ({{ number_format(($campaign->opens_count ?? 0) / $campaign->total_recipients * 100, 1) }}%)
                                                                    @else
                                                                        (0%)
                                                                    @endif
                                                                </span>
                                                            </p>
                                                            <p class="text-gray-600 dark:text-slate-400 text-xs">Opens</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-gray-900 dark:text-white text-sm font-medium">
                                                                {{ $campaign->clicks_count ?? 0 }}
                                                                <span class="text-green-600 dark:text-green-400 text-xs">
                                                                    @if(($campaign->total_recipients ?? 0) > 0)
                                                                        ({{ number_format(($campaign->clicks_count ?? 0) / $campaign->total_recipients * 100, 1) }}%)
                                                                    @else
                                                                        (0%)
                                                                    @endif
                                                                </span>
                                                            </p>
                                                            <p class="text-gray-600 dark:text-slate-400 text-xs">Clicks</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-4">
                                                    <div>
                                                        <p class="text-gray-900 dark:text-white text-sm">
                                                            @if(isset($campaign->created_at))
                                                                {{ $campaign->created_at->format('M d, Y') }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </p>
                                                        @if(isset($campaign->status) && $campaign->status == 'scheduled' && isset($campaign->scheduled_at))
                                                        <p class="text-blue-600 dark:text-blue-400 text-xs">
                                                            {{ \Carbon\Carbon::parse($campaign->scheduled_at)->format('M d, H:i') }}
                                                        </p>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="py-4 pr-4">
                                                    <div class="flex items-center gap-2">
                                                        @if(isset($campaign->status) && $campaign->status == 'draft')
                                                        <button onclick="previewCampaign({{ $campaign->id ?? 1 }})" class="p-2 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors" title="Preview">
                                                            <span class="material-symbols-outlined text-sm">visibility</span>
                                                        </button>
                                                        @else
                                                        <a href="{{ url('/campaigns/' . ($campaign->id ?? 1)) }}" class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors" title="View">
                                                            <span class="material-symbols-outlined text-sm">visibility</span>
                                                        </a>
                                                        @endif
                                                        
                                                        @if(isset($campaign->status) && in_array($campaign->status, ['draft', 'scheduled']))
                                                        <button onclick="editCampaign({{ $campaign->id ?? 1 }})" class="p-2 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors" title="Edit">
                                                            <span class="material-symbols-outlined text-sm">edit</span>
                                                        </button>
                                                        @endif
                                                        
                                                        @if(isset($campaign->status) && in_array($campaign->status, ['sent', 'sending']))
                                                        <button onclick="viewAnalytics({{ $campaign->id ?? 1 }})" class="p-2 rounded-lg bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800 transition-colors" title="Analytics">
                                                            <span class="material-symbols-outlined text-sm">analytics</span>
                                                        </button>
                                                        @endif
                                                        
                                                        @if(isset($campaign->status) && $campaign->status == 'scheduled')
                                                        <button onclick="sendNow({{ $campaign->id ?? 1 }})" class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors" title="Send Now">
                                                            <span class="material-symbols-outlined text-sm">send</span>
                                                        </button>
                                                        @endif
                                                        
                                                        <div class="dropdown relative">
                                                            <button class="p-2 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors" title="More">
                                                                <span class="material-symbols-outlined text-sm">more_vert</span>
                                                            </button>
                                                            <div class="dropdown-content left-auto right-0">
                                                                <button onclick="duplicateCampaign({{ $campaign->id ?? 1 }})" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded w-full">
                                                                    <span class="material-symbols-outlined text-sm">content_copy</span>
                                                                    Duplicate
                                                                </button>
                                                                @if(isset($campaign->status) && $campaign->status == 'draft')
                                                                <button onclick="scheduleCampaign({{ $campaign->id ?? 1 }})" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded w-full">
                                                                    <span class="material-symbols-outlined text-sm">schedule</span>
                                                                    Schedule
                                                                </button>
                                                                @endif
                                                                <button onclick="deleteCampaign({{ $campaign->id ?? 1 }})" class="flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded w-full">
                                                                    <span class="material-symbols-outlined text-sm">delete</span>
                                                                    Delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                        <tr>
                                            <td colspan="6" class="py-12 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <span class="material-symbols-outlined text-gray-400 dark:text-slate-600 text-4xl">campaign</span>
                                                    <p class="text-gray-600 dark:text-slate-400 mt-2">No campaigns yet</p>
                                                    <!-- FIX 3: Also fix the button in empty state -->
                                                    <a href="http://127.0.0.1:8000/campaigns/create" class="mt-3 px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg text-sm font-semibold hover-glow transition-all duration-300 inline-block">
                                                        <span class="material-symbols-outlined align-middle mr-2 text-sm">add</span>
                                                        Create Your First Campaign
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            @php
                                // Simple pagination info if needed
                                $totalCampaignsCount = $campaignsCount;
                                $showingCampaigns = $totalCampaignsCount > 0 ? "1 to $totalCampaignsCount" : '0';
                            @endphp
                            
                            <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200 dark:border-slate-700">
                                <div class="text-gray-600 dark:text-slate-400 text-sm">
                                    Showing {{ $showingCampaigns }} of {{ $totalCampaignsCount }} campaigns
                                </div>
                                @if($totalCampaignsCount > 10)
                                <div class="flex items-center gap-2">
                                    <!-- Simple pagination buttons -->
                                    <button class="px-3 py-1 text-sm rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700">
                                        Previous
                                    </button>
                                    <button class="px-3 py-1 text-sm rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800">
                                        1
                                    </button>
                                    <button class="px-3 py-1 text-sm rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700">
                                        Next
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Side Panel -->
                    <div class="lg:col-span-1 flex flex-col gap-6">
                        <!-- Quick Actions -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <!-- FIX 2: Quick Actions New Campaign button -->
                                <a href="http://127.0.0.1:8000/campaigns/create" class="w-full flex items-center gap-3 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-800/30 transition-colors">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">add</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">New Campaign</span>
                                </a>
                                <button onclick="showImportModal()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">upload</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">Import CSV</span>
                                </button>
                                <a href="{{ url('/templates') }}" class="w-full flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">description</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">Templates</span>
                                </a>
                                <a href="{{ url('/analytics') }}" class="w-full flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                                    <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400">analytics</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">Analytics</span>
                                </a>
                                <button onclick="showScheduleModal()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">schedule</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">Schedule Campaign</span>
                                </button>
                            </div>
                        </div>

                        <!-- Campaign Performance -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">Campaign Performance</h3>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-gray-600 dark:text-slate-400 text-sm">Open Rate</span>
                                        <span class="text-gray-900 dark:text-white text-sm font-medium">{{ $avgOpenRate ?? 0 }}%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-bar-fill progress-blue" style="width: {{ min($avgOpenRate ?? 0, 100) }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-gray-600 dark:text-slate-400 text-sm">Click Rate</span>
                                        <span class="text-gray-900 dark:text-white text-sm font-medium">{{ $avgClickRate ?? 0 }}%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-bar-fill progress-green" style="width: {{ min($avgClickRate ?? 0, 100) }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-gray-600 dark:text-slate-400 text-sm">Bounce Rate</span>
                                        <span class="text-gray-900 dark:text-white text-sm font-medium">{{ $bounceRate ?? 0 }}%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-bar-fill progress-orange" style="width: {{ min($bounceRate ?? 0, 100) }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-gray-600 dark:text-slate-400 text-sm">Unsubscribe Rate</span>
                                        <span class="text-gray-900 dark:text-white text-sm font-medium">{{ $unsubscribeRate ?? 0 }}%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-bar-fill progress-purple" style="width: {{ min($unsubscribeRate ?? 0, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Campaigns -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">Recent Campaigns</h3>
                            <div class="space-y-3">
                                @php
                                    $recentCampaigns = $campaigns ? (is_countable($campaigns) ? array_slice($campaigns->toArray(), 0, 3) : []) : [];
                                @endphp
                                
                                @if(count($recentCampaigns) > 0)
                                    @foreach($recentCampaigns as $campaign)
                                    <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50 dark:bg-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors cursor-pointer" onclick="viewCampaign({{ $campaign['id'] ?? 1 }})">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-blue-500 text-sm">campaign</span>
                                            <div>
                                                <span class="text-gray-900 dark:text-white text-sm truncate block max-w-[120px]">{{ Str::limit($campaign['name'] ?? 'Untitled', 20) }}</span>
                                                <span class="campaign-status status-{{ $campaign['status'] ?? 'draft' }} text-xs mt-1">
                                                    {{ ucfirst($campaign['status'] ?? 'draft') }}
                                                </span>
                                            </div>
                                        </div>
                                        <span class="text-gray-600 dark:text-slate-400 text-xs">
                                            @if(isset($campaign['status']) && $campaign['status'] == 'sent' && isset($campaign['sent_at']))
                                                {{ \Carbon\Carbon::parse($campaign['sent_at'])->format('M d') }}
                                            @elseif(isset($campaign['status']) && $campaign['status'] == 'scheduled' && isset($campaign['scheduled_at']))
                                                {{ \Carbon\Carbon::parse($campaign['scheduled_at'])->format('M d') }}
                                            @else
                                                {{ isset($campaign['created_at']) ? \Carbon\Carbon::parse($campaign['created_at'])->format('M d') : 'N/A' }}
                                            @endif
                                        </span>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-4">
                                        <span class="material-symbols-outlined text-gray-400 dark:text-slate-600 text-3xl">bar_chart</span>
                                        <p class="text-gray-600 dark:text-slate-400 mt-2">No recent campaigns</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Chart -->
                <div class="glass-card rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-gray-900 dark:text-white font-bold text-lg">Campaign Performance Overview</h3>
                        <div class="flex items-center gap-2">
                            <button onclick="updateChart('week')" class="px-3 py-1 text-sm rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800">Week</button>
                            <button onclick="updateChart('month')" class="px-3 py-1 text-sm rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700">Month</button>
                            <button onclick="updateChart('year')" class="px-3 py-1 text-sm rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700">Year</button>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Create Campaign Modal - Still needed for other functionality but won't be triggered by New Campaign button -->
    <div id="createCampaignModal" class="modal-backdrop modal-hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-2xl shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-gray-900 dark:text-white text-xl font-bold">Create New Campaign</h3>
                <button onclick="closeModal('createCampaignModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
            </div>
            <form id="campaignForm" action="{{ route('campaigns.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Campaign Name</label>
                        <input type="text" name="name" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter campaign name" required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Email Subject</label>
                        <input type="text" name="subject" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter email subject" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Campaign Type</label>
                        <select name="type" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="regular">Regular Campaign</option>
                            <option value="automated">Automated Series</option>
                            <option value="abtest">A/B Test</option>
                            <option value="transactional">Transactional</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Email List</label>
                        <select name="list_id" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">Select a list</option>
                            @if(isset($lists) && is_countable($lists) && count($lists) > 0)
                                @foreach($lists as $list)
                                    <option value="{{ $list->id }}">{{ $list->name }} ({{ $list->subscribers_count ?? 0 }} subscribers)</option>
                                @endforeach
                            @else
                                <option value="">No lists available</option>
                            @endif
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Optional campaign description"></textarea>
                    </div>
                    
                    <div class="md:col-span-2 pt-4 border-t border-gray-200 dark:border-slate-700">
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="closeModal('createCampaignModal')" class="px-6 py-2 border border-gray-300 dark:border-slate-700 rounded-lg text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                                Create Campaign
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Import Campaign Modal -->
    <div id="importCampaignModal" class="modal-backdrop modal-hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-md shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-gray-900 dark:text-white text-xl font-bold">Import Campaigns</h3>
                <button onclick="closeModal('importCampaignModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
            </div>
            <div class="space-y-4">
                <div class="border-2 border-dashed border-gray-300 dark:border-slate-700 rounded-lg p-8 text-center">
                    <span class="material-symbols-outlined text-gray-400 dark:text-slate-600 text-4xl mb-3">cloud_upload</span>
                    <p class="text-gray-600 dark:text-slate-400 mb-2">Drop your CSV file here or click to browse</p>
                    <p class="text-gray-500 dark:text-slate-500 text-sm">Supports .csv files up to 10MB</p>
                    <input type="file" id="csvFile" accept=".csv" class="hidden">
                    <button onclick="document.getElementById('csvFile').click()" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold">
                        Select File
                    </button>
                </div>
                
                <div class="pt-4 border-t border-gray-200 dark:border-slate-700">
                    <h4 class="text-gray-700 dark:text-slate-300 font-medium mb-2">CSV Format</h4>
                    <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3 font-mono text-sm">
                        name,subject,type,list_id,status<br>
                        "Welcome Series","Welcome to our service","automated",1,"draft"<br>
                        "Weekly Newsletter","This week's updates","regular",2,"scheduled"
                    </div>
                </div>
                
                <div class="pt-4">
                    <div class="flex justify-end gap-3">
                        <button onclick="closeModal('importCampaignModal')" class="px-6 py-2 border border-gray-300 dark:border-slate-700 rounded-lg text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                            Cancel
                        </button>
                        <button onclick="processImport()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                            Import
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Campaign Modal -->
    <div id="scheduleCampaignModal" class="modal-backdrop modal-hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-md shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-gray-900 dark:text-white text-xl font-bold">Schedule Campaign</h3>
                <button onclick="closeModal('scheduleCampaignModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Select Campaign</label>
                    <select id="scheduleCampaignSelect" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select a campaign</option>
                        @if(isset($campaigns) && is_countable($campaigns))
                            @foreach($campaigns as $campaign)
                                @if(isset($campaign->status) && $campaign->status == 'draft')
                                    <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Schedule Date & Time</label>
                    <input type="datetime-local" id="scheduleDateTime" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="flex items-center gap-2 text-gray-700 dark:text-slate-300 text-sm">
                        <input type="checkbox" id="sendTestEmail" class="rounded">
                        <span>Send test email before scheduling</span>
                    </label>
                </div>
                
                <div class="pt-4">
                    <div class="flex justify-end gap-3">
                        <button onclick="closeModal('scheduleCampaignModal')" class="px-6 py-2 border border-gray-300 dark:border-slate-700 rounded-lg text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                            Cancel
                        </button>
                        <button onclick="confirmSchedule()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                            Schedule
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Theme Toggle - Fixed
        function initThemeToggle() {
            const themeToggle = document.getElementById('themeToggle');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const savedTheme = localStorage.getItem('infimal_theme');
            
            // Set initial theme
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
                console.log('Dark mode enabled');
            } else {
                document.documentElement.classList.remove('dark');
                console.log('Light mode enabled');
            }
            
            themeToggle.addEventListener('click', () => {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('infimal_theme', 'light');
                    console.log('Switched to light mode');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('infimal_theme', 'dark');
                    console.log('Switched to dark mode');
                }
            });
        }

        // Performance Chart
        let performanceChart;
        function initPerformanceChart() {
            const ctx = document.getElementById('performanceChart');
            if (!ctx) return;
            
            const chartData = {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Open Rate',
                    data: [22, 25, 28, 24, 27, 23, 26],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Click Rate',
                    data: [3, 4, 5, 3, 4, 2, 3],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            };
            
            performanceChart = new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#6b7280'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#6b7280',
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#6b7280'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    }
                }
            });
            
            // Update chart theme when dark mode changes
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'class') {
                        updateChartTheme();
                    }
                });
            });
            
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
            
            function updateChartTheme() {
                if (!performanceChart) return;
                
                if (document.documentElement.classList.contains('dark')) {
                    performanceChart.options.scales.y.ticks.color = '#94a3b8';
                    performanceChart.options.scales.x.ticks.color = '#94a3b8';
                    performanceChart.options.scales.y.grid.color = 'rgba(255, 255, 255, 0.1)';
                    performanceChart.options.scales.x.grid.color = 'rgba(255, 255, 255, 0.1)';
                    performanceChart.options.plugins.legend.labels.color = '#94a3b8';
                } else {
                    performanceChart.options.scales.y.ticks.color = '#6b7280';
                    performanceChart.options.scales.x.ticks.color = '#6b7280';
                    performanceChart.options.scales.y.grid.color = 'rgba(0, 0, 0, 0.1)';
                    performanceChart.options.scales.x.grid.color = 'rgba(0, 0, 0, 0.1)';
                    performanceChart.options.plugins.legend.labels.color = '#6b7280';
                }
                performanceChart.update();
            }
        }

        function updateChart(range) {
            if (!performanceChart) return;
            
            let newData;
            switch(range) {
                case 'week':
                    newData = {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Open Rate',
                            data: [22, 25, 28, 24, 27, 23, 26],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'Click Rate',
                            data: [3, 4, 5, 3, 4, 2, 3],
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    };
                    break;
                case 'month':
                    newData = {
                        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                        datasets: [{
                            label: 'Open Rate',
                            data: [24, 26, 25, 27],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'Click Rate',
                            data: [3.5, 4.2, 3.8, 4.5],
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    };
                    break;
                case 'year':
                    newData = {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Open Rate',
                            data: [23, 24, 25, 26, 27, 26, 28, 27, 28, 29, 28, 30],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'Click Rate',
                            data: [3.2, 3.5, 3.8, 4.0, 4.2, 4.1, 4.3, 4.4, 4.5, 4.7, 4.6, 4.8],
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    };
                    break;
            }
            
            performanceChart.data = newData;
            performanceChart.update();
        }

        // Modal Functions - Fixed
        function showCreateCampaignModal() {
            // This function is kept for other modals but New Campaign button now redirects
            console.log('Create campaign modal should not open now - redirecting to create page');
            window.location.href = "http://127.0.0.1:8000/campaigns/create";
        }

        function showImportModal() {
            const modal = document.getElementById('importCampaignModal');
            if (modal) {
                modal.classList.remove('modal-hidden');
            }
        }

        function showScheduleModal() {
            const modal = document.getElementById('scheduleCampaignModal');
            if (modal) {
                modal.classList.remove('modal-hidden');
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('modal-hidden');
            }
        }

        // Campaign Actions
        function editCampaign(campaignId) {
            window.location.href = "/campaigns/" + campaignId + "/edit";
        }

        function viewAnalytics(campaignId) {
            window.location.href = "/campaigns/" + campaignId + "/analytics";
        }

        function viewCampaign(campaignId) {
            window.location.href = "/campaigns/" + campaignId;
        }

        function previewCampaign(campaignId) {
            Swal.fire({
                title: 'Preview Campaign',
                html: '<p class="text-gray-600 dark:text-gray-300">Opening campaign preview...</p>',
                timer: 1000,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                willClose: () => {
                    window.open("/campaigns/" + campaignId + "/preview", "_blank");
                }
            });
        }

        function sendNow(campaignId) {
            Swal.fire({
                title: 'Send Campaign Now?',
                text: "This will immediately send the campaign to all recipients.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, send now!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sending...',
                        text: 'Please wait while we send your campaign',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                            setTimeout(() => {
                                Swal.fire(
                                    'Sent!',
                                    'Your campaign has been sent successfully.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            }, 2000);
                        }
                    });
                }
            });
        }

        function scheduleCampaign(campaignId) {
            showScheduleModal();
            document.getElementById('scheduleCampaignSelect').value = campaignId;
        }

        function confirmSchedule() {
            const campaignId = document.getElementById('scheduleCampaignSelect').value;
            const scheduleTime = document.getElementById('scheduleDateTime').value;
            
            if (!campaignId || !scheduleTime) {
                Swal.fire('Error', 'Please select a campaign and schedule time', 'error');
                return;
            }
            
            Swal.fire({
                title: 'Schedule Campaign?',
                html: `Campaign will be scheduled for:<br><strong>${new Date(scheduleTime).toLocaleString()}</strong>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Schedule'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Scheduled!', 'Campaign has been scheduled successfully.', 'success');
                    closeModal('scheduleCampaignModal');
                }
            });
        }

        function duplicateCampaign(campaignId) {
            Swal.fire({
                title: 'Duplicate Campaign?',
                text: "This will create a copy of the campaign as a draft.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Duplicate'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Duplicated!', 'Campaign has been duplicated as draft.', 'success');
                }
            });
        }

        function deleteCampaign(campaignId) {
            Swal.fire({
                title: 'Delete Campaign?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/campaigns/${campaignId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire('Deleted!', 'Campaign has been deleted.', 'success');
                        setTimeout(() => location.reload(), 1000);
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'Failed to delete campaign.', 'error');
                    });
                }
            });
        }

        function processImport() {
            const fileInput = document.getElementById('csvFile');
            if (!fileInput.files.length) {
                Swal.fire('Error', 'Please select a CSV file', 'error');
                return;
            }
            
            Swal.fire({
                title: 'Importing...',
                text: 'Please wait while we import your campaigns',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    setTimeout(() => {
                        Swal.fire('Imported!', 'Campaigns have been imported successfully.', 'success');
                        closeModal('importCampaignModal');
                    }, 2000);
                }
            });
        }

        function exportCampaigns() {
            Swal.fire({
                title: 'Export Campaigns',
                text: 'Preparing your campaign data for export...',
                timer: 1500,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                willClose: () => {
                    const link = document.createElement('a');
                    link.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent('name,subject,status,recipients,opens,clicks\n');
                    link.download = 'campaigns_export.csv';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            });
        }

        function filterByStatus(status) {
            const url = new URL(window.location.href);
            if (status === 'all') {
                url.searchParams.delete('status');
            } else {
                url.searchParams.set('status', status);
            }
            window.location.href = url.toString();
        }

        function filterByType(type) {
            const url = new URL(window.location.href);
            if (type === 'all') {
                url.searchParams.delete('type');
            } else {
                url.searchParams.set('type', type);
            }
            window.location.href = url.toString();
        }

        function showNotifications() {
            Swal.fire({
                title: 'Notifications',
                html: `
                    <div class="text-left max-h-60 overflow-y-auto">
                        <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                            <p class="font-semibold text-gray-800 dark:text-white">Campaign "Weekly Newsletter" sent successfully</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Just now</p>
                        </div>
                        <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                            <p class="font-semibold text-gray-800 dark:text-white">High bounce rate detected in "Welcome Series"</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">5 minutes ago</p>
                        </div>
                        <div class="p-3">
                            <p class="font-semibold text-gray-800 dark:text-white">Campaign "Black Friday Sale" scheduled for tomorrow</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">1 hour ago</p>
                        </div>
                    </div>
                `,
                showCloseButton: true,
                showConfirmButton: false,
                width: 400
            });
        }

        // Search functionality
        const searchInput = document.getElementById('searchCampaigns');
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    const searchTerm = this.value.trim();
                    if (searchTerm) {
                        window.location.href = "{{ url('/campaigns') }}?search=" + encodeURIComponent(searchTerm);
                    }
                }
            });
        }

        // Close modals when clicking outside
        document.querySelectorAll('.modal-backdrop').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('modal-hidden');
                }
            });
        });

        // Close modal on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-backdrop').forEach(modal => {
                    modal.classList.add('modal-hidden');
                });
            }
        });

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Campaigns page loaded successfully');
            
            // Check for saved theme
            const savedTheme = localStorage.getItem('infimal_theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            }
            
            // Initialize theme toggle
            initThemeToggle();
            
            // Initialize chart
            initPerformanceChart();
            
            // Highlight active sidebar link
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
            
            // Set filter values from URL
            const urlParams = new URLSearchParams(window.location.search);
            const statusFilter = urlParams.get('status');
            const typeFilter = urlParams.get('type');
            const searchTerm = urlParams.get('search');
            
            if (statusFilter && document.getElementById('statusFilter')) {
                document.getElementById('statusFilter').value = statusFilter;
            }
            if (typeFilter && document.getElementById('typeFilter')) {
                document.getElementById('typeFilter').value = typeFilter;
            }
            if (searchTerm && document.getElementById('searchCampaigns')) {
                document.getElementById('searchCampaigns').value = searchTerm;
            }
            
            // Set default schedule time to tomorrow
            const scheduleDateTime = document.getElementById('scheduleDateTime');
            if (scheduleDateTime) {
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                tomorrow.setHours(10, 0, 0, 0);
                scheduleDateTime.value = tomorrow.toISOString().slice(0, 16);
            }
            
            // Add event listener to New Campaign buttons (in case of any leftover onclick)
            const newCampaignButtons = document.querySelectorAll('button[onclick*="showCreateCampaignModal"]');
            newCampaignButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = "http://127.0.0.1:8000/campaigns/create";
                });
            });
            
            console.log('All initialization complete');
        });
    </script>
</body>
</html>