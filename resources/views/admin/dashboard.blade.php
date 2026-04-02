<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <!-- CSRF TOKEN MUST BE HERE -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - InfiMal</title>
    <!-- Rest of your code -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .neon-glow-primary {
             box-shadow: 0 0 8px rgba(43, 124, 238, 0.5), 0 0 20px rgba(43, 124, 238, 0.3);
        }
        .soft-shadow {
            box-shadow: 0px 25px 50px -12px rgba(0, 0, 0, 0.4);
        }
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 25s infinite linear;
        }
        @keyframes float {
            0% { transform: translateY(0) translateX(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100vh) translateX(5vw); opacity: 0; }
        }
        .badge-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
        }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-white/90">
    <div class="relative min-h-screen w-full overflow-hidden">
        <!-- Particle Background -->
        <div class="absolute inset-0 z-0">
            <div class="particle" style="width: 5px; height: 5px; left: 10%; animation-duration: 20s; animation-delay: -5s;"></div>
            <div class="particle" style="width: 3px; height: 3px; left: 25%; animation-duration: 30s; animation-delay: -2s;"></div>
            <div class="particle" style="width: 6px; height: 6px; left: 40%; animation-duration: 18s; animation-delay: -10s;"></div>
            <div class="particle" style="width: 4px; height: 4px; left: 60%; animation-duration: 28s; animation-delay: -7s;"></div>
            <div class="particle" style="width: 7px; height: 7px; left: 75%; animation-duration: 22s; animation-delay: -15s;"></div>
            <div class="particle" style="width: 3px; height: 3px; left: 90%; animation-duration: 35s; animation-delay: -3s;"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-[#0a192f] via-[#020c1b] to-[#020c1b]"></div>
        </div>
        
        <div class="relative z-10 flex h-full min-h-screen">
            <!-- SideNavBar (ADMIN VERSION) -->
            <nav class="flex-shrink-0 w-64 p-4">
                <div class="flex flex-col h-full gap-4">
                      <div class="flex items-center gap-3 p-2">
    <div class="p-2 rounded-full bg-purple-500/20 text-purple-500">
        <span class="material-symbols-outlined">admin_panel_settings</span>
    </div>
    <div class="flex flex-col">
        <h1 class="text-white text-base font-bold leading-normal">InfiMal <span class="badge-admin">ADMIN</span></h1>
        <p class="text-white/60 text-sm font-normal leading-normal">Control Room</p>
    </div>
</div>

                    <div class="flex flex-col gap-2 mt-4">
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-purple-500/20 text-white" href="{{ url('/admin/dashboard') }}">
                            <span class="material-symbols-outlined text-white">dashboard</span>
                            <p class="text-sm font-medium leading-normal">Admin Dashboard</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/admin/users') }}">
                            <span class="material-symbols-outlined">group</span>
                            <p class="text-sm font-medium leading-normal">All Users</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/admin/licenses') }}">
                            <span class="material-symbols-outlined">verified</span>
                            <p class="text-sm font-medium leading-normal">Licenses</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/admin/trust') }}">
                            <span class="material-symbols-outlined">security</span>
                            <p class="text-sm font-medium leading-normal">Trust System</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/admin/emails') }}">
                            <span class="material-symbols-outlined">monitoring</span>
                            <p class="text-sm font-medium leading-normal">Email Logs</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/admin/smtp') }}">
                            <span class="material-symbols-outlined">dns</span>
                            <p class="text-sm font-medium leading-normal">Global SMTP</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/admin/revenue') }}">
                            <span class="material-symbols-outlined">trending_up</span>
                            <p class="text-sm font-medium leading-normal">Revenue</p>
                        </a>
                        <!-- Switch to User Dashboard -->
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70 mt-4" href="{{ url('/dashboard') }}">
                            <span class="material-symbols-outlined">switch_account</span>
                            <p class="text-sm font-medium leading-normal">User Dashboard</p>
                        </a>
                    </div>
                    
                    <!-- Logout -->
                    <div class="mt-auto pt-4 border-t border-white/10">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70">
                                <span class="material-symbols-outlined">logout</span>
                                <p class="text-sm font-medium leading-normal">Logout</p>
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="flex-1 flex flex-col p-6 overflow-y-auto">
                <!-- TopNavBar -->
                <header class="flex-shrink-0 glass-card rounded-xl px-6 py-3 sticky top-6 z-20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-8">
                            <label class="flex flex-col w-72">
                                <div class="flex w-full flex-1 items-stretch rounded-lg h-10">
                                    <div class="text-white/60 flex bg-transparent items-center justify-center pl-3">
                                        <span class="material-symbols-outlined">search</span>
                                    </div>
                                    <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-0 border-none bg-transparent h-full placeholder:text-white/60 px-2 text-base font-normal leading-normal" placeholder="Search users, licenses..." value=""/>
                                </div>
                            </label>
                        </div>
                        <div class="flex flex-1 justify-end gap-4 items-center">
                            <button onclick="window.location.href='/admin/users/create'" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-purple-500 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-purple-500/90 transition-colors">
                                <span class="truncate">Add User</span>
                            </button>
                            <button onclick="window.location.href='/admin/licenses/create'" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-white/10 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-white/20 transition-colors">
                                <span class="truncate">Add License</span>
                            </button>
                            <button class="flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-white/10 text-white/80 gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5 hover:bg-white/20 transition-colors">
                                <span class="material-symbols-outlined">notifications</span>
                                @if($frozenUsers > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $frozenUsers }}</span>
                                @endif
                            </button>
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=FFFFFF&background=8B5CF6");'></div>
                        </div>
                    </div>
                </header>

                <!-- Hero Section -->
                <div class="relative my-8 glass-card rounded-xl p-8 lg:p-12 overflow-hidden soft-shadow">
                    <div class="absolute -top-1/4 -left-1/4 w-1/2 h-1/2 bg-purple-500/30 rounded-full filter blur-3xl opacity-50"></div>
                    <div class="absolute -bottom-1/4 -right-1/4 w-1/2 h-1/2 bg-[#f59e0b]/30 rounded-full filter blur-3xl opacity-50"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <h1 class="text-white tracking-light text-3xl font-bold leading-tight">Control Room, {{ Auth::user()->name }}!</h1>
                            <p class="text-white/70 text-base font-normal leading-normal pt-2">
                                Platform Overview • {{ now()->format('l, F j, Y') }}
                                <span class="badge-admin ml-2">OWNER MODE</span>
                            </p>
                            <div class="flex items-center gap-4 mt-4">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-purple-400">shield</span>
                                    <span class="text-white/70 text-sm">Admin Access Active</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-400">schedule</span>
                                    <span class="text-white/70 text-sm">
                                        Last updated: {{ now()->diffForHumans() }}
                                    </span>
                                </div>
                                <div id="platform-health" class="flex items-center gap-2">
                                    <span class="animate-pulse w-2 h-2 bg-green-400 rounded-full"></span>
                                    <span class="text-white/60 text-xs">Platform Healthy</span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-48 h-48 bg-purple-500/20 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-purple-500 text-6xl">admin_panel_settings</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Grid -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Stats Section -->
                    <div class="lg:col-span-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Stat Cards (ADMIN DATA) -->
                            <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                                <div class="flex items-center justify-between">
                                    <p class="text-white/80 text-base font-medium">Total Users</p>
                                    <span class="material-symbols-outlined text-blue-400">group</span>
                                </div>
                                <p class="text-white text-4xl font-bold">{{ number_format($totalUsers) }}</p>
                                <p class="text-green-400 text-sm">{{ $usersToday ?? 0 }} new today</p>
                            </div>
                            
                            <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                                <div class="flex items-center justify-between">
                                    <p class="text-white/80 text-base font-medium">Active Licenses</p>
                                    <span class="material-symbols-outlined text-green-400">verified</span>
                                </div>
                                <p class="text-white text-4xl font-bold">{{ $activeLicenses }}</p>
                                <p class="text-green-400 text-sm">{{ $activeLicensesPercentage ?? 0 }}% of users</p>
                            </div>
                            
                            <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                                <div class="flex items-center justify-between">
                                    <p class="text-white/80 text-base font-medium">Emails Sent</p>
                                    <span class="material-symbols-outlined text-yellow-400">send</span>
                                </div>
                                <p class="text-white text-4xl font-bold">{{ number_format($totalEmailsSent) }}</p>
                                <p class="text-green-400 text-sm">{{ $emailsToday ?? 0 }} today</p>
                            </div>
                            
                            <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                                <div class="flex items-center justify-between">
                                    <p class="text-white/80 text-base font-medium">Frozen Users</p>
                                    <span class="material-symbols-outlined text-red-400">block</span>
                                </div>
                                <p class="text-white text-4xl font-bold">{{ $frozenUsers }}</p>
                                <p class="{{ $frozenUsers > 0 ? 'text-red-400' : 'text-green-400' }} text-sm">
                                    {{ $frozenUsers > 0 ? 'Action Required' : 'All Good' }}
                                </p>
                            </div>
                            
                            <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                                <div class="flex items-center justify-between">
                                    <p class="text-white/80 text-base font-medium">Total Revenue</p>
                                    <span class="material-symbols-outlined text-green-400">payments</span>
                                </div>
                                <p class="text-white text-4xl font-bold">${{ number_format($totalRevenue ?? 0) }}</p>
                                <p class="text-green-400 text-sm">${{ $revenueToday ?? 0 }} today</p>
                            </div>
                            
                            <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                                <div class="flex items-center justify-between">
                                    <p class="text-white/80 text-base font-medium">Avg Trust Score</p>
                                    <span class="material-symbols-outlined text-purple-400">security</span>
                                </div>
                                <p class="text-white text-4xl font-bold">{{ $avgTrustScore ?? 85 }}</p>
                                <p class="{{ ($avgTrustScore ?? 85) > 70 ? 'text-green-400' : 'text-yellow-400' }} text-sm">
                                    {{ ($avgTrustScore ?? 85) > 70 ? 'Healthy' : 'Needs Attention' }}
                                </p>
                            </div>
                        </div>

                        <!-- Trust Stage Distribution -->
                        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="rounded-xl p-6 glass-card soft-shadow">
                                <h3 class="text-white font-bold text-lg mb-4">Trust Stage Distribution</h3>
                                <div class="space-y-3">
                                    @foreach($trustStats as $stat)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-3 h-3 rounded-full 
                                                @if($stat->stage == 1) bg-green-500
                                                @elseif($stat->stage == 2) bg-blue-500
                                                @elseif($stat->stage == 3) bg-yellow-500
                                                @elseif($stat->stage == 4) bg-orange-500
                                                @else bg-red-500 @endif">
                                            </div>
                                            <span class="text-white/70">Stage {{ $stat->stage }}</span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="text-white font-bold">{{ $stat->total }}</span>
                                            <span class="text-white/50 text-sm">users</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Recent Activity -->
                            <div class="rounded-xl p-6 glass-card soft-shadow">
                                <h3 class="text-white font-bold text-lg mb-4">Recent Platform Activity</h3>
                                <div class="space-y-3">
                                    @foreach($recentActivity as $activity)
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 rounded-full bg-white/10">
                                            <span class="material-symbols-outlined text-sm">
                                                @if($activity->type == 'license') verified
                                                @elseif($activity->type == 'freeze') block
                                                @elseif($activity->type == 'email') send
                                                @else person
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-white text-sm">{{ $activity->description }}</p>
                                            <p class="text-white/50 text-xs">{{ $activity->time }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel -->
                    <div class="lg:col-span-1 flex flex-col gap-6">
                        <!-- Today's Overview -->
                        <div class="flex flex-col p-6 rounded-xl glass-card soft-shadow neon-glow-primary">
                            <h3 class="text-white font-bold text-lg mb-4">Today's Overview</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-white/70 text-sm">New Users</p>
                                        <p class="text-white text-xl font-bold">{{ $usersToday ?? 0 }}</p>
                                    </div>
                                    <span class="material-symbols-outlined text-blue-400">person_add</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-white/70 text-sm">Emails Sent</p>
                                        <p class="text-white text-xl font-bold">{{ $emailsToday ?? 0 }}</p>
                                    </div>
                                    <span class="material-symbols-outlined text-green-400">send</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-white/70 text-sm">New Licenses</p>
                                        <p class="text-white text-xl font-bold">{{ $licensesToday ?? 0 }}</p>
                                    </div>
                                    <span class="material-symbols-outlined text-purple-400">verified</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-white/70 text-sm">Revenue Today</p>
                                        <p class="text-white text-xl font-bold">${{ $revenueToday ?? 0 }}</p>
                                    </div>
                                    <span class="material-symbols-outlined text-yellow-400">payments</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Admin Actions -->
                        <div class="flex flex-col p-6 rounded-xl glass-card soft-shadow">
                            <h3 class="text-white font-bold text-lg mb-4">Quick Admin Actions</h3>
                            <div class="space-y-3">
                                <a href="{{ url('/admin/users') }}" class="w-full flex items-center gap-3 p-3 rounded-lg bg-purple-500/20 hover:bg-purple-500/30 transition-colors">
                                    <span class="material-symbols-outlined text-purple-500">manage_accounts</span>
                                    <span class="text-white text-sm">Manage Users</span>
                                </a>
                                <a href="{{ url('/admin/trust/manage') }}" class="w-full flex items-center gap-3 p-3 rounded-lg bg-white/10 hover:bg-white/20 transition-colors">
                                    <span class="material-symbols-outlined text-red-500">shield</span>
                                    <span class="text-white text-sm">Trust Management</span>
                                </a>
                                <a href="{{ url('/admin/emails') }}" class="w-full flex items-center gap-3 p-3 rounded-lg bg-white/10 hover:bg-white/20 transition-colors">
                                    <span class="material-symbols-outlined text-blue-500">monitoring</span>
                                    <span class="text-white text-sm">Email Analytics</span>
                                </a>
                                <a href="{{ url('/admin/settings') }}" class="w-full flex items-center gap-3 p-3 rounded-lg bg-white/10 hover:bg-white/20 transition-colors">
                                    <span class="material-symbols-outlined text-yellow-500">settings</span>
                                    <span class="text-white text-sm">Platform Settings</span>
                                </a>
                            </div>
                        </div>

                        <!-- System Health -->
                        <div class="flex flex-col p-6 rounded-xl glass-card soft-shadow">
                            <h3 class="text-white font-bold text-lg mb-4">System Health</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-white/70 text-sm">Database</span>
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                                        <span class="text-white text-sm">Healthy</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-white/70 text-sm">SMTP Service</span>
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 {{ $emailsToday > 0 ? 'bg-green-400' : 'bg-yellow-400' }} rounded-full"></span>
                                        <span class="text-white text-sm">{{ $emailsToday > 0 ? 'Active' : 'Idle' }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-white/70 text-sm">License System</span>
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 {{ $activeLicenses > 0 ? 'bg-green-400' : 'bg-yellow-400' }} rounded-full"></span>
                                        <span class="text-white text-sm">{{ $activeLicenses > 0 ? 'Active' : 'No Sales' }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-white/70 text-sm">Trust System</span>
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                                        <span class="text-white text-sm">Monitoring</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="mt-6 rounded-xl glass-card soft-shadow overflow-hidden">
                    <div class="p-6 border-b border-white/10">
                        <h3 class="text-white font-bold text-lg">Recent Users</h3>
                        <p class="text-white/50 text-sm">Latest registered users with trust status</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">User</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Stage</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Trust Score</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">License</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Status</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr class="border-b border-white/5 hover:bg-white/5">
                                    <td class="py-3 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-center bg-cover" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode($user->email) }}&color=FFFFFF&background=3B82F6");'></div>
                                            <div>
                                                <p class="text-white font-medium">{{ $user->email }}</p>
                                                <p class="text-white/50 text-xs">{{ $user->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold 
                                            @if($user->stage == 1) bg-green-500/20 text-green-400
                                            @elseif($user->stage == 2) bg-blue-500/20 text-blue-400
                                            @elseif($user->stage == 3) bg-yellow-500/20 text-yellow-400
                                            @elseif($user->stage == 4) bg-orange-500/20 text-orange-400
                                            @else bg-red-500/20 text-red-400 @endif">
                                            Stage {{ $user->stage ?? 1 }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6">
                                        <div class="flex items-center gap-2">
                                            <div class="w-16 h-2 bg-white/10 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-green-400 to-blue-400 rounded-full" style="width: {{ $user->trust_score ?? 100 }}%"></div>
                                            </div>
                                            <span class="text-white font-medium">{{ $user->trust_score ?? 100 }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold 
                                            @if($user->license_status == 'active') bg-green-500/20 text-green-400
                                            @elseif($user->license_status == 'expired') bg-red-500/20 text-red-400
                                            @else bg-gray-500/20 text-gray-400 @endif">
                                            {{ $user->license_status ?? 'none' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6">
                                        @if($user->is_frozen)
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-500/20 text-red-400">Frozen</span>
                                        @else
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-400">Active</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6">
                                        <div class="flex items-center gap-2">
                                            <button onclick="viewUser({{ $user->id }})" class="p-1 hover:bg-white/10 rounded">
                                                <span class="material-symbols-outlined text-white/70 text-sm">visibility</span>
                                            </button>
                                            <button onclick="manageTrust({{ $user->id }})" class="p-1 hover:bg-white/10 rounded">
                                                <span class="material-symbols-outlined text-white/70 text-sm">shield</span>
                                            </button>
                                            @if($user->is_frozen)
                                            <button onclick="unfreezeUser({{ $user->id }})" class="p-1 hover:bg-white/10 rounded">
                                                <span class="material-symbols-outlined text-green-400 text-sm">lock_open</span>
                                            </button>
                                            @else
                                            <button onclick="freezeUser({{ $user->id }})" class="p-1 hover:bg-white/10 rounded">
                                                <span class="material-symbols-outlined text-red-400 text-sm">lock</span>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Admin actions
        function viewUser(id) {
            window.location.href = `/admin/users/${id}`;
        }

        function manageTrust(id) {
            window.location.href = `/admin/trust/${id}/manage`;
        }

        function freezeUser(id) {
            if(confirm('Freeze this user? They will not be able to send emails.')) {
                fetch(`/admin/users/${id}/freeze`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(() => location.reload());
            }
        }

        function unfreezeUser(id) {
            if(confirm('Unfreeze this user?')) {
                fetch(`/admin/users/${id}/unfreeze`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(() => location.reload());
            }
        }

        // Real-time updates
        let lastUpdate = new Date();
        
        setInterval(() => {
            fetch('/admin/api/stats')
                .then(res => res.json())
                .then(data => {
                    // Update counters
                    document.querySelectorAll('[data-stat]').forEach(el => {
                        const stat = el.getAttribute('data-stat');
                        if(data[stat]) {
                            animateValue(el, parseInt(el.textContent), data[stat], 500);
                        }
                    });
                    lastUpdate = new Date();
                });
        }, 30000); // Update every 30 seconds

        // Number animation
        function animateValue(element, start, end, duration) {
            const startTime = performance.now();
            const difference = end - start;
            
            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const value = Math.floor(start + (difference * progress));
                element.textContent = value.toLocaleString();
                
                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            }
            
            requestAnimationFrame(update);
        }

        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            console.log('? Admin Dashboard Loaded - Owner Mode Active');
            // Add real-time indicators
            document.getElementById('last-updated').textContent = lastUpdate.toLocaleTimeString();
        });
    </script>
</body>
</html>
