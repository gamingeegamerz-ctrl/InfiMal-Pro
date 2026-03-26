<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Subscribers - InfiMal</title>
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
        /* Progress bar */
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }
        .dark .progress-bar {
            background: #4b5563;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3B82F6, #8B5CF6);
            transition: width 0.3s ease;
            border-radius: 4px;
        }
        /* Custom dark mode classes */
        .text-gray-500.dark-text {
            color: #94a3b8;
        }
        .text-gray-600.dark-text {
            color: #cbd5e1;
        }
        .text-gray-700.dark-text {
            color: #e2e8f0;
        }
        .text-gray-900.dark-text {
            color: #f1f5f9;
        }
        .bg-white.dark-bg {
            background-color: #1e293b;
        }
        .bg-gray-50.dark-bg {
            background-color: #0f172a;
        }
        .bg-blue-50.dark-bg {
            background-color: rgba(30, 64, 175, 0.2);
        }
        .bg-green-50.dark-bg {
            background-color: rgba(6, 95, 70, 0.2);
        }
        .bg-red-50.dark-bg {
            background-color: rgba(153, 27, 27, 0.2);
        }
        .bg-orange-50.dark-bg {
            background-color: rgba(194, 65, 12, 0.2);
        }
        .bg-gray-100.dark-bg {
            background-color: rgba(30, 41, 59, 0.5);
        }
        .border-gray-200.dark-border {
            border-color: #334155;
        }
        .border-gray-300.dark-border {
            border-color: #475569;
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
                    <a class="nav-link active flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 dark:text-white font-medium text-sm" href="{{ url('/subscribers') }}">
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
                                <input id="searchInput" type="text" placeholder="Search subscribers..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100" value="{{ request('search', '') }}" />
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="openImportModal()" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg font-semibold text-sm hover-glow transition-all duration-300">
                                Import CSV
                            </button>
                            <button onclick="openAddModal()" class="border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 px-6 py-2 rounded-lg font-semibold text-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition-all duration-300">
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
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Subscribers Management</h1>
                            <p class="text-gray-600 dark:text-slate-300 mb-4">Manage your email subscribers, create lists, and track engagement.</p>
                            <div class="flex items-center gap-6">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-500 text-xl">verified</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        Total Subscribers: {{ $totalSubscribers }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500 text-xl">trending_up</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        30 Day Growth: +{{ $growth30Days }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden lg:block">
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 rounded-2xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-6xl">group</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Stat Card 1 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Total Subscribers</h3>
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">group</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $totalSubscribers }}</p>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">+{{ $growth30Days }} this month</p>
                    </div>
                    
                    <!-- Stat Card 2 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Active Subscribers</h3>
                            <div class="p-2 bg-green-100 dark:bg-green-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $activeSubscribers }}</p>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">{{ $totalSubscribers > 0 ? number_format($activeSubscribers/$totalSubscribers*100, 1) : 0 }}% of total</p>
                    </div>
                    
                    <!-- Stat Card 3 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Unsubscribed</h3>
                            <div class="p-2 bg-red-100 dark:bg-red-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-red-600 dark:text-red-400">cancel</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $unsubscribedSubscribers }}</p>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">{{ $totalSubscribers > 0 ? number_format($unsubscribedSubscribers/$totalSubscribers*100, 1) : 0 }}% of total</p>
                    </div>
                    
                    <!-- Stat Card 4 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Bounced</h3>
                            <div class="p-2 bg-orange-100 dark:bg-orange-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-orange-600 dark:text-orange-400">error</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $bouncedSubscribers }}</p>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">{{ $totalSubscribers > 0 ? number_format($bouncedSubscribers/$totalSubscribers*100, 1) : 0 }}% of total</p>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Subscribers Table -->
                    <div class="lg:col-span-3">
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-gray-900 dark:text-white font-bold text-lg">All Subscribers</h3>
                                <div class="flex items-center gap-3">
                                    <select id="statusFilter" onchange="filterByStatus(this.value)" class="bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-700 dark:text-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="unsubscribed" {{ request('status') == 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
                                        <option value="bounced" {{ request('status') == 'bounced' ? 'selected' : '' }}>Bounced</option>
                                    </select>
                                    <select id="listFilter" onchange="filterByList(this.value)" class="bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-700 dark:text-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="all" {{ request('list_id') == 'all' ? 'selected' : '' }}>All Lists</option>
                                        @foreach($lists as $list)
                                            <option value="{{ $list->id }}" {{ request('list_id') == $list->id ? 'selected' : '' }}>
                                                {{ $list->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Subscribers Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead>
                                        <tr class="text-gray-600 dark:text-slate-400 text-sm border-b border-gray-200 dark:border-slate-700">
                                            <th class="pb-3 text-left font-semibold">Email</th>
                                            <th class="pb-3 text-left font-semibold">Name</th>
                                            <th class="pb-3 text-left font-semibold">Status</th>
                                            <th class="pb-3 text-left font-semibold">List</th>
                                            <th class="pb-3 text-left font-semibold">Subscribed</th>
                                            <th class="pb-3 text-left font-semibold">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($subscribers as $subscriber)
                                        <tr class="border-b border-gray-100 dark:border-slate-800 last:border-0 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                            <td class="py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                                                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm">person</span>
                                                    </div>
                                                    <div>
                                                        <p class="text-gray-900 dark:text-white font-medium">{{ $subscriber->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 text-gray-900 dark:text-white">{{ $subscriber->first_name }} {{ $subscriber->last_name }}</td>
                                            <td class="py-4">
                                                <span class="text-xs px-3 py-1 rounded-full font-medium
                                                    @if($subscriber->status === 'active') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-400
                                                    @elseif($subscriber->status === 'unsubscribed') bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-400
                                                    @else bg-orange-100 dark:bg-orange-900/50 text-orange-800 dark:text-orange-400
                                                    @endif">
                                                    {{ ucfirst($subscriber->status) }}
                                                </span>
                                            </td>
                                            <td class="py-4 text-gray-600 dark:text-slate-400 text-sm">{{ $subscriber->mailingList->name ?? 'No List' }}</td>
                                            <td class="py-4 text-gray-600 dark:text-slate-400 text-sm">{{ $subscriber->created_at->format('M d, Y') }}</td>
                                            <td class="py-4">
                                                <div class="flex items-center gap-2">
                                                    <button onclick="editSubscriber({{ $subscriber->id }})" class="p-1.5 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors" title="Edit">
                                                        <span class="material-symbols-outlined text-sm">edit</span>
                                                    </button>
                                                    <button onclick="deleteSubscriber({{ $subscriber->id }})" class="p-1.5 rounded-lg bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800 transition-colors" title="Delete">
                                                        <span class="material-symbols-outlined text-sm">delete</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="py-12 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <span class="material-symbols-outlined text-gray-400 dark:text-slate-600 text-4xl">group</span>
                                                    <p class="text-gray-600 dark:text-slate-400 mt-2">No subscribers yet</p>
                                                    <button onclick="openAddModal()" class="mt-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold">
                                                        Add Your First Subscriber
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            @if($subscribers->hasPages())
                            <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200 dark:border-slate-700">
                                <div class="text-gray-600 dark:text-slate-400 text-sm">
                                    Showing {{ $subscribers->firstItem() }} to {{ $subscribers->lastItem() }} of {{ $subscribers->total() }} subscribers
                                </div>
                                <div class="flex items-center gap-2">
                                    {{ $subscribers->links() }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right Side Panel -->
                    <div class="lg:col-span-1 flex flex-col gap-6">
                        <!-- Quick Actions -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <button onclick="openAddModal()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-800/30 transition-colors">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">add</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">Add New Subscriber</span>
                                </button>
                                <button onclick="openImportModal()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">upload</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">Import CSV</span>
                                </button>
                                <a href="{{ route('subscribers.export') }}" class="w-full flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">download</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">Export Subscribers</span>
                                </a>
                                <a href="{{ url('/lists') }}" class="w-full flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                                    <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400">list_alt</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">Manage Lists</span>
                                </a>
                            </div>
                        </div>

                        <!-- Subscriber Stats -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">Subscriber Stats</h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-gray-600 dark:text-slate-400 text-sm">Avg. Open Rate</p>
                                    <p class="text-gray-900 dark:text-white text-xl font-bold">{{ $avgOpenRate }}%</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 dark:text-slate-400 text-sm">Avg. Click Rate</p>
                                    <p class="text-gray-900 dark:text-white text-xl font-bold">{{ $avgClickRate }}%</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 dark:text-slate-400 text-sm">Avg. Engagement</p>
                                    <p class="text-gray-900 dark:text-white text-xl font-bold">{{ number_format(($avgOpenRate + $avgClickRate) / 2, 1) }}%</p>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">Recent Activity</h3>
                            <div class="space-y-3 max-h-60 overflow-y-auto">
                                @forelse($recentActivities as $activity)
                                <div class="flex items-start gap-3 p-2 rounded-lg bg-gray-50 dark:bg-slate-800/50">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm mt-0.5">person_add</span>
                                    <div class="flex-1">
                                        <p class="text-gray-900 dark:text-white text-sm">{{ $activity['message'] }}</p>
                                        <p class="text-gray-500 dark:text-slate-500 text-xs">{{ $activity['time'] }}</p>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-4">
                                    <span class="material-symbols-outlined text-gray-400 dark:text-slate-600 text-3xl">history</span>
                                    <p class="text-gray-600 dark:text-slate-400 mt-2">No recent activity</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Subscriber Modal -->
    <div id="addModal" class="modal-backdrop modal-hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-md shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-gray-900 dark:text-white text-xl font-bold" id="modalTitle">Add New Subscriber</h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
            </div>
            <form id="addSubscriberForm">
                @csrf
                <input type="hidden" name="edit_id" id="edit_id" value="">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Email Address *</label>
                        <input type="email" name="email" required class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="user@example.com">
                        <span class="text-red-500 text-xs hidden mt-1" id="emailError"></span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">First Name</label>
                            <input type="text" name="first_name" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="John">
                        </div>
                        <div>
                            <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Last Name</label>
                            <input type="text" name="last_name" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Doe">
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Status</label>
                        <select name="status" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="active">Active</option>
                            <option value="unsubscribed">Unsubscribed</option>
                            <option value="bounced">Bounced</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">List *</label>
                        <select name="list_id" required class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Select List --</option>
                            @foreach($lists as $list)
                                <option value="{{ $list->id }}">{{ $list->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-red-500 text-xs hidden mt-1" id="listError"></span>
                    </div>
                </div>
                <div class="flex gap-3 mt-8">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold transition-colors" id="addBtn">Add Subscriber</button>
                    <button type="button" onclick="closeAddModal()" class="flex-1 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-slate-300 py-2.5 rounded-lg font-semibold hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="modal-backdrop modal-hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-lg shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-gray-900 dark:text-white text-xl font-bold">Import Subscribers</h3>
                <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
            </div>
            <form id="importForm" enctype="multipart/form-data">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Select CSV File</label>
                        <input type="file" name="file" accept=".csv" required class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="csvFile">
                        <p class="text-gray-500 dark:text-slate-500 text-xs mt-2">Format: email,first_name,last_name</p>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">List *</label>
                        <select name="list_id" required class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Select List --</option>
                            @foreach($lists as $list)
                                <option value="{{ $list->id }}">{{ $list->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="skip_duplicates" checked class="rounded border-gray-300 dark:border-slate-700 text-blue-600 focus:ring-blue-500 bg-white dark:bg-slate-800" id="skipDuplicates">
                        <label class="text-gray-700 dark:text-slate-300 text-sm">Skip duplicate emails</label>
                    </div>
                    
                    <div id="progressSection" class="hidden">
                        <div class="mb-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-700 dark:text-slate-300 text-sm font-medium">Import Progress</span>
                                <span class="text-gray-900 dark:text-white text-sm font-bold" id="progressPercent">0%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" id="progressFill" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 mt-8">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg font-semibold transition-colors" id="importBtn">Start Import</button>
                    <button type="button" onclick="closeImportModal()" class="flex-1 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-slate-300 py-2.5 rounded-lg font-semibold hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Theme Toggle
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

    // Modal Functions
    function openAddModal() {
        document.getElementById('modalTitle').textContent = 'Add New Subscriber';
        document.getElementById('addBtn').textContent = 'Add Subscriber';
        document.getElementById('addSubscriberForm').reset();
        document.getElementById('edit_id').value = '';
        document.getElementById('addModal').classList.remove('modal-hidden');
    }
    
    function closeAddModal() {
        document.getElementById('addModal').classList.add('modal-hidden');
        document.getElementById('addSubscriberForm').reset();
        document.getElementById('emailError').classList.add('hidden');
        document.getElementById('listError').classList.add('hidden');
    }
    
    function openImportModal() {
        document.getElementById('importModal').classList.remove('modal-hidden');
    }
    
    function closeImportModal() {
        document.getElementById('importModal').classList.add('modal-hidden');
        document.getElementById('importForm').reset();
        document.getElementById('progressSection').classList.add('hidden');
    }
    
    // Add/Edit Subscriber Form
    document.getElementById('addSubscriberForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = document.getElementById('addBtn');
        const editId = document.getElementById('edit_id').value;
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';
        
        // Clear previous errors
        document.getElementById('emailError').classList.add('hidden');
        document.getElementById('listError').classList.add('hidden');
        
        const url = editId ? `/subscribers/${editId}` : '{{ route("subscribers.store") }}';
        const method = editId ? 'PUT' : 'POST';
        
        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
                window.location.reload();
            } else {
                if (data.errors) {
                    if (data.errors.email) {
                        document.getElementById('emailError').textContent = data.errors.email[0];
                        document.getElementById('emailError').classList.remove('hidden');
                    }
                    if (data.errors.list_id) {
                        document.getElementById('listError').textContent = data.errors.list_id[0];
                        document.getElementById('listError').classList.remove('hidden');
                    }
                } else {
                    alert('❌ Error: ' + (data.message || 'Failed to save subscriber'));
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Failed to save subscriber');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = editId ? 'Update Subscriber' : 'Add Subscriber';
        });
    });
    
    // Import Form
    document.getElementById('importForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const importBtn = document.getElementById('importBtn');
        const progressSection = document.getElementById('progressSection');
        
        importBtn.disabled = true;
        importBtn.textContent = 'Importing...';
        progressSection.classList.remove('hidden');
        
        // Simulate progress
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += 5;
            if (progress > 90) progress = 90;
            document.getElementById('progressPercent').textContent = progress + '%';
            document.getElementById('progressFill').style.width = progress + '%';
        }, 200);
        
        fetch('{{ route("subscribers.import") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            clearInterval(progressInterval);
            document.getElementById('progressPercent').textContent = '100%';
            document.getElementById('progressFill').style.width = '100%';
            
            if (data.success) {
                setTimeout(() => {
                    alert(`✅ Import Complete!\n\nAdded: ${data.imported || 0}\nSkipped: ${data.skipped || 0}`);
                    window.location.reload();
                }, 500);
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            clearInterval(progressInterval);
            console.error('Error:', error);
            alert('❌ Import failed');
        })
        .finally(() => {
            importBtn.disabled = false;
            importBtn.textContent = 'Start Import';
        });
    });
    
    // Delete Subscriber
    function deleteSubscriber(id) {
        if (!confirm('Are you sure you want to delete this subscriber?')) return;
        
        fetch(`/subscribers/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
                window.location.reload();
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Failed to delete subscriber');
        });
    }
    
    // Edit Subscriber
    function editSubscriber(id) {
        fetch(`/subscribers/${id}/edit`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const sub = data.subscriber;
                document.getElementById('edit_id').value = sub.id;
                document.querySelector('#addSubscriberForm input[name="email"]').value = sub.email;
                document.querySelector('#addSubscriberForm input[name="first_name"]').value = sub.first_name || '';
                document.querySelector('#addSubscriberForm input[name="last_name"]').value = sub.last_name || '';
                document.querySelector('#addSubscriberForm select[name="status"]').value = sub.status;
                document.querySelector('#addSubscriberForm select[name="list_id"]').value = sub.list_id;
                
                document.getElementById('modalTitle').textContent = 'Edit Subscriber';
                document.getElementById('addBtn').textContent = 'Update Subscriber';
                
                document.getElementById('addModal').classList.remove('modal-hidden');
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Failed to load subscriber data');
        });
    }
    
    // Filter functions
    function filterByStatus(status) {
        const url = new URL(window.location.href);
        if (status === 'all') {
            url.searchParams.delete('status');
        } else {
            url.searchParams.set('status', status);
        }
        window.location.href = url.toString();
    }
    
    function filterByList(listId) {
        const url = new URL(window.location.href);
        if (listId === 'all') {
            url.searchParams.delete('list_id');
        } else {
            url.searchParams.set('list_id', listId);
        }
        window.location.href = url.toString();
    }
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const searchTerm = this.value.trim();
            if (searchTerm) {
                const url = new URL(window.location.href);
                url.searchParams.set('search', searchTerm);
                window.location.href = url.toString();
            }
        }
    });
    
    // Close modals on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddModal();
            closeImportModal();
        }
    });

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
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
    });
    </script>
</body>
</html>