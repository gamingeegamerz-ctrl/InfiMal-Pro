<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Messages - InfiMal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
        /* Message status badges */
        .message-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-unread { 
            background: rgba(59, 130, 246, 0.2); 
            color: #3b82f6; 
        }
        .dark .status-unread { 
            background: rgba(59, 130, 246, 0.3); 
            color: #60a5fa; 
        }
        .status-read { 
            background: rgba(34, 197, 94, 0.2); 
            color: #22c55e; 
        }
        .dark .status-read { 
            background: rgba(34, 197, 94, 0.3); 
            color: #4ade80; 
        }
        .status-archived { 
            background: rgba(107, 114, 128, 0.2); 
            color: #6b7280; 
        }
        .dark .status-archived { 
            background: rgba(107, 114, 128, 0.3); 
            color: #9ca3af; 
        }
        /* Message type indicators */
        .message-type {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .type-system { 
            background: rgba(245, 158, 11, 0.2); 
            color: #f59e0b; 
        }
        .type-campaign { 
            background: rgba(59, 130, 246, 0.2); 
            color: #3b82f6; 
        }
        .type-billing { 
            background: rgba(239, 68, 68, 0.2); 
            color: #ef4444; 
        }
        .type-support { 
            background: rgba(34, 197, 94, 0.2); 
            color: #22c55e; 
        }
        .type-notification { 
            background: rgba(147, 51, 234, 0.2); 
            color: #9333ea; 
        }
        /* Priority indicators */
        .priority-high { color: #ef4444; }
        .priority-medium { color: #f59e0b; }
        .priority-low { color: #22c55e; }
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
        /* Toast notifications */
        .toast {
            animation: slideInRight 0.3s ease, fadeOut 0.3s ease 2.7s;
        }
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
        /* Loading spinner */
        .loading-spinner {
            border: 3px solid rgba(59, 130, 246, 0.1);
            border-radius: 50%;
            border-top: 3px solid #3b82f6;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/campaigns') }}">
                        <span class="material-symbols-outlined text-xl">campaign</span>
                        <span>Campaigns</span>
                    </a>
                    <a class="nav-link active flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 dark:text-white font-medium text-sm" href="{{ url('/messages') }}">
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
                                <input type="text" id="searchMessages" placeholder="Search messages..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100" />
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="composeNewMessage()" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg font-semibold text-sm hover-glow transition-all duration-300">
                                <span class="material-symbols-outlined align-middle mr-2 text-sm">edit</span>
                                Compose
                            </button>
                            <button onclick="markAllAsRead()" class="border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 px-6 py-2 rounded-lg font-semibold text-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition-all duration-300">
                                <span class="material-symbols-outlined align-middle mr-2 text-sm">done_all</span>
                                Mark All Read
                            </button>
                            <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors relative" id="notificationBtn">
                                <span class="material-symbols-outlined text-gray-600 dark:text-slate-400">notifications</span>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center" id="notificationCount">
                                    {{ $stats['unreadCount'] ?? 0 }}
                                </span>
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
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Messages & Notifications</h1>
                            <p class="text-gray-600 dark:text-slate-300 mb-4">Manage your system messages, alerts, and notifications</p>
                            <div class="flex items-center gap-6">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-500 text-xl">verified</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        Unread Messages: {{ $stats['unreadCount'] ?? 0 }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500 text-xl">campaign</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        Campaign Alerts: {{ $stats['campaignAlertsCount'] ?? 0 }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-purple-500 text-xl">warning</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        System Warnings: {{ $stats['systemMessagesCount'] ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden lg:block">
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 rounded-2xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-6xl">chat</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Stat Card 1 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Total Messages</h3>
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">mail</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $stats['totalMessages'] ?? 0 }}</p>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">{{ $stats['unreadCount'] ?? 0 }} unread</p>
                    </div>
                    
                    <!-- Stat Card 2 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">System Messages</h3>
                            <div class="p-2 bg-green-100 dark:bg-green-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">info</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $stats['systemMessagesCount'] ?? 0 }}</p>
                        <p class="text-blue-600 dark:text-blue-400 text-sm font-medium">{{ $stats['todayMessagesCount'] ?? 0 }} today</p>
                    </div>
                    
                    <!-- Stat Card 3 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Campaign Alerts</h3>
                            <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">campaign</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $stats['campaignAlertsCount'] ?? 0 }}</p>
                        <p class="text-orange-600 dark:text-orange-400 text-sm font-medium">{{ $stats['highPriorityCount'] ?? 0 }} important</p>
                    </div>
                    
                    <!-- Stat Card 4 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">This Month</h3>
                            <div class="p-2 bg-orange-100 dark:bg-orange-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-orange-600 dark:text-orange-400">calendar_month</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $stats['thisMonthCount'] ?? 0 }}</p>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">+{{ $stats['growthRate'] ?? 0 }}% from last month</p>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Messages Table -->
                    <div class="lg:col-span-3">
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-gray-900 dark:text-white font-bold text-lg">All Messages</h3>
                                <div class="flex items-center gap-3">
                                    <select id="typeFilter" onchange="filterByType(this.value)" class="bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-700 dark:text-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="all" {{ request('type', 'all') == 'all' ? 'selected' : '' }}>All Types</option>
                                        <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>System</option>
                                        <option value="campaign" {{ request('type') == 'campaign' ? 'selected' : '' }}>Campaign</option>
                                        <option value="billing" {{ request('type') == 'billing' ? 'selected' : '' }}>Billing</option>
                                        <option value="support" {{ request('type') == 'support' ? 'selected' : '' }}>Support</option>
                                    </select>
                                    <select id="statusFilter" onchange="filterByStatus(this.value)" class="bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-700 dark:text-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All Status</option>
                                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                    <button onclick="exportMessages()" class="flex items-center gap-2 px-3 py-2 border border-gray-300 dark:border-slate-700 rounded-lg text-gray-700 dark:text-slate-300 text-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                        <span class="material-symbols-outlined text-sm">download</span>
                                        Export
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Messages Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead>
                                        <tr class="text-gray-600 dark:text-slate-400 text-sm border-b border-gray-200 dark:border-slate-700">
                                            <th class="pb-3 text-left font-semibold pl-4">Message</th>
                                            <th class="pb-3 text-left font-semibold">Type</th>
                                            <th class="pb-3 text-left font-semibold">Priority</th>
                                            <th class="pb-3 text-left font-semibold">Status</th>
                                            <th class="pb-3 text-left font-semibold">Date</th>
                                            <th class="pb-3 text-left font-semibold pr-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="messagesTableBody">
                                        @forelse($messages as $message)
                                        <tr class="border-b border-gray-100 dark:border-slate-800 last:border-0 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors {{ $message->status === 'unread' ? 'bg-blue-50 dark:bg-blue-900/10' : '' }}" id="message-{{ $message->id }}">
                                            <td class="py-4 pl-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/50">
                                                        @php
                                                            $icons = [
                                                                'system' => 'info',
                                                                'campaign' => 'campaign',
                                                                'billing' => 'payments',
                                                                'support' => 'support_agent',
                                                                'notification' => 'notifications'
                                                            ];
                                                        @endphp
                                                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm">
                                                            {{ $icons[$message->type] ?? 'mail' }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <p class="text-gray-900 dark:text-white font-medium">{{ $message->subject }}</p>
                                                        <p class="text-gray-600 dark:text-slate-400 text-sm">{{ Str::limit($message->content, 70) }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                <span class="message-type type-{{ $message->type }}">
                                                    {{ ucfirst($message->type) }}
                                                </span>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex items-center gap-2">
                                                    <span class="material-symbols-outlined text-sm priority-{{ $message->priority }}">
                                                        @if($message->priority === 'high')
                                                        warning
                                                        @elseif($message->priority === 'medium')
                                                        info
                                                        @else
                                                        check_circle
                                                        @endif
                                                    </span>
                                                    <span class="text-gray-900 dark:text-white text-sm capitalize">{{ $message->priority }}</span>
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                <span class="message-status status-{{ $message->status }}">
                                                    {{ ucfirst($message->status) }}
                                                </span>
                                            </td>
                                            <td class="py-4">
                                                <span class="text-gray-600 dark:text-slate-400 text-sm">
                                                    {{ $message->created_at->diffForHumans() }}
                                                </span>
                                            </td>
                                            <td class="py-4 pr-4">
                                                <div class="flex items-center gap-2">
                                                    <button onclick="viewMessage({{ $message->id }})" class="p-1.5 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors" title="View">
                                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                                    </button>
                                                    @if($message->status === 'unread')
                                                    <button onclick="markAsRead({{ $message->id }})" class="p-1.5 rounded-lg bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800 transition-colors" title="Mark as Read">
                                                        <span class="material-symbols-outlined text-sm">done</span>
                                                    </button>
                                                    @endif
                                                    <button onclick="deleteMessage({{ $message->id }})" class="p-1.5 rounded-lg bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800 transition-colors" title="Delete">
                                                        <span class="material-symbols-outlined text-sm">delete</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="py-12 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <span class="material-symbols-outlined text-gray-400 dark:text-slate-600 text-4xl">mail</span>
                                                    <p class="text-gray-600 dark:text-slate-400 mt-2">No messages yet</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200 dark:border-slate-700">
                                <div class="text-gray-600 dark:text-slate-400 text-sm">
                                    Showing {{ $messages->firstItem() }} to {{ $messages->lastItem() }} of {{ $messages->total() }} messages
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($messages->onFirstPage())
                                    <span class="px-3 py-1 text-sm rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 cursor-not-allowed opacity-50">
                                        Previous
                                    </span>
                                    @else
                                    <a href="{{ $messages->previousPageUrl() }}&type={{ request('type', 'all') }}&status={{ request('status', 'all') }}" class="px-3 py-1 text-sm rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors">
                                        Previous
                                    </a>
                                    @endif
                                    
                                    @foreach(range(1, $messages->lastPage()) as $page)
                                        @if($page == $messages->currentPage())
                                        <span class="px-3 py-1 text-sm rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400">
                                            {{ $page }}
                                        </span>
                                        @else
                                        <a href="{{ $messages->url($page) }}&type={{ request('type', 'all') }}&status={{ request('status', 'all') }}" class="px-3 py-1 text-sm rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors">
                                            {{ $page }}
                                        </a>
                                        @endif
                                    @endforeach
                                    
                                    @if($messages->hasMorePages())
                                    <a href="{{ $messages->nextPageUrl() }}&type={{ request('type', 'all') }}&status={{ request('status', 'all') }}" class="px-3 py-1 text-sm rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors">
                                        Next
                                    </a>
                                    @else
                                    <span class="px-3 py-1 text-sm rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 cursor-not-allowed opacity-50">
                                        Next
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side Panel -->
                    <div class="lg:col-span-1 flex flex-col gap-6">
                        <!-- Quick Actions -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <button onclick="composeNewMessage()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-800/30 transition-colors">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">edit</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">Compose</span>
                                </button>
                                <button onclick="markAllAsRead()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-800/30 transition-colors">
                                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">done_all</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">Mark All Read</span>
                                </button>
                                <button onclick="archiveAllRead()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">archive</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">Archive Read</span>
                                </button>
                                <button onclick="deleteAllRead()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-800/30 transition-colors">
                                    <span class="material-symbols-outlined text-red-600 dark:text-red-400">delete</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">Delete Read</span>
                                </button>
                            </div>
                        </div>

                        <!-- Message Types -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">Message Types</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-orange-500">campaign</span>
                                        <span class="text-gray-900 dark:text-white text-sm">Campaign</span>
                                    </div>
                                    <span class="text-gray-600 dark:text-slate-400 text-sm font-medium">{{ $stats['campaignAlertsCount'] ?? 0 }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-blue-500">info</span>
                                        <span class="text-gray-900 dark:text-white text-sm">System</span>
                                    </div>
                                    <span class="text-gray-600 dark:text-slate-400 text-sm font-medium">{{ $stats['systemMessagesCount'] ?? 0 }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-green-500">payments</span>
                                        <span class="text-gray-900 dark:text-white text-sm">Billing</span>
                                    </div>
                                    <span class="text-gray-600 dark:text-slate-400 text-sm font-medium">{{ $stats['billingMessagesCount'] ?? 0 }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-purple-500">support_agent</span>
                                        <span class="text-gray-900 dark:text-white text-sm">Support</span>
                                    </div>
                                    <span class="text-gray-600 dark:text-slate-400 text-sm font-medium">{{ $stats['supportMessagesCount'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Messages -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">Recent Messages</h3>
                            <div class="space-y-3">
                                @php
                                    $recentMessages = $messages->take(3);
                                @endphp
                                
                                @if(count($recentMessages) > 0)
                                    @foreach($recentMessages as $message)
                                    <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50 dark:bg-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors cursor-pointer" onclick="viewMessage({{ $message->id }})">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-blue-500 text-sm">
                                                @php
                                                    $icons = [
                                                        'system' => 'info',
                                                        'campaign' => 'campaign',
                                                        'billing' => 'payments',
                                                        'support' => 'support_agent',
                                                        'notification' => 'notifications'
                                                    ];
                                                @endphp
                                                {{ $icons[$message->type] ?? 'mail' }}
                                            </span>
                                            <div>
                                                <span class="text-gray-900 dark:text-white text-sm truncate block max-w-[120px]">{{ Str::limit($message->subject, 20) }}</span>
                                                <span class="message-status status-{{ $message->status }} text-xs mt-1">
                                                    {{ ucfirst($message->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <span class="text-gray-600 dark:text-slate-400 text-xs">
                                            {{ $message->created_at->format('H:i') }}
                                        </span>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-4">
                                        <span class="material-symbols-outlined text-gray-400 dark:text-slate-600 text-3xl">mail</span>
                                        <p class="text-gray-600 dark:text-slate-400 mt-2">No recent messages</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- View Message Modal -->
    <div id="viewMessageModal" class="modal-backdrop modal-hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-2xl shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-gray-900 dark:text-white text-xl font-bold">Message Details</h3>
                <button onclick="closeModal('viewMessageModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
            </div>
            <div class="space-y-6">
                <div class="border-b border-gray-200 dark:border-slate-700 pb-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-gray-900 dark:text-white text-lg font-semibold" id="messageSubject">Loading...</h4>
                        <div class="flex items-center gap-2">
                            <span class="message-type type-system" id="messageType">System</span>
                            <span class="message-status status-unread" id="messageStatus">Unread</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-gray-600 dark:text-slate-400 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">person</span>
                            <span>System</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">schedule</span>
                            <span id="messageTime">Loading...</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm priority-high" id="messagePriorityIcon">warning</span>
                            <span class="capitalize" id="messagePriority">High</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-slate-900 rounded-xl p-6">
                    <p class="text-gray-700 dark:text-slate-300" id="messageContent">
                        Loading message content...
                    </p>
                </div>
                
                <div class="pt-4 border-t border-gray-200 dark:border-slate-700">
                    <div class="flex justify-end gap-3">
                        <button onclick="replyToMessage()" class="px-6 py-2 border border-gray-300 dark:border-slate-700 rounded-lg text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                            <span class="material-symbols-outlined align-middle mr-2 text-sm">reply</span>
                            Reply
                        </button>
                        <button onclick="markCurrentAsRead()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                            <span class="material-symbols-outlined align-middle mr-2 text-sm">done</span>
                            Mark as Read
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Compose Message Modal -->
    <div id="composeMessageModal" class="modal-backdrop modal-hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-2xl shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-gray-900 dark:text-white text-xl font-bold">Compose New Message</h3>
                <button onclick="closeModal('composeMessageModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
            </div>
            <form id="composeForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">To</label>
                        <select id="messageTo" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="support">Support Team</option>
                            <option value="system">System Admin</option>
                            <option value="billing">Billing Department</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Subject</label>
                        <input type="text" id="messageSubjectInput" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter message subject" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Type</label>
                        <select id="messageTypeSelect" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="system">System</option>
                            <option value="campaign">Campaign</option>
                            <option value="billing">Billing</option>
                            <option value="support">Support</option>
                            <option value="notification">Notification</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Priority</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="priority" value="low" checked class="text-blue-600">
                                <span class="text-gray-700 dark:text-slate-300">Low</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="priority" value="medium" class="text-blue-600">
                                <span class="text-gray-700 dark:text-slate-300">Medium</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="priority" value="high" class="text-blue-600">
                                <span class="text-gray-700 dark:text-slate-300">High</span>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Message</label>
                        <textarea rows="6" id="messageContentInput" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Type your message here..." required></textarea>
                    </div>
                    
                    <div class="pt-4">
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="closeModal('composeMessageModal')" class="px-6 py-2 border border-gray-300 dark:border-slate-700 rounded-lg text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                                <span class="material-symbols-outlined align-middle mr-2 text-sm">send</span>
                                Send Message
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="loading-spinner"></div>
    </div>

    <script>
        // Theme Toggle
        function initThemeToggle() {
            const themeToggle = document.getElementById('themeToggle');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const savedTheme = localStorage.getItem('infimal_theme');
            
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

        // Show/Hide Loading
        function showLoading(show = true) {
            const spinner = document.getElementById('loadingSpinner');
            if (show) {
                spinner.classList.remove('hidden');
            } else {
                spinner.classList.add('hidden');
            }
        }

        // Toast Notifications
        function showToast(message, type = 'info') {
            // Remove existing toasts
            document.querySelectorAll('.toast').forEach(toast => toast.remove());
            
            // Create toast element
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : 
                           type === 'error' ? 'bg-red-500' : 
                           type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
            
            toast.className = `toast fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg ${bgColor} text-white flex items-center gap-2`;
            toast.innerHTML = `
                <span class="material-symbols-outlined">
                    ${type === 'success' ? 'check_circle' : 
                      type === 'error' ? 'error' : 
                      type === 'warning' ? 'warning' : 'info'}
                </span>
                <span>${message}</span>
            `;
            
            document.body.appendChild(toast);
            
            // Remove after 3 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 3000);
        }

        // Modal Functions
        async function viewMessage(messageId) {
            showLoading(true);
            try {
                const response = await fetch(`/messages/${messageId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (!response.ok) throw new Error('Failed to fetch message');
                
                const message = await response.json();
                
                // Update modal content with real data
                document.getElementById('messageSubject').textContent = message.subject;
                document.getElementById('messageContent').textContent = message.content;
                
                const typeText = message.type.charAt(0).toUpperCase() + message.type.slice(1);
                document.getElementById('messageType').textContent = typeText;
                document.getElementById('messageType').className = `message-type type-${message.type}`;
                
                const statusText = message.status.charAt(0).toUpperCase() + message.status.slice(1);
                document.getElementById('messageStatus').textContent = statusText;
                document.getElementById('messageStatus').className = `message-status status-${message.status}`;
                
                const timeAgo = new Date(message.created_at).toLocaleDateString() + ' ' + 
                               new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                document.getElementById('messageTime').textContent = timeAgo;
                
                document.getElementById('messagePriority').textContent = message.priority;
                const priorityIcon = message.priority === 'high' ? 'warning' : 
                                   message.priority === 'medium' ? 'info' : 'check_circle';
                document.getElementById('messagePriorityIcon').textContent = priorityIcon;
                document.getElementById('messagePriorityIcon').className = `material-symbols-outlined text-sm priority-${message.priority}`;
                
                // Store current message ID
                document.getElementById('viewMessageModal').dataset.messageId = messageId;
                
                // Update UI for this message if it was unread
                const row = document.getElementById(`message-${messageId}`);
                if (row && message.status === 'unread') {
                    row.classList.remove('bg-blue-50', 'dark:bg-blue-900/10');
                    const statusBadge = row.querySelector('.message-status');
                    if (statusBadge) {
                        statusBadge.textContent = 'Read';
                        statusBadge.className = 'message-status status-read';
                    }
                    
                    // Remove mark as read button
                    const markReadBtn = row.querySelector(`button[onclick="markAsRead(${messageId})"]`);
                    if (markReadBtn) {
                        markReadBtn.remove();
                    }
                    
                    // Update unread count
                    updateUnreadCount();
                }
                
                // Show modal
                document.getElementById('viewMessageModal').classList.remove('modal-hidden');
                
            } catch (error) {
                console.error('Error fetching message:', error);
                showToast('Error loading message', 'error');
            } finally {
                showLoading(false);
            }
        }

        function composeNewMessage() {
            document.getElementById('composeMessageModal').classList.remove('modal-hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('modal-hidden');
        }

        // Message Actions with Real API
        async function markAsRead(messageId) {
            showLoading(true);
            try {
                const response = await fetch(`/messages/${messageId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: 'read' })
                });
                
                if (!response.ok) throw new Error('Failed to update message');
                
                const result = await response.json();
                
                if (result.success) {
                    // Update UI
                    const row = document.getElementById(`message-${messageId}`);
                    if (row) {
                        row.classList.remove('bg-blue-50', 'dark:bg-blue-900/10');
                        
                        const statusBadge = row.querySelector('.message-status');
                        if (statusBadge) {
                            statusBadge.textContent = 'Read';
                            statusBadge.className = 'message-status status-read';
                        }
                        
                        // Remove mark as read button
                        const markReadBtn = row.querySelector(`button[onclick="markAsRead(${messageId})"]`);
                        if (markReadBtn) {
                            markReadBtn.remove();
                        }
                    }
                    
                    showToast('Message marked as read', 'success');
                    updateUnreadCount();
                }
            } catch (error) {
                console.error('Error marking as read:', error);
                showToast('Error updating message', 'error');
            } finally {
                showLoading(false);
            }
        }

        async function markCurrentAsRead() {
            const messageId = document.getElementById('viewMessageModal').dataset.messageId;
            if (messageId) {
                await markAsRead(messageId);
                closeModal('viewMessageModal');
            }
        }

        async function markAllAsRead() {
            if (!confirm('Mark all messages as read?')) return;
            
            showLoading(true);
            try {
                const response = await fetch('/messages/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) throw new Error('Failed to mark all as read');
                
                const result = await response.json();
                
                if (result.success) {
                    // Update all unread messages in UI
                    document.querySelectorAll('.message-status.status-unread').forEach(badge => {
                        badge.textContent = 'Read';
                        badge.className = 'message-status status-read';
                        const row = badge.closest('tr');
                        if (row) {
                            row.classList.remove('bg-blue-50', 'dark:bg-blue-900/10');
                            
                            // Remove mark as read button
                            const markReadBtn = row.querySelector('button[onclick^="markAsRead"]');
                            if (markReadBtn) {
                                markReadBtn.remove();
                            }
                        }
                    });
                    
                    showToast('All messages marked as read', 'success');
                    updateUnreadCount();
                }
            } catch (error) {
                console.error('Error marking all as read:', error);
                showToast('Error updating messages', 'error');
            } finally {
                showLoading(false);
            }
        }

        async function deleteMessage(messageId) {
            if (!confirm('Are you sure you want to delete this message?')) return;
            
            showLoading(true);
            try {
                const response = await fetch(`/messages/${messageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) throw new Error('Failed to delete message');
                
                const result = await response.json();
                
                if (result.success) {
                    const row = document.getElementById(`message-${messageId}`);
                    if (row) {
                        row.style.opacity = '0.5';
                        setTimeout(() => {
                            row.remove();
                            
                            // Check if table is empty
                            const tbody = document.querySelector('tbody');
                            if (tbody && tbody.children.length === 0) {
                                const emptyRow = document.createElement('tr');
                                emptyRow.innerHTML = `
                                    <td colspan="6" class="py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <span class="material-symbols-outlined text-gray-400 dark:text-slate-600 text-4xl">mail</span>
                                            <p class="text-gray-600 dark:text-slate-400 mt-2">No messages yet</p>
                                        </div>
                                    </td>
                                `;
                                tbody.appendChild(emptyRow);
                            }
                        }, 300);
                    }
                    
                    showToast('Message deleted', 'success');
                    updateUnreadCount();
                }
            } catch (error) {
                console.error('Error deleting message:', error);
                showToast('Error deleting message', 'error');
            } finally {
                showLoading(false);
            }
        }

        async function archiveAllRead() {
            const readRows = document.querySelectorAll('.message-status.status-read');
            if (readRows.length === 0) {
                showToast('No read messages to archive', 'info');
                return;
            }
            
            if (!confirm(`Archive ${readRows.length} read messages?`)) return;
            
            showLoading(true);
            try {
                // Archive each read message
                const promises = Array.from(readRows).map(async (badge) => {
                    const row = badge.closest('tr');
                    const messageId = row.id.replace('message-', '');
                    
                    const response = await fetch(`/messages/${messageId}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: 'archived' })
                    });
                    
                    if (response.ok) {
                        badge.textContent = 'Archived';
                        badge.className = 'message-status status-archived';
                        row.style.opacity = '0.7';
                    }
                });
                
                await Promise.all(promises);
                showToast(`${readRows.length} messages archived`, 'success');
            } catch (error) {
                console.error('Error archiving messages:', error);
                showToast('Error archiving messages', 'error');
            } finally {
                showLoading(false);
            }
        }

        async function deleteAllRead() {
            const readRows = document.querySelectorAll('.message-status.status-read');
            if (readRows.length === 0) {
                showToast('No read messages to delete', 'info');
                return;
            }
            
            if (!confirm(`Delete ${readRows.length} read messages? This action cannot be undone.`)) return;
            
            showLoading(true);
            try {
                // Get all read message IDs
                const messageIds = Array.from(readRows).map(badge => {
                    const row = badge.closest('tr');
                    return row.id.replace('message-', '');
                });
                
                // Send bulk delete request
                const response = await fetch('/messages/bulk-delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ids: messageIds })
                });
                
                if (!response.ok) throw new Error('Failed to delete messages');
                
                const result = await response.json();
                
                if (result.success) {
                    // Remove all read rows
                    readRows.forEach(badge => {
                        const row = badge.closest('tr');
                        if (row) {
                            row.style.opacity = '0.5';
                            setTimeout(() => row.remove(), 300);
                        }
                    });
                    
                    setTimeout(() => {
                        showToast(`${readRows.length} messages deleted`, 'success');
                        
                        // Check if table is empty
                        const tbody = document.querySelector('tbody');
                        if (tbody && tbody.children.length === 0) {
                            const emptyRow = document.createElement('tr');
                            emptyRow.innerHTML = `
                                <td colspan="6" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-outlined text-gray-400 dark:text-slate-600 text-4xl">mail</span>
                                        <p class="text-gray-600 dark:text-slate-400 mt-2">No messages yet</p>
                                    </div>
                                </td>
                            `;
                            tbody.appendChild(emptyRow);
                        }
                    }, 350);
                }
            } catch (error) {
                console.error('Error deleting messages:', error);
                showToast('Error deleting messages', 'error');
            } finally {
                showLoading(false);
            }
        }

        function replyToMessage() {
            const subject = document.getElementById('messageSubject').textContent;
            closeModal('viewMessageModal');
            setTimeout(() => {
                composeNewMessage();
                document.getElementById('messageSubjectInput').value = `Re: ${subject}`;
            }, 300);
        }

        async function exportMessages() {
            showLoading(true);
            try {
                // Get current filters
                const type = document.getElementById('typeFilter').value;
                const status = document.getElementById('statusFilter').value;
                
                // Create export URL with filters
                let exportUrl = '/messages/export?';
                if (type !== 'all') exportUrl += `type=${type}&`;
                if (status !== 'all') exportUrl += `status=${status}&`;
                
                // Trigger download
                window.location.href = exportUrl;
                
                showToast('Export started', 'info');
            } catch (error) {
                console.error('Error exporting messages:', error);
                showToast('Error exporting messages', 'error');
            } finally {
                showLoading(false);
            }
        }

        function filterByType(type) {
            const url = new URL(window.location);
            if (type === 'all') {
                url.searchParams.delete('type');
            } else {
                url.searchParams.set('type', type);
            }
            window.location.href = url.toString();
        }

        function filterByStatus(status) {
            const url = new URL(window.location);
            if (status === 'all') {
                url.searchParams.delete('status');
            } else {
                url.searchParams.set('status', status);
            }
            window.location.href = url.toString();
        }

        function updateUnreadCount() {
            // Count unread messages from table
            const unreadCount = document.querySelectorAll('.message-status.status-unread').length;
            const notificationCount = document.getElementById('notificationCount');
            const unreadSpan = document.querySelector('.text-gray-600.dark\\:text-slate-300.text-sm.font-medium');
            
            // Update notification bell
            if (notificationCount) {
                if (unreadCount > 0) {
                    notificationCount.textContent = unreadCount;
                    notificationCount.classList.remove('hidden');
                } else {
                    notificationCount.classList.add('hidden');
                }
            }
            
            // Update welcome banner count if found
            const unreadSpans = document.querySelectorAll('.text-gray-600.dark\\:text-slate-300.text-sm.font-medium');
            unreadSpans.forEach(span => {
                if (span.textContent.includes('Unread Messages')) {
                    span.innerHTML = `Unread Messages: ${unreadCount}`;
                }
            });
        }

        // Handle compose form submission
        document.getElementById('composeForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            showLoading(true);
            
            const data = {
                subject: document.getElementById('messageSubjectInput').value,
                content: document.getElementById('messageContentInput').value,
                type: document.getElementById('messageTypeSelect').value,
                priority: document.querySelector('input[name="priority"]:checked').value
            };
            
            try {
                const response = await fetch('/messages', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                if (!response.ok) throw new Error('Failed to send message');
                
                const result = await response.json();
                
                if (result.success) {
                    showToast('Message sent successfully', 'success');
                    closeModal('composeMessageModal');
                    this.reset();
                    
                    // Reload page to show new message
                    setTimeout(() => window.location.reload(), 1000);
                }
            } catch (error) {
                console.error('Error sending message:', error);
                showToast('Error sending message', 'error');
            } finally {
                showLoading(false);
            }
        });

        // Search functionality
        document.getElementById('searchMessages').addEventListener('input', debounce(function(e) {
            const searchTerm = this.value.toLowerCase().trim();
            const url = new URL(window.location);
            
            if (searchTerm) {
                url.searchParams.set('search', searchTerm);
            } else {
                url.searchParams.delete('search');
            }
            
            // Update URL without reload if using AJAX, or reload page
            window.history.pushState({}, '', url);
            
            // For now, just filter client-side
            const rows = document.querySelectorAll('tbody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const subject = row.querySelector('.font-medium')?.textContent.toLowerCase() || '';
                const content = row.querySelector('.text-sm.text-gray-600')?.textContent.toLowerCase() || '';
                
                if (subject.includes(searchTerm) || content.includes(searchTerm) || searchTerm === '') {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Show message if no results
            const emptyRow = document.querySelector('tbody tr:last-child');
            if (emptyRow && emptyRow.cells.length === 1) {
                if (visibleCount === 0 && searchTerm) {
                    emptyRow.style.display = '';
                    emptyRow.querySelector('p').textContent = 'No messages found';
                } else if (visibleCount === 0) {
                    emptyRow.style.display = '';
                    emptyRow.querySelector('p').textContent = 'No messages yet';
                } else {
                    emptyRow.style.display = 'none';
                }
            }
        }, 300));

        // Debounce function for search
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
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

        // Notification bell click
        document.getElementById('notificationBtn').addEventListener('click', function() {
            // Mark all as read when clicking notification bell
            const unreadCount = document.querySelectorAll('.message-status.status-unread').length;
            if (unreadCount > 0) {
                markAllAsRead();
            }
        });

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            // Check for saved theme
            const savedTheme = localStorage.getItem('infimal_theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            }
            
            // Initialize theme toggle
            initThemeToggle();
            
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
            const typeFilter = urlParams.get('type');
            const statusFilter = urlParams.get('status');
            const searchFilter = urlParams.get('search');
            
            if (typeFilter && document.getElementById('typeFilter')) {
                document.getElementById('typeFilter').value = typeFilter;
            }
            
            if (statusFilter && document.getElementById('statusFilter')) {
                document.getElementById('statusFilter').value = statusFilter;
            }
            
            if (searchFilter && document.getElementById('searchMessages')) {
                document.getElementById('searchMessages').value = searchFilter;
            }
            
            console.log('Messages page loaded successfully');
        });
    </script>
</body>
</html>