<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trust System - InfiMal Admin</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
        .soft-shadow {
            box-shadow: 0px 25px 50px -12px rgba(0, 0, 0, 0.4);
        }
        .badge-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
        }
        .neon-glow-red {
            box-shadow: 0 0 8px rgba(239, 68, 68, 0.5), 0 0 20px rgba(239, 68, 68, 0.3);
        }
        .table-container {
            max-height: 600px;
            overflow-y: auto;
        }
        .table-container::-webkit-scrollbar {
            width: 6px;
        }
        .table-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 3px;
        }
        .table-container::-webkit-scrollbar-thumb {
            background: rgba(239, 68, 68, 0.5);
            border-radius: 3px;
        }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-white/90 min-h-screen">
    <div class="relative min-h-screen w-full overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-[#0a192f] via-[#020c1b] to-[#020c1b]"></div>
        
        <div class="relative z-10 flex h-full min-h-screen">
            <!-- Sidebar - SAME AS DASHBOARD -->
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
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/admin/dashboard') }}">
                            <span class="material-symbols-outlined">dashboard</span>
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
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-red-500/20 text-white" href="{{ url('/admin/trust') }}">
                            <span class="material-symbols-outlined text-white">security</span>
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
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70 mt-4" href="{{ url('/dashboard') }}">
                            <span class="material-symbols-outlined">switch_account</span>
                            <p class="text-sm font-medium leading-normal">User Dashboard</p>
                        </a>
                    </div>
                    
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

            <main class="flex-1 flex flex-col p-6 overflow-y-auto">
                <header class="flex-shrink-0 glass-card rounded-xl px-6 py-3 sticky top-6 z-20 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-8">
                            <label class="flex flex-col w-72">
                                <div class="flex w-full flex-1 items-stretch rounded-lg h-10">
                                    <div class="text-white/60 flex bg-transparent items-center justify-center pl-3">
                                        <span class="material-symbols-outlined">search</span>
                                    </div>
                                    <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-0 border-none bg-transparent h-full placeholder:text-white/60 px-2 text-base font-normal leading-normal" placeholder="Search users, trust scores..." value="" id="searchInput"/>
                                </div>
                            </label>
                        </div>
                        <div class="flex flex-1 justify-end gap-4 items-center">
                            <button onclick="window.location.href='/admin/trust/freeze-all'" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-red-500 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-red-500/90 transition-colors">
                                <span class="material-symbols-outlined mr-2">block</span>
                                <span class="truncate">Freeze All Low</span>
                            </button>
                            <button onclick="exportTrustData()" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-white/10 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-white/20 transition-colors">
                                <span class="material-symbols-outlined mr-2">download</span>
                                <span class="truncate">Export</span>
                            </button>
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? "Admin") }}&color=FFFFFF&background=8B5CF6");'></div>
                        </div>
                    </div>
                </header>

                <div class="relative mb-8 glass-card rounded-xl p-8 lg:p-12 overflow-hidden soft-shadow neon-glow-red">
                    <div class="absolute -top-1/4 -left-1/4 w-1/2 h-1/2 bg-red-500/30 rounded-full filter blur-3xl opacity-50"></div>
                    <div class="absolute -bottom-1/4 -right-1/4 w-1/2 h-1/2 bg-[#3b82f6]/30 rounded-full filter blur-3xl opacity-50"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <h1 class="text-white tracking-light text-3xl font-bold leading-tight">Trust System Management</h1>
                            <p class="text-white/70 text-base font-normal leading-normal pt-2">
                                Monitor user trust scores & security • {{ now()->format('F j, Y') }}
                                <span class="badge-admin ml-2">TRUST CONTROL</span>
                            </p>
                            <div class="flex items-center gap-4 mt-4">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-400">shield</span>
                                    <span class="text-white/70 text-sm">High Trust: {{ $highTrustUsers ?? 0 }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-yellow-400">warning</span>
                                    <span class="text-white/70 text-sm">Medium Trust: {{ $mediumTrustUsers ?? 0 }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-red-400">block</span>
                                    <span class="text-white/70 text-sm">Low Trust: {{ $lowTrustUsers ?? 0 }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-red-400">lock</span>
                                    <span class="text-white/70 text-sm">Frozen Users: {{ $frozenUsers ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-48 h-48 bg-red-500/20 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-red-500 text-6xl">security</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- REAL TRUST STATISTICS CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-white/80 text-base font-medium">Avg. Trust Score</p>
                            <span class="material-symbols-outlined text-blue-400">bar_chart</span>
                        </div>
                        <p class="text-white text-4xl font-bold">{{ $avgTrustScore ?? 85 }}</p>
                        <p class="{{ ($avgTrustScore ?? 85) > 70 ? 'text-green-400' : 'text-red-400' }} text-sm">
                            {{ ($avgTrustScore ?? 85) > 70 ? 'Good' : 'Needs Attention' }}
                        </p>
                    </div>
                    
                    <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-white/80 text-base font-medium">Frozen Users</p>
                            <span class="material-symbols-outlined text-red-400">block</span>
                        </div>
                        <p class="text-white text-4xl font-bold">{{ $frozenUsers ?? 0 }}</p>
                        <p class="text-red-400 text-sm">{{ $newFrozenToday ?? 0 }} frozen today</p>
                    </div>
                    
                    <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-white/80 text-base font-medium">Stage Distribution</p>
                            <span class="material-symbols-outlined text-purple-400">layers</span>
                        </div>
                        <p class="text-white text-4xl font-bold">{{ $stageDistribution->sum() ?? 0 }}</p>
                        <p class="text-blue-400 text-sm">{{ $stage1Count ?? 0 }} in Stage 1</p>
                    </div>
                    
                    <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-white/80 text-base font-medium">Active Monitoring</p>
                            <span class="material-symbols-outlined text-green-400">monitoring</span>
                        </div>
                        <p class="text-white text-4xl font-bold">{{ $monitoredUsers ?? 0 }}</p>
                        <p class="text-green-400 text-sm">{{ $alertsToday ?? 0 }} alerts today</p>
                    </div>
                </div>

                <!-- TRUST STAGE BREAKDOWN -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="rounded-xl glass-card soft-shadow p-6">
                        <h3 class="text-white font-bold text-lg mb-4">Trust Stage Distribution</h3>
                        <div class="space-y-4">
                            @php
                                $stages = [
                                    1 => ['name' => 'Stage 1: New Users', 'color' => 'bg-green-500', 'text' => 'text-green-400', 'count' => $stage1Count ?? 0],
                                    2 => ['name' => 'Stage 2: Verified', 'color' => 'bg-blue-500', 'text' => 'text-blue-400', 'count' => $stage2Count ?? 0],
                                    3 => ['name' => 'Stage 3: Watchlist', 'color' => 'bg-yellow-500', 'text' => 'text-yellow-400', 'count' => $stage3Count ?? 0],
                                    4 => ['name' => 'Stage 4: Restricted', 'color' => 'bg-orange-500', 'text' => 'text-orange-400', 'count' => $stage4Count ?? 0],
                                    5 => ['name' => 'Stage 5: Frozen', 'color' => 'bg-red-500', 'text' => 'text-red-400', 'count' => $stage5Count ?? 0],
                                ];
                                $total = array_sum(array_column($stages, 'count'));
                            @endphp
                            
                            @foreach($stages as $stageNum => $stage)
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 rounded-full {{ $stage['color'] }}"></div>
                                        <span class="text-white/70 text-sm">{{ $stage['name'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-white font-bold">{{ $stage['count'] }}</span>
                                        <span class="text-white/50 text-sm">
                                            @if($total > 0)
                                            {{ number_format(($stage['count'] / $total) * 100, 1) }}%
                                            @else
                                            0%
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full {{ $stage['color'] }} rounded-full" 
                                         style="width: {{ $total > 0 ? ($stage['count'] / $total) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="rounded-xl glass-card soft-shadow p-6">
                        <h3 class="text-white font-bold text-lg mb-4">Trust Score Range</h3>
                        <div class="space-y-4">
                            @php
                                $ranges = [
                                    ['min' => 80, 'max' => 100, 'label' => 'High Trust (80-100)', 'color' => 'bg-green-500', 'count' => $highTrustUsers ?? 0],
                                    ['min' => 50, 'max' => 79, 'label' => 'Medium Trust (50-79)', 'color' => 'bg-yellow-500', 'count' => $mediumTrustUsers ?? 0],
                                    ['min' => 0, 'max' => 49, 'label' => 'Low Trust (0-49)', 'color' => 'bg-red-500', 'count' => $lowTrustUsers ?? 0],
                                ];
                            @endphp
                            
                            @foreach($ranges as $range)
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-white/70 text-sm">{{ $range['label'] }}</span>
                                    <span class="text-white font-bold">{{ $range['count'] }} users</span>
                                </div>
                                <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full {{ $range['color'] }} rounded-full" 
                                         style="width: {{ $total > 0 ? ($range['count'] / $total) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- USERS TRUST TABLE -->
                <div class="mt-6 rounded-xl glass-card soft-shadow overflow-hidden">
                    <div class="p-6 border-b border-white/10 flex justify-between items-center">
                        <div>
                            <h3 class="text-white font-bold text-lg">User Trust Monitoring</h3>
                            <p class="text-white/50 text-sm">Showing {{ $users->count() ?? 0 }} users with trust scores</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <span class="text-white/50 text-sm">Filter:</span>
                                <select class="bg-transparent border border-white/20 rounded-lg px-3 py-1 text-white text-sm" onchange="filterUsers(this.value)">
                                    <option value="all">All Users</option>
                                    <option value="frozen">Frozen</option>
                                    <option value="low">Low Trust</option>
                                    <option value="medium">Medium Trust</option>
                                    <option value="high">High Trust</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">User</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Trust Score</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Stage</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Emails/Hour</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Last Active</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Status</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="trustTable">
                                @if(isset($users) && $users->count() > 0)
                                    @foreach($users as $user)
                                    @php
                                        $userId = $user->user_id ?? $user->id;
                                        $userEmail = $user->email ?? 'N/A';
                                        $userName = $user->name ?? 'No Name';
                                        $trustScore = $user->trust_score ?? 100;
                                        $stage = $user->stage ?? 1;
                                        $emailsPerHour = $user->emails_last_hour ?? 0;
                                        $lastActive = $user->last_activity_at ?? $user->created_at;
                                        $isFrozen = $user->is_frozen ?? false;
                                        $frozenAt = $user->frozen_at ?? null;
                                        
                                        // Determine trust level
                                        $trustLevel = 'high';
                                        if ($trustScore < 50) {
                                            $trustLevel = 'low';
                                        } elseif ($trustScore < 80) {
                                            $trustLevel = 'medium';
                                        }
                                    @endphp
                                    
                                    <tr class="border-b border-white/5 hover:bg-white/5 trust-row" data-level="{{ $trustLevel }}" data-frozen="{{ $isFrozen ? 'true' : 'false' }}">
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-center bg-cover" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode($userName) }}&color=FFFFFF&background=3B82F6");'></div>
                                                <div>
                                                    <p class="text-white font-medium">{{ $userName }}</p>
                                                    <p class="text-white/50 text-xs">{{ $userEmail }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-24 h-2 bg-white/10 rounded-full overflow-hidden">
                                                    <div class="h-full 
                                                        @if($trustScore >= 80) bg-green-500
                                                        @elseif($trustScore >= 50) bg-yellow-500
                                                        @else bg-red-500 @endif
                                                        rounded-full" style="width: {{ $trustScore }}%">
                                                    </div>
                                                </div>
                                                <span class="text-white font-bold">{{ $trustScore }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6">
                                            <span class="px-2 py-1 rounded-full text-xs font-bold 
                                                @if($stage == 1) bg-green-500/20 text-green-400
                                                @elseif($stage == 2) bg-blue-500/20 text-blue-400
                                                @elseif($stage == 3) bg-yellow-500/20 text-yellow-400
                                                @elseif($stage == 4) bg-orange-500/20 text-orange-400
                                                @else bg-red-500/20 text-red-400 @endif">
                                                Stage {{ $stage }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6">
                                            <p class="text-white text-sm">{{ $emailsPerHour }}</p>
                                            <p class="text-white/50 text-xs">per hour</p>
                                        </td>
                                        <td class="py-3 px-6">
                                            @if($lastActive)
                                            <p class="text-white text-sm">{{ \Carbon\Carbon::parse($lastActive)->format('M d, H:i') }}</p>
                                            <p class="text-white/50 text-xs">
                                                {{ \Carbon\Carbon::parse($lastActive)->diffForHumans() }}
                                            </p>
                                            @else
                                            <span class="text-white/50 italic">Never</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6">
                                            <span class="px-2 py-1 rounded-full text-xs font-bold 
                                                @if($isFrozen) bg-red-500/20 text-red-400
                                                @else bg-green-500/20 text-green-400 @endif
                                                flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs">
                                                    @if($isFrozen) block @else check_circle @endif
                                                </span>
                                                @if($isFrozen) Frozen @else Active @endif
                                            </span>
                                            @if($isFrozen && $frozenAt)
                                            <p class="text-white/50 text-xs mt-1">
                                                {{ \Carbon\Carbon::parse($frozenAt)->format('M d') }}
                                            </p>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-2">
                                                <button onclick="viewUserTrust({{ $userId }})" class="p-2 hover:bg-white/10 rounded" title="View Details">
                                                    <span class="material-symbols-outlined text-white/70">visibility</span>
                                                </button>
                                                <button onclick="adjustTrust({{ $userId }})" class="p-2 hover:bg-white/10 rounded" title="Adjust Trust">
                                                    <span class="material-symbols-outlined text-blue-400">edit</span>
                                                </button>
                                                @if($isFrozen)
                                                <button onclick="unfreezeUser({{ $userId }})" class="p-2 hover:bg-white/10 rounded" title="Unfreeze User">
                                                    <span class="material-symbols-outlined text-green-400">lock_open</span>
                                                </button>
                                                @else
                                                <button onclick="freezeUser({{ $userId }})" class="p-2 hover:bg-white/10 rounded" title="Freeze User">
                                                    <span class="material-symbols-outlined text-red-400">lock</span>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-white/50">
                                        <span class="material-symbols-outlined text-4xl mb-2">security</span>
                                        <p class="text-lg">No trust data found</p>
                                        <p class="text-sm mt-2">User trust data will appear here</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    @if(isset($users) && $users->hasPages())
                    <div class="p-6 border-t border-white/10">
                        <div class="flex items-center justify-between">
                            <p class="text-white/50 text-sm">
                                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                            </p>
                            <div class="flex items-center gap-2">
                                @if($users->onFirstPage())
                                <span class="px-3 py-1 rounded-lg bg-white/5 text-white/30 cursor-not-allowed">Previous</span>
                                @else
                                <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1 rounded-lg bg-white/10 text-white hover:bg-white/20">Previous</a>
                                @endif
                                
                                @foreach($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                                <a href="{{ $url }}" class="px-3 py-1 rounded-lg {{ $users->currentPage() == $page ? 'bg-red-500 text-white' : 'bg-white/10 text-white hover:bg-white/20' }}">
                                    {{ $page }}
                                </a>
                                @endforeach
                                
                                @if($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1 rounded-lg bg-white/10 text-white hover:bg-white/20">Next</a>
                                @else
                                <span class="px-3 py-1 rounded-lg bg-white/5 text-white/30 cursor-not-allowed">Next</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.trust-row');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        function filterUsers(filter) {
            const rows = document.querySelectorAll('.trust-row');
            rows.forEach(row => {
                const trustLevel = row.getAttribute('data-level');
                const isFrozen = row.getAttribute('data-frozen') === 'true';
                
                if (filter === 'all') {
                    row.style.display = '';
                } else if (filter === 'frozen') {
                    row.style.display = isFrozen ? '' : 'none';
                } else if (filter === 'low') {
                    row.style.display = trustLevel === 'low' ? '' : 'none';
                } else if (filter === 'medium') {
                    row.style.display = trustLevel === 'medium' ? '' : 'none';
                } else if (filter === 'high') {
                    row.style.display = trustLevel === 'high' ? '' : 'none';
                }
            });
        }

        function viewUserTrust(userId) {
            window.location.href = `/admin/trust/${userId}`;
        }

        function adjustTrust(userId) {
            const newScore = prompt('Enter new trust score (0-100):', '100');
            if (newScore && !isNaN(newScore) && newScore >= 0 && newScore <= 100) {
                fetch(`/admin/trust/${userId}/adjust`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ trust_score: parseInt(newScore) })
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }

        function freezeUser(userId) {
            if(confirm('Are you sure you want to freeze this user? They will not be able to send emails.')) {
                fetch(`/admin/trust/${userId}/freeze`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }

        function unfreezeUser(userId) {
            if(confirm('Are you sure you want to unfreeze this user?')) {
                fetch(`/admin/trust/${userId}/unfreeze`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }

        function exportTrustData() {
            window.location.href = '/admin/trust/export';
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('✅ Trust Management Loaded Successfully');
        });
    </script>
</body>
</html>
