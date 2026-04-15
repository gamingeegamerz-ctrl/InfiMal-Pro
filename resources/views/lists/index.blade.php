<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lists - InfiMal</title>
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
                    <a class="nav-link active flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 dark:text-white font-medium text-sm" href="{{ url('/lists') }}">
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
                            <div class="flex justify-center mb-6">
                                <a href="{{ url('/') }}">
                                    <img src="{{ asset('logo.png') }}" alt="INFIMAL" class="h-12 w-auto">
                                </a>
                            </div>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500">search</span>
                                <input type="text" placeholder="Search lists..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100" />
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="createNewList()" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg font-semibold text-sm hover-glow transition-all duration-300">
                                Create List
                            </button>
                            <button onclick="importSubscribers()" class="border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 px-6 py-2 rounded-lg font-semibold text-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition-all duration-300">
                                Import CSV
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
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Email Lists Management</h1>
                            <p class="text-gray-600 dark:text-slate-300 mb-4">Manage your email lists, track performance, and segment your audience</p>
                            <div class="flex items-center gap-6">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-500 text-xl">verified</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        Total Lists: {{ $totalLists ?? 0 }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500 text-xl">group</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        Total Subscribers: {{ $totalSubscribers ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden lg:block">
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 rounded-2xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-6xl">list_alt</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Stat Card 1 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Total Lists</h3>
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">list_alt</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $totalLists ?? 0 }}</p>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">{{ $growthRate ?? 0 }}% growth</p>
                    </div>
                    
                    <!-- Stat Card 2 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Total Subscribers</h3>
                            <div class="p-2 bg-green-100 dark:bg-green-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">group</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $totalSubscribers ?? 0 }}</p>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">{{ $activeSubscribers ?? 0 }} active</p>
                    </div>
                    
                    <!-- Stat Card 3 -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Avg. Subscribers</h3>
                            <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">trending_up</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                            @if(($totalLists ?? 0) > 0 && ($totalSubscribers ?? 0) > 0)
                                {{ number_format(($totalSubscribers ?? 0) / $totalLists) }}
                            @else
                                0
                            @endif
                        </p>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">per list</p>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Lists Table -->
                    <div class="lg:col-span-3">
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-gray-900 dark:text-white font-bold text-lg">Your Email Lists</h3>
                                <span class="text-gray-600 dark:text-slate-400 text-sm">{{ ($lists->total() ?? 0) }} lists found</span>
                            </div>
                            
                            @if($lists && $lists->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead>
                                        <tr class="text-gray-600 dark:text-slate-400 text-sm border-b border-gray-200 dark:border-slate-700">
                                            <th class="pb-3 text-left font-semibold">List Name</th>
                                            <th class="pb-3 text-left font-semibold">Subscribers</th>
                                            <th class="pb-3 text-left font-semibold">Active</th>
                                            <th class="pb-3 text-left font-semibold">Created</th>
                                            <th class="pb-3 text-left font-semibold">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lists as $list)
                                        <tr class="border-b border-gray-100 dark:border-slate-800 last:border-0 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                            <td class="py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/50">
                                                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm">list_alt</span>
                                                    </div>
                                                    <div>
                                                        <p class="text-gray-900 dark:text-white font-medium">{{ $list->name ?? 'Sample List' }}</p>
                                                        <p class="text-gray-600 dark:text-slate-400 text-sm">{{ $list->description ?? 'No description' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                <span class="text-gray-900 dark:text-white font-medium">{{ $list->subscribers_count ?? 0 }}</span>
                                            </td>
                                            <td class="py-4">
                                                <span class="text-green-600 dark:text-green-400">{{ $list->active_subscribers_count ?? 0 }}</span>
                                            </td>
                                            <td class="py-4">
                                                <span class="text-gray-600 dark:text-slate-400 text-sm">
                                                    @if(isset($list->created_at))
                                                        {{ $list->created_at->format('Y-m-d') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ url('/lists/' . ($list->id ?? 1)) }}" class="p-1.5 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors" title="View">
                                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                                    </a>
                                                    <button onclick="editList({{ $list->id ?? 1 }})" class="p-1.5 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors" title="Edit">
                                                        <span class="material-symbols-outlined text-sm">edit</span>
                                                    </button>
                                                    <button onclick="deleteList({{ $list->id ?? 1 }})" class="p-1.5 rounded-lg bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800 transition-colors" title="Delete">
                                                        <span class="material-symbols-outlined text-sm">delete</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-12">
                                <div class="flex flex-col items-center gap-4">
                                    <span class="material-symbols-outlined text-5xl text-gray-400 dark:text-slate-600">list_alt</span>
                                    <p class="text-gray-600 dark:text-slate-400">No lists found. Create your first list!</p>
                                    <button onclick="createNewList()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold">
                                        Create List
                                    </button>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Pagination -->
                            @if($lists && $lists->hasPages())
                            <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200 dark:border-slate-700">
                                <div class="text-gray-600 dark:text-slate-400 text-sm">
                                    Showing {{ $lists->firstItem() }} to {{ $lists->lastItem() }} of {{ $lists->total() }} lists
                                </div>
                                <div class="flex items-center gap-2">
                                    {{ $lists->links() }}
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
                                <button onclick="createNewList()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-800/30 transition-colors">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">add_circle</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">Create New List</span>
                                </button>
                                <button onclick="importSubscribers()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">upload</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">Import Subscribers</span>
                                </button>
                                <a href="{{ url('/subscribers') }}" class="w-full flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">group</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">Manage Subscribers</span>
                                </a>
                                <a href="{{ url('/dashboard') }}" class="w-full flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                                    <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400">dashboard</span>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium">Back to Dashboard</span>
                                </a>
                            </div>
                        </div>

                        <!-- List Stats -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">List Performance</h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-gray-600 dark:text-slate-400 text-sm">Avg. Open Rate</p>
                                    <p class="text-gray-900 dark:text-white text-xl font-bold">{{ $avgOpenRate ?? 0 }}%</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 dark:text-slate-400 text-sm">Avg. Click Rate</p>
                                    <p class="text-gray-900 dark:text-white text-xl font-bold">{{ $avgClickRate ?? 0 }}%</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 dark:text-slate-400 text-sm">Active Rate</p>
                                    <p class="text-gray-900 dark:text-white text-xl font-bold">
                                        @if(($totalSubscribers ?? 0) > 0)
                                            {{ number_format(($activeSubscribers ?? 0)/$totalSubscribers*100, 1) }}%
                                        @else
                                            0%
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Lists -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">Recent Lists</h3>
                            <div class="space-y-3">
                                @php
                                    // Get recent lists from the $lists collection
                                    $recentLists = $lists ? $lists->take(3) : collect();
                                @endphp
                                
                                @if($recentLists->count() > 0)
                                    @foreach($recentLists as $list)
                                    <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50 dark:bg-slate-800/50">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-blue-500 text-sm">list_alt</span>
                                            <span class="text-gray-900 dark:text-white text-sm truncate">{{ Str::limit($list->name ?? 'Untitled', 20) }}</span>
                                        </div>
                                        <span class="text-gray-600 dark:text-slate-400 text-sm font-medium">{{ $list->subscribers_count ?? 0 }}</span>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-4">
                                        <span class="material-symbols-outlined text-gray-400 dark:text-slate-600 text-3xl">bar_chart</span>
                                        <p class="text-gray-600 dark:text-slate-400 mt-2">No list data</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Create List Modal -->
    <div id="createListModal" class="modal-backdrop modal-hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-md shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-gray-900 dark:text-white text-xl font-bold">Create New List</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
            </div>
            <form id="listForm" action="{{ route('lists.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">List Name</label>
                        <input type="text" name="name" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter list name" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Optional description"></textarea>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold transition-colors">
                            Create List
                        </button>
                    </div>
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
    function createNewList() {
        document.getElementById('createListModal').classList.remove('modal-hidden');
    }

    function closeModal() {
        document.getElementById('createListModal').classList.add('modal-hidden');
    }

    function importSubscribers() {
        alert('CSV import functionality would open here');
    }

    function editList(listId) {
        alert('Edit list: ' + listId);
    }

    function deleteList(listId) {
        if (confirm('Are you sure you want to delete this list?')) {
            // Simple delete action
            window.location.href = '/lists/' + listId + '/delete';
        }
    }

    // Close modal when clicking outside
    document.getElementById('createListModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Close modal on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
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
    });
    </script>
</body>
</html>