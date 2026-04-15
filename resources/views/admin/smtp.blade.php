<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Global SMTP - InfiMal Admin</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
        .neon-glow-purple {
            box-shadow: 0 0 8px rgba(139, 92, 246, 0.5), 0 0 20px rgba(139, 92, 246, 0.3);
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
            background: rgba(139, 92, 246, 0.5);
            border-radius: 3px;
        }
        .health-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .health-excellent { background: linear-gradient(135deg, #10b981 0%, #34d399 100%); color: white; }
        .health-good { background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); color: white; }
        .health-risky { background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); color: white; }
        .health-critical { background: linear-gradient(135deg, #ef4444 0%, #f87171 100%); color: white; }
        .health-disabled { background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%); color: white; }
        .provider-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.1);
        }
        .progress-bar {
            height: 6px;
            border-radius: 3px;
            background: rgba(255, 255, 255, 0.1);
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s ease;
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-white/90 min-h-screen">
    <div class="relative min-h-screen w-full overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-[#0a192f] via-[#020c1b] to-[#020c1b]"></div>
        
        <div class="relative z-10 flex h-full min-h-screen">
            <!-- Sidebar -->
            <nav class="flex-shrink-0 w-64 p-4">
                <div class="flex flex-col h-full gap-4">
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('logo.png') }}" alt="INFIMAL" class="h-12 w-auto">
                </a>
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
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/admin/trust') }}">
                            <span class="material-symbols-outlined">security</span>
                            <p class="text-sm font-medium leading-normal">Trust System</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/admin/emails') }}">
                            <span class="material-symbols-outlined">monitoring</span>
                            <p class="text-sm font-medium leading-normal">Email Logs</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-purple-500/20 text-white" href="{{ url('/admin/smtp') }}">
                            <span class="material-symbols-outlined text-white">dns</span>
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
                                    <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-0 border-none bg-transparent h-full placeholder:text-white/60 px-2 text-base font-normal leading-normal" placeholder="Search SMTPs by host, user, provider..." value="" id="searchInput"/>
                                </div>
                            </label>
                        </div>
                        <div class="flex flex-1 justify-end gap-4 items-center">
                            <button onclick="refreshSMTPStats()" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-green-500 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-green-500/90 transition-colors">
                                <span class="material-symbols-outlined mr-2">refresh</span>
                                <span class="truncate">Refresh</span>
                            </button>
                            <button onclick="addNewSMTP()" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-purple-500 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-purple-500/90 transition-colors">
                                <span class="material-symbols-outlined mr-2">add</span>
                                <span class="truncate">Add SMTP</span>
                            </button>
                            <button onclick="showEmergencyActions()" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-red-500 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-red-500/90 transition-colors">
                                <span class="material-symbols-outlined mr-2">emergency</span>
                                <span class="truncate">Emergency</span>
                            </button>
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? "Admin") }}&color=FFFFFF&background=8B5CF6");'></div>
                        </div>
                    </div>
                </header>

                <!-- Hero Section -->
                <div class="relative mb-8 glass-card rounded-xl p-8 lg:p-12 overflow-hidden soft-shadow neon-glow-purple">
                    <div class="absolute -top-1/4 -left-1/4 w-1/2 h-1/2 bg-purple-500/30 rounded-full filter blur-3xl opacity-50"></div>
                    <div class="absolute -bottom-1/4 -right-1/4 w-1/2 h-1/2 bg-[#8b5cf6]/30 rounded-full filter blur-3xl opacity-50"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <h1 class="text-white tracking-light text-3xl font-bold leading-tight">Global SMTP Control Room</h1>
                            <p class="text-white/70 text-base font-normal leading-normal pt-2">
                                Manage all SMTP servers • Real-time health monitoring • Advanced rotation
                                <span class="badge-admin ml-2">PROTECTED ZONE</span>
                            </p>
                            <div class="flex items-center gap-4 mt-4">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-400 pulse-animation">check_circle</span>
                                    <span class="text-white/70 text-sm">Active: {{ $activeSMTPs }} servers</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-yellow-400">warning</span>
                                    <span class="text-white/70 text-sm">Risky: {{ $riskySMTPs }} servers</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-red-400">block</span>
                                    <span class="text-white/70 text-sm">Disabled: {{ $disabledSMTPs }} servers</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-400">sync</span>
                                    <span class="text-white/70 text-sm">Rotation Score: {{ $avgRotationScore }}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-48 h-48 bg-purple-500/20 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-purple-500 text-6xl">dns</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- REAL SMTP STATISTICS CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-white/80 text-base font-medium">Total SMTPs</p>
                            <span class="material-symbols-outlined text-blue-400">storage</span>
                        </div>
                        <p class="text-white text-4xl font-bold" id="totalSMTPs">{{ number_format($totalSMTPs) }}</p>
                        <div class="progress-bar mt-2">
                            <div class="progress-fill bg-green-500" style="width: {{ ($activeSMTPs / $totalSMTPs) * 100 }}%" id="activeProgress"></div>
                        </div>
                        <p class="text-green-400 text-sm" id="activeSMTPs">{{ $activeSMTPs }} active ({{ number_format(($activeSMTPs / $totalSMTPs) * 100, 1) }}%)</p>
                    </div>
                    
                    <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-white/80 text-base font-medium">Avg. Reputation</p>
                            <span class="material-symbols-outlined {{ $avgReputation > 80 ? 'text-green-400' : ($avgReputation > 60 ? 'text-yellow-400' : 'text-red-400') }}" id="reputationIcon">
                                {{ $avgReputation > 80 ? 'verified' : ($avgReputation > 60 ? 'gpp_maybe' : 'gpp_bad') }}
                            </span>
                        </div>
                        <p class="text-white text-4xl font-bold" id="avgReputation">{{ number_format($avgReputation) }}%</p>
                        <div class="progress-bar mt-2">
                            <div class="progress-fill {{ $avgReputation > 80 ? 'bg-green-500' : ($avgReputation > 60 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $avgReputation }}%" id="reputationProgress"></div>
                        </div>
                        <p class="{{ $avgReputation > 80 ? 'text-green-400' : ($avgReputation > 60 ? 'text-yellow-400' : 'text-red-400') }} text-sm" id="reputationStatus">
                            {{ $avgReputation > 80 ? 'Excellent' : ($avgReputation > 60 ? 'Moderate' : 'Critical') }}
                        </p>
                    </div>
                    
                    <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-white/80 text-base font-medium">Today's Usage</p>
                            <span class="material-symbols-outlined text-yellow-400">send</span>
                        </div>
                        <p class="text-white text-4xl font-bold" id="emailsToday">{{ number_format($emailsToday) }}</p>
                        <div class="progress-bar mt-2">
                            <div class="progress-fill bg-yellow-500" style="width: {{ min(($emailsToday / $totalSMTPs) * 2, 100) }}%" id="usageProgress"></div>
                        </div>
                        <p class="text-yellow-400 text-sm" id="perHour">{{ number_format($emailsPerHour) }}/hour • {{ number_format($avgEmailsPerSMTP) }}/SMTP</p>
                    </div>
                    
                    <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-white/80 text-base font-medium">System Health</p>
                            <span class="material-symbols-outlined {{ $systemHealth > 80 ? 'text-green-400' : ($systemHealth > 60 ? 'text-yellow-400' : 'text-red-400') }}" id="healthIcon">
                                {{ $systemHealth > 80 ? 'health_and_safety' : ($systemHealth > 60 ? 'healing' : 'sick') }}
                            </span>
                        </div>
                        <p class="text-white text-4xl font-bold" id="systemHealth">{{ $systemHealth }}%</p>
                        <div class="progress-bar mt-2">
                            <div class="progress-fill {{ $systemHealth > 80 ? 'bg-green-500' : ($systemHealth > 60 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $systemHealth }}%" id="healthProgress"></div>
                        </div>
                        <p class="{{ $systemHealth > 80 ? 'text-green-400' : ($systemHealth > 60 ? 'text-yellow-400' : 'text-red-400') }} text-sm" id="healthStatus">
                            {{ $systemHealth > 80 ? 'Optimal' : ($systemHealth > 60 ? 'Stable' : 'Attention Needed') }}
                        </p>
                    </div>
                </div>

                <!-- SMTP HEALTH DISTRIBUTION - REAL DATA -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="rounded-xl glass-card soft-shadow p-6">
                        <h3 class="text-white font-bold text-lg mb-4">SMTP Health Distribution</h3>
                        <div class="space-y-4">
                            @php
                                $healthLevels = [
                                    'excellent' => ['name' => 'Excellent (80-100%)', 'color' => 'bg-green-500', 'count' => $healthStats['excellent']],
                                    'good' => ['name' => 'Good (60-79%)', 'color' => 'bg-blue-500', 'count' => $healthStats['good']],
                                    'risky' => ['name' => 'Risky (40-59%)', 'color' => 'bg-yellow-500', 'count' => $healthStats['risky']],
                                    'critical' => ['name' => 'Critical (<40%)', 'color' => 'bg-red-500', 'count' => $healthStats['critical']],
                                    'disabled' => ['name' => 'Disabled', 'color' => 'bg-gray-500', 'count' => $healthStats['disabled']],
                                ];
                                $totalHealth = $totalSMTPs;
                            @endphp
                            
                            @foreach($healthLevels as $level => $data)
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 rounded-full {{ $data['color'] }}"></div>
                                        <span class="text-white/70 text-sm">{{ $data['name'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-white font-bold" id="health{{ ucfirst($level) }}">{{ number_format($data['count']) }}</span>
                                        <span class="text-white/50 text-sm" id="health{{ ucfirst($level) }}Percent">
                                            @if($totalHealth > 0)
                                            {{ number_format(($data['count'] / $totalHealth) * 100, 1) }}%
                                            @else
                                            0%
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full {{ $data['color'] }} rounded-full" id="health{{ ucfirst($level) }}Bar"
                                         style="width: {{ $totalHealth > 0 ? ($data['count'] / $totalHealth) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="rounded-xl glass-card soft-shadow p-6">
                        <h3 class="text-white font-bold text-lg mb-4">Provider Breakdown • Real-time</h3>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($providerStats as $provider => $count)
                            <div class="text-center p-4 rounded-lg bg-white/5">
                                <p class="text-white text-2xl font-bold">{{ $count }}</p>
                                <p class="text-white/70 text-sm">{{ ucfirst($provider) }}</p>
                                <p class="text-white/50 text-xs mt-1">
                                    {{ $totalSMTPs > 0 ? number_format(($count / $totalSMTPs) * 100, 1) : 0 }}%
                                </p>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <p class="text-white/60 text-sm">
                                <span class="material-symbols-outlined text-xs">info</span>
                                Total emails today: <span class="text-white font-bold">{{ number_format($totalEmailsToday) }}</span>
                                • Rotation success: <span class="text-white font-bold">{{ $rotationSuccessRate }}%</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SMTP FAILURE ANALYTICS -->
                <div class="rounded-xl glass-card soft-shadow p-6 mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-white font-bold text-lg">Failure & Risk Signals (Last 24h)</h3>
                        <span class="text-white/50 text-sm">Auto-refresh in <span id="refreshCountdown">30</span>s</span>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div class="text-center p-4 rounded-lg {{ $failureStats['soft_bounces'] > 10 ? 'bg-red-500/20' : 'bg-white/5' }}">
                            <p class="text-white text-2xl font-bold {{ $failureStats['soft_bounces'] > 10 ? 'text-red-400' : '' }}">
                                {{ $failureStats['soft_bounces'] }}
                            </p>
                            <p class="{{ $failureStats['soft_bounces'] > 10 ? 'text-red-400' : 'text-white/70' }} text-sm">Soft Bounces</p>
                            @if($failureStats['soft_bounces'] > 10)
                            <span class="text-red-400 text-xs mt-1">⚠️ High</span>
                            @endif
                        </div>
                        <div class="text-center p-4 rounded-lg {{ $failureStats['hard_bounces'] > 5 ? 'bg-red-500/20' : 'bg-white/5' }}">
                            <p class="text-white text-2xl font-bold {{ $failureStats['hard_bounces'] > 5 ? 'text-red-400' : '' }}">
                                {{ $failureStats['hard_bounces'] }}
                            </p>
                            <p class="{{ $failureStats['hard_bounces'] > 5 ? 'text-red-400' : 'text-white/70' }} text-sm">Hard Bounces</p>
                            @if($failureStats['hard_bounces'] > 5)
                            <span class="text-red-400 text-xs mt-1">🚨 Critical</span>
                            @endif
                        </div>
                        <div class="text-center p-4 rounded-lg {{ $failureStats['spam_complaints'] > 2 ? 'bg-red-500/20' : 'bg-white/5' }}">
                            <p class="text-white text-2xl font-bold {{ $failureStats['spam_complaints'] > 2 ? 'text-red-400' : '' }}">
                                {{ $failureStats['spam_complaints'] }}
                            </p>
                            <p class="{{ $failureStats['spam_complaints'] > 2 ? 'text-red-400' : 'text-white/70' }} text-sm">Spam Complaints</p>
                            @if($failureStats['spam_complaints'] > 2)
                            <span class="text-red-400 text-xs mt-1">⚠️ Risk</span>
                            @endif
                        </div>
                        <div class="text-center p-4 rounded-lg {{ $failureStats['auth_errors'] > 3 ? 'bg-yellow-500/20' : 'bg-white/5' }}">
                            <p class="text-white text-2xl font-bold {{ $failureStats['auth_errors'] > 3 ? 'text-yellow-400' : '' }}">
                                {{ $failureStats['auth_errors'] }}
                            </p>
                            <p class="{{ $failureStats['auth_errors'] > 3 ? 'text-yellow-400' : 'text-white/70' }} text-sm">Auth Errors</p>
                            @if($failureStats['auth_errors'] > 3)
                            <span class="text-yellow-400 text-xs mt-1">⚠️ Unstable</span>
                            @endif
                        </div>
                        <div class="text-center p-4 rounded-lg {{ $failureStats['temp_failures'] > 15 ? 'bg-yellow-500/20' : 'bg-white/5' }}">
                            <p class="text-white text-2xl font-bold {{ $failureStats['temp_failures'] > 15 ? 'text-yellow-400' : '' }}">
                                {{ $failureStats['temp_failures'] }}
                            </p>
                            <p class="{{ $failureStats['temp_failures'] > 15 ? 'text-yellow-400' : 'text-white/70' }} text-sm">Temp Failures</p>
                            @if($failureStats['temp_failures'] > 15)
                            <span class="text-yellow-400 text-xs mt-1">⚠️ High</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <p class="text-white/60 text-sm">
                            Overall bounce rate: <span class="text-white font-bold">{{ $bounceRate }}%</span>
                            • Spam rate: <span class="text-white font-bold">{{ $spamRate }}%</span>
                            • System stability: <span class="{{ $systemStability > 90 ? 'text-green-400' : 'text-yellow-400' }} font-bold">{{ $systemStability }}%</span>
                        </p>
                    </div>
                </div>

                <!-- SMTP MASTER TABLE -->
                <div class="mt-6 rounded-xl glass-card soft-shadow overflow-hidden">
                    <div class="p-6 border-b border-white/10 flex justify-between items-center">
                        <div>
                            <h3 class="text-white font-bold text-lg">All SMTP Servers</h3>
                            <p class="text-white/50 text-sm">Showing {{ $smtps->count() }} of {{ number_format($totalSMTPs) }} SMTPs</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <span class="text-white/50 text-sm">Filter:</span>
                                <select class="bg-transparent border border-white/20 rounded-lg px-3 py-1 text-white text-sm" onchange="filterSMTPs(this.value)" id="statusFilter">
                                    <option value="all">All SMTPs</option>
                                    <option value="active">Active Only</option>
                                    <option value="risky">Risky Only</option>
                                    <option value="critical">Critical Only</option>
                                    <option value="disabled">Disabled</option>
                                    <option value="gmail">Gmail Only</option>
                                    <option value="outlook">Outlook Only</option>
                                    <option value="yahoo">Yahoo Only</option>
                                    <option value="custom">Custom/VPS</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-white/50 text-sm">Sort:</span>
                                <select class="bg-transparent border border-white/20 rounded-lg px-3 py-1 text-white text-sm" onchange="sortSMTPs(this.value)" id="sortFilter">
                                    <option value="reputation">Reputation (High-Low)</option>
                                    <option value="reputation_low">Reputation (Low-High)</option>
                                    <option value="usage">Usage Today</option>
                                    <option value="created">Newest First</option>
                                    <option value="health">Health Score</option>
                                    <option value="rotation">Rotation Priority</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">SMTP Identity</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Provider & Host</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Health & Status</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Usage Today</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Warmup</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Rotation</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Last Used</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="smtpTable">
                                @if($smtps->count() > 0)
                                    @foreach($smtps as $smtp)
                                    @php
                                        $smtpId = $smtp->id;
                                        $userId = $smtp->user_id;
                                        $username = $smtp->username ?? 'N/A';
                                        $maskedUsername = maskEmail($username);
                                        $host = $smtp->host ?? 'smtp.gmail.com';
                                        $port = $smtp->port ?? 587;
                                        $provider = $smtp->provider ?? 'custom';
                                        $isActive = $smtp->is_active ?? false;
                                        $reputation = $smtp->reputation_score ?? 0;
                                        $health = calculateHealthScore($smtp);
                                        $emailsToday = $smtp->emails_today ?? 0;
                                        $emailsThisHour = $smtp->emails_this_hour ?? 0;
                                        $totalEmails = $smtp->total_emails_sent ?? 0;
                                        $hourlyLimit = $smtp->hourly_limit ?? 50;
                                        $warmupStage = $smtp->warmup_stage ?? 'stable';
                                        $rotationScore = $smtp->rotation_score ?? 0;
                                        $lastUsed = $smtp->last_used_at ?? $smtp->created_at;
                                        $lastUsedTime = \Carbon\Carbon::parse($lastUsed);
                                        $softBounces = $smtp->soft_bounces_24h ?? 0;
                                        $hardBounces = $smtp->hard_bounces_24h ?? 0;
                                        $spamComplaints = $smtp->spam_complaints_24h ?? 0;
                                        
                                        // Health badge
                                        if ($health >= 80) {
                                            $healthClass = 'health-excellent';
                                            $healthLabel = 'Excellent';
                                        } elseif ($health >= 60) {
                                            $healthClass = 'health-good';
                                            $healthLabel = 'Good';
                                        } elseif ($health >= 40) {
                                            $healthClass = 'health-risky';
                                            $healthLabel = 'Risky';
                                        } else {
                                            $healthClass = 'health-critical';
                                            $healthLabel = 'Critical';
                                        }
                                        
                                        if (!$isActive) {
                                            $healthClass = 'health-disabled';
                                            $healthLabel = 'Disabled';
                                        }
                                        
                                        // Warmup badge
                                        $warmupClasses = [
                                            'new' => 'bg-blue-500/20 text-blue-400',
                                            'warming' => 'bg-yellow-500/20 text-yellow-400',
                                            'stable' => 'bg-green-500/20 text-green-400',
                                            'paused' => 'bg-gray-500/20 text-gray-400'
                                        ];
                                        $warmupClass = $warmupClasses[$warmupStage] ?? 'bg-gray-500/20 text-gray-400';
                                        
                                        // Provider badge
                                        $providerClasses = [
                                            'gmail' => 'bg-red-500/20 text-red-400',
                                            'outlook' => 'bg-blue-500/20 text-blue-400',
                                            'yahoo' => 'bg-purple-500/20 text-purple-400',
                                            'custom' => 'bg-gray-500/20 text-gray-400'
                                        ];
                                        $providerClass = $providerClasses[$provider] ?? 'bg-gray-500/20 text-gray-400';
                                        
                                        // Usage percentage
                                        $usagePercent = $hourlyLimit > 0 ? min(($emailsThisHour / $hourlyLimit) * 100, 100) : 0;
                                    @endphp
                                    
                                    <tr class="border-b border-white/5 hover:bg-white/5 smtp-row" 
                                        data-status="{{ $isActive ? 'active' : 'disabled' }}"
                                        data-health="{{ $health }}"
                                        data-provider="{{ $provider }}"
                                        data-reputation="{{ $reputation }}">
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-center bg-cover" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(substr($maskedUsername, 0, 2)) }}&color=FFFFFF&background=8B5CF6");'></div>
                                                <div>
                                                    <p class="text-white font-medium text-sm">{{ $maskedUsername }}</p>
                                                    <p class="text-white/50 text-xs">ID: {{ $smtpId }} • User: {{ $userId }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="provider-badge {{ $providerClass }}">
                                                    {{ strtoupper($provider) }}
                                                </span>
                                            </div>
                                            <p class="text-white/70 text-sm truncate max-w-xs">{{ $host }}:{{ $port }}</p>
                                            <p class="text-white/50 text-xs">{{ $username }}</p>
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="health-badge {{ $healthClass }}" id="healthBadge{{ $smtpId }}">
                                                    {{ $healthLabel }}
                                                </span>
                                                @if($isActive)
                                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                                @else
                                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <p class="text-white/70 text-sm">Rep: {{ $reputation }}%</p>
                                                @if($softBounces > 0 || $hardBounces > 0)
                                                <span class="text-red-400 text-xs">
                                                    {{ $softBounces + $hardBounces }} bounce(s)
                                                </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="space-y-1">
                                                <p class="text-white text-sm font-bold">{{ $emailsToday }}</p>
                                                <div class="progress-bar">
                                                    <div class="progress-fill {{ $usagePercent > 80 ? 'bg-red-500' : ($usagePercent > 60 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                                         style="width: {{ $usagePercent }}%"></div>
                                                </div>
                                                <p class="text-white/50 text-xs">
                                                    {{ $emailsThisHour }}/{{ $hourlyLimit }} this hour
                                                    @if($usagePercent > 90)
                                                    <span class="text-red-400">⚠️ High</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6">
                                            <span class="px-2 py-1 rounded text-xs {{ $warmupClass }}">
                                                {{ ucfirst($warmupStage) }}
                                            </span>
                                            <p class="text-white/50 text-xs mt-1">
                                                @if($warmupStage === 'new')
                                                New (0/100)
                                                @elseif($warmupStage === 'warming')
                                                Warming (50/100)
                                                @else
                                                Stable
                                                @endif
                                            </p>
                                        </td>
                                        <td class="py-3 px-6">
                                            <p class="text-white text-sm font-bold">{{ $rotationScore }}%</p>
                                            <p class="text-white/50 text-xs">
                                                @if($rotationScore > 80)
                                                ⭐ Primary
                                                @elseif($rotationScore > 60)
                                                🔄 Secondary
                                                @elseif($rotationScore > 40)
                                                ⏳ Backup
                                                @else
                                                🛑 Low Pri
                                                @endif
                                            </p>
                                            <p class="text-white/50 text-xs mt-1">
                                                Total: {{ number_format($totalEmails) }}
                                            </p>
                                        </td>
                                        <td class="py-3 px-6">
                                            <p class="text-white text-sm">{{ $lastUsedTime->format('M d, H:i') }}</p>
                                            <p class="text-white/50 text-xs">
                                                {{ $lastUsedTime->diffForHumans() }}
                                                @if($lastUsedTime->diffInHours() > 24)
                                                <span class="text-yellow-400"> • Idle</span>
                                                @endif
                                            </p>
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-2">
                                                <button onclick="viewSMTPDetails('{{ $smtpId }}')" class="p-2 hover:bg-white/10 rounded" title="View Details">
                                                    <span class="material-symbols-outlined text-white/70 text-sm">visibility</span>
                                                </button>
                                                <button onclick="toggleSMTPStatus('{{ $smtpId }}', {{ $isActive ? 'false' : 'true' }})" class="p-2 hover:bg-white/10 rounded" title="{{ $isActive ? 'Disable' : 'Enable' }}">
                                                    <span class="material-symbols-outlined {{ $isActive ? 'text-red-400' : 'text-green-400' }} text-sm">
                                                        {{ $isActive ? 'toggle_off' : 'toggle_on' }}
                                                    </span>
                                                </button>
                                                <button onclick="resetReputation('{{ $smtpId }}')" class="p-2 hover:bg-white/10 rounded" title="Reset Reputation">
                                                    <span class="material-symbols-outlined text-blue-400 text-sm">restart_alt</span>
                                                </button>
                                                <div class="relative">
                                                    <button onclick="showSMTPSettings('{{ $smtpId }}')" class="p-2 hover:bg-white/10 rounded" title="More Actions">
                                                        <span class="material-symbols-outlined text-white/70 text-sm">more_vert</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-white/50">
                                        <span class="material-symbols-outlined text-4xl mb-2">dns</span>
                                        <p class="text-lg">No SMTP servers found</p>
                                        <p class="text-sm mt-2">Add your first SMTP server to get started</p>
                                        <button onclick="addNewSMTP()" class="mt-4 px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600">
                                            Add SMTP Server
                                        </button>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    @if($smtps->hasPages())
                    <div class="p-6 border-t border-white/10">
                        <div class="flex items-center justify-between">
                            <p class="text-white/50 text-sm">
                                Showing {{ $smtps->firstItem() }} to {{ $smtps->lastItem() }} of {{ $smtps->total() }} SMTPs
                            </p>
                            <div class="flex items-center gap-2">
                                @if($smtps->onFirstPage())
                                <span class="px-3 py-1 rounded-lg bg-white/5 text-white/30 cursor-not-allowed">Previous</span>
                                @else
                                <a href="{{ $smtps->previousPageUrl() }}" class="px-3 py-1 rounded-lg bg-white/10 text-white hover:bg-white/20">Previous</a>
                                @endif
                                
                                @foreach($smtps->getUrlRange(max(1, $smtps->currentPage() - 2), min($smtps->lastPage(), $smtps->currentPage() + 2)) as $page => $url)
                                <a href="{{ $url }}" class="px-3 py-1 rounded-lg {{ $smtps->currentPage() == $page ? 'bg-purple-500 text-white' : 'bg-white/10 text-white hover:bg-white/20' }}">
                                    {{ $page }}
                                </a>
                                @endforeach
                                
                                @if($smtps->hasMorePages())
                                <a href="{{ $smtps->nextPageUrl() }}" class="px-3 py-1 rounded-lg bg-white/10 text-white hover:bg-white/20">Next</a>
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

    <!-- SMTP Details Modal -->
    <div id="smtpDetailsModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
        <div class="glass-card rounded-xl p-6 w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-white font-bold text-xl">SMTP Server Details</h3>
                <button onclick="hideSMTPDetails()" class="p-2 hover:bg-white/10 rounded">
                    <span class="material-symbols-outlined text-white/70">close</span>
                </button>
            </div>
            <div id="smtpDetailsContent">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Emergency Actions Modal -->
    <div id="emergencyModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
        <div class="glass-card rounded-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-white font-bold text-lg mb-4">⚠️ Emergency Actions</h3>
            <p class="text-white/70 text-sm mb-6">Use these actions with extreme caution. They affect ALL SMTPs.</p>
            <div class="space-y-3">
                <button onclick="emergencyStopAll()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-red-500/20 hover:bg-red-500/30 text-red-400">
                    <span class="material-symbols-outlined">emergency</span>
                    <span>Emergency Stop All SMTPs</span>
                </button>
                <button onclick="resetAllReputations()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400">
                    <span class="material-symbols-outlined">restart_alt</span>
                    <span>Reset All Reputations</span>
                </button>
                <button onclick="clearAllCounters()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-blue-500/20 hover:bg-blue-500/30 text-blue-400">
                    <span class="material-symbols-outlined">cleaning</span>
                    <span>Clear All Counters</span>
                </button>
                <button onclick="pauseAllWarmup()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-purple-500/20 hover:bg-purple-500/30 text-purple-400">
                    <span class="material-symbols-outlined">pause</span>
                    <span>Pause All Warmup</span>
                </button>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button onclick="hideEmergencyActions()" class="px-4 py-2 rounded-lg bg-white/10 text-white hover:bg-white/20">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Add SMTP Modal -->
    <div id="addSmtpModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
        <div class="glass-card rounded-xl p-6 w-full max-w-md">
            <h3 class="text-white font-bold text-lg mb-4">Add New SMTP Server</h3>
            <form id="addSmtpForm" onsubmit="return submitSMTPServer(this)">
                <div class="space-y-4">
                    <div>
                        <label class="text-white/70 text-sm mb-1 block">User ID (Owner)</label>
                        <input type="text" name="user_id" required class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white">
                    </div>
                    <div>
                        <label class="text-white/70 text-sm mb-1 block">SMTP Host</label>
                        <input type="text" name="host" placeholder="smtp.gmail.com" required class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-white/70 text-sm mb-1 block">Port</label>
                            <select name="port" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white">
                                <option value="587">587 (TLS)</option>
                                <option value="465">465 (SSL)</option>
                                <option value="25">25</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-white/70 text-sm mb-1 block">Provider</label>
                            <select name="provider" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white">
                                <option value="gmail">Gmail</option>
                                <option value="outlook">Outlook</option>
                                <option value="yahoo">Yahoo</option>
                                <option value="custom">Custom/VPS</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-white/70 text-sm mb-1 block">Username/Email</label>
                        <input type="email" name="username" required class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white">
                    </div>
                    <div>
                        <label class="text-white/70 text-sm mb-1 block">Password</label>
                        <input type="password" name="password" required class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-white/70 text-sm mb-1 block">Hourly Limit</label>
                            <input type="number" name="hourly_limit" value="50" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white">
                        </div>
                        <div>
                            <label class="text-white/70 text-sm mb-1 block">Warmup Stage</label>
                            <select name="warmup_stage" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white">
                                <option value="new">New</option>
                                <option value="warming">Warming</option>
                                <option value="stable" selected>Stable</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="hideAddSMTP()" class="px-4 py-2 rounded-lg bg-white/10 text-white hover:bg-white/20">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-purple-500 text-white hover:bg-purple-600">Add SMTP</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.smtp-row');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        function filterSMTPs(filter) {
            const rows = document.querySelectorAll('.smtp-row');
            rows.forEach(row => {
                if (filter === 'all') {
                    row.style.display = '';
                } else if (filter === 'active') {
                    const status = row.getAttribute('data-status');
                    row.style.display = status === 'active' ? '' : 'none';
                } else if (filter === 'disabled') {
                    const status = row.getAttribute('data-status');
                    row.style.display = status === 'disabled' ? '' : 'none';
                } else if (filter === 'risky') {
                    const health = parseInt(row.getAttribute('data-health'));
                    row.style.display = (health >= 40 && health < 60) ? '' : 'none';
                } else if (filter === 'critical') {
                    const health = parseInt(row.getAttribute('data-health'));
                    row.style.display = health < 40 ? '' : 'none';
                } else {
                    const provider = row.getAttribute('data-provider');
                    row.style.display = provider === filter ? '' : 'none';
                }
            });
        }

        function sortSMTPs(sortBy) {
            // Reload page with sort parameter
            window.location.href = '{{ url("/admin/smtp") }}?sort=' + sortBy;
        }

        let currentDetailsSmtpId = null;

        function viewSMTPDetails(id) {
            currentDetailsSmtpId = id;
            fetch(`/admin/smtp/${id}/details`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const content = document.getElementById('smtpDetailsContent');
                        content.innerHTML = `
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-white font-bold mb-2">Basic Information</h4>
                                        <div class="bg-white/5 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-white/70">SMTP ID:</span>
                                                <span class="text-white font-mono">${data.smtp.id}</span>
                                            </div>
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-white/70">User ID:</span>
                                                <span class="text-white">${data.smtp.user_id}</span>
                                            </div>
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-white/70">Host:</span>
                                                <span class="text-white">${data.smtp.host}:${data.smtp.port}</span>
                                            </div>
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-white/70">Provider:</span>
                                                <span class="px-2 py-1 rounded text-xs ${data.smtp.provider_class}">
                                                    ${data.smtp.provider.toUpperCase()}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-white/70">Username:</span>
                                                <span class="text-white">${data.smtp.masked_username}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h4 class="text-white font-bold mb-2">Health & Status</h4>
                                        <div class="bg-white/5 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-white/70">Health Score:</span>
                                                <span class="health-badge ${data.smtp.health_class}">${data.smtp.health_label}</span>
                                            </div>
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-white/70">Reputation:</span>
                                                <span class="text-white">${data.smtp.reputation}%</span>
                                            </div>
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-white/70">Status:</span>
                                                <span class="text-${data.smtp.is_active ? 'green' : 'red'}-400">
                                                    ${data.smtp.is_active ? 'Active' : 'Disabled'}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-white/70">Auto-disabled:</span>
                                                <span class="text-${data.smtp.auto_disabled ? 'red' : 'green'}-400">
                                                    ${data.smtp.auto_disabled ? 'Yes' : 'No'}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-white font-bold mb-2">Usage Metrics</h4>
                                        <div class="bg-white/5 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-white/70">Emails Today:</span>
                                                <span class="text-white">${data.smtp.emails_today}</span>
                                            </div>
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-white/70">This Hour:</span>
                                                <span class="text-white">${data.smtp.emails_this_hour}/${data.smtp.hourly_limit}</span>
                                            </div>
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-white/70">Total Emails:</span>
                                                <span class="text-white">${data.smtp.total_emails_sent}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-white/70">Last Used:</span>
                                                <span class="text-white">${data.smtp.last_used_formatted}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h4 class="text-white font-bold mb-2">Warmup & Rotation</h4>
                                        <div class="bg-white/5 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-white/70">Warmup Stage:</span>
                                                <span class="px-2 py-1 rounded text-xs ${data.smtp.warmup_class}">
                                                    ${data.smtp.warmup_stage.toUpperCase()}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-white/70">Rotation Priority:</span>
                                                <span class="text-white">${data.smtp.rotation_score}%</span>
                                            </div>
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-white/70">Last Skipped Reason:</span>
                                                <span class="text-white/70">${data.smtp.last_skipped_reason || 'None'}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-white/70">Next Eligible:</span>
                                                <span class="text-${data.smtp.next_eligible ? 'green' : 'red'}-400">
                                                    ${data.smtp.next_eligible ? 'Yes' : 'No'}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <h4 class="text-white font-bold mb-2">Failure Signals (Last 24h)</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="text-center p-3 rounded-lg ${data.smtp.soft_bounces > 5 ? 'bg-red-500/20' : 'bg-white/5'}">
                                        <p class="text-white text-lg font-bold">${data.smtp.soft_bounces}</p>
                                        <p class="text-white/70 text-sm">Soft Bounces</p>
                                    </div>
                                    <div class="text-center p-3 rounded-lg ${data.smtp.hard_bounces > 2 ? 'bg-red-500/20' : 'bg-white/5'}">
                                        <p class="text-white text-lg font-bold">${data.smtp.hard_bounces}</p>
                                        <p class="text-white/70 text-sm">Hard Bounces</p>
                                    </div>
                                    <div class="text-center p-3 rounded-lg ${data.smtp.spam_complaints > 0 ? 'bg-red-500/20' : 'bg-white/5'}">
                                        <p class="text-white text-lg font-bold">${data.smtp.spam_complaints}</p>
                                        <p class="text-white/70 text-sm">Spam Complaints</p>
                                    </div>
                                    <div class="text-center p-3 rounded-lg ${data.smtp.auth_errors > 2 ? 'bg-yellow-500/20' : 'bg-white/5'}">
                                        <p class="text-white text-lg font-bold">${data.smtp.auth_errors}</p>
                                        <p class="text-white/70 text-sm">Auth Errors</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <h4 class="text-white font-bold mb-2">Auto-Actions Log</h4>
                                <div class="bg-white/5 rounded-lg p-4 max-h-48 overflow-y-auto">
                                    ${data.logs.length > 0 ? 
                                        data.logs.map(log => `
                                            <div class="flex items-center justify-between py-2 border-b border-white/10 last:border-0">
                                                <div>
                                                    <p class="text-white text-sm">${log.action}</p>
                                                    <p class="text-white/50 text-xs">${log.reason}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-white/70 text-xs">${log.created_at_formatted}</p>
                                                    <p class="text-white/50 text-xs">${log.triggered_by}</p>
                                                </div>
                                            </div>
                                        `).join('') :
                                        '<p class="text-white/50 text-center py-4">No auto-actions recorded</p>'
                                    }
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-end gap-3">
                                <button onclick="toggleSMTPStatus('${data.smtp.id}', ${!data.smtp.is_active})" class="px-4 py-2 rounded-lg ${data.smtp.is_active ? 'bg-red-500/20 text-red-400' : 'bg-green-500/20 text-green-400'} hover:opacity-80">
                                    ${data.smtp.is_active ? 'Disable SMTP' : 'Enable SMTP'}
                                </button>
                                <button onclick="resetReputation('${data.smtp.id}')" class="px-4 py-2 rounded-lg bg-blue-500/20 text-blue-400 hover:opacity-80">
                                    Reset Reputation
                                </button>
                                <button onclick="resetWarmup('${data.smtp.id}')" class="px-4 py-2 rounded-lg bg-yellow-500/20 text-yellow-400 hover:opacity-80">
                                    Reset Warmup
                                </button>
                            </div>
                        `;
                        document.getElementById('smtpDetailsModal').classList.remove('hidden');
                        document.getElementById('smtpDetailsModal').classList.add('flex');
                    } else {
                        showToast(data.message || 'Failed to load SMTP details', 'error');
                    }
                });
        }

        function hideSMTPDetails() {
            document.getElementById('smtpDetailsModal').classList.add('hidden');
            document.getElementById('smtpDetailsModal').classList.remove('flex');
        }

        function toggleSMTPStatus(id, enable) {
            const action = enable ? 'enable' : 'disable';
            if(confirm(`${enable ? 'Enable' : 'Disable'} this SMTP server?`)) {
                fetch(`/admin/smtp/${id}/${action}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        refreshSMTPStats();
                        hideSMTPDetails();
                    } else {
                        showToast(data.message || 'Failed to update status', 'error');
                    }
                });
            }
        }

        function resetReputation(id) {
            if(confirm('Reset reputation to 100%? This cannot be undone.')) {
                fetch(`/admin/smtp/${id}/reset-reputation`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        refreshSMTPStats();
                        if (currentDetailsSmtpId === id) {
                            viewSMTPDetails(id);
                        }
                    } else {
                        showToast(data.message || 'Failed to reset reputation', 'error');
                    }
                });
            }
        }

        function resetWarmup(id) {
            if(confirm('Reset warmup stage to "new"? This will restart the warmup process.')) {
                fetch(`/admin/smtp/${id}/reset-warmup`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        if (currentDetailsSmtpId === id) {
                            viewSMTPDetails(id);
                        }
                    } else {
                        showToast(data.message || 'Failed to reset warmup', 'error');
                    }
                });
            }
        }

        function showSMTPSettings(id) {
            // Show context menu or quick actions
            const actions = [
                { label: 'Clear Daily Counters', action: () => clearDailyCounters(id) },
                { label: 'Pause Warmup', action: () => pauseWarmup(id) },
                { label: 'Force Test Connection', action: () => testConnection(id) },
                { label: 'View Raw Logs', action: () => viewRawLogs(id) },
                { label: 'Delete SMTP', action: () => deleteSMTP(id) }
            ];
            
            // Create a context menu
            const menu = document.createElement('div');
            menu.className = 'absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-lg z-10';
            menu.innerHTML = actions.map(action => `
                <button onclick="${action.action}" class="w-full text-left px-4 py-2 text-sm text-white/70 hover:bg-white/10">
                    ${action.label}
                </button>
            `).join('');
            
            // Add to document and position
            document.body.appendChild(menu);
            // Position logic here (simplified)
            
            // Remove on click outside
            setTimeout(() => {
                document.addEventListener('click', function closeMenu() {
                    menu.remove();
                    document.removeEventListener('click', closeMenu);
                });
            }, 100);
        }

        function clearDailyCounters(id) {
            fetch(`/admin/smtp/${id}/clear-counters`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Counters cleared', 'success');
                    refreshSMTPStats();
                }
            });
        }

        function pauseWarmup(id) {
            fetch(`/admin/smtp/${id}/pause-warmup`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Warmup paused', 'success');
                    if (currentDetailsSmtpId === id) {
                        viewSMTPDetails(id);
                    }
                }
            });
        }

        function testConnection(id) {
            fetch(`/admin/smtp/${id}/test-connection`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Connection test successful', 'success');
                } else {
                    showToast('Connection test failed: ' + data.message, 'error');
                }
            });
        }

        function deleteSMTP(id) {
            if(confirm('⚠️ Delete this SMTP server? This will permanently remove it from the system.')) {
                fetch(`/admin/smtp/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('SMTP deleted', 'success');
                        refreshSMTPStats();
                    } else {
                        showToast('Failed to delete: ' + data.message, 'error');
                    }
                });
            }
        }

        function refreshSMTPStats() {
            // Show loading
            document.querySelectorAll('#totalSMTPs, #avgReputation, #emailsToday, #systemHealth').forEach(el => {
                el.innerHTML = '<span class="animate-pulse">---</span>';
            });
            
            // Fetch updated stats
            fetch('/admin/smtp/stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update all stats
                        updateStat('totalSMTPs', data.totalSMTPs);
                        updateStat('activeSMTPs', data.activeSMTPs + ' active (' + data.activePercent + '%)');
                        updateStat('avgReputation', data.avgReputation + '%');
                        updateStat('emailsToday', data.emailsToday);
                        updateStat('systemHealth', data.systemHealth + '%');
                        updateStat('perHour', data.emailsPerHour + '/hour • ' + data.avgEmailsPerSMTP + '/SMTP');
                        
                        // Update progress bars
                        updateProgressBar('activeProgress', data.activePercent);
                        updateProgressBar('reputationProgress', data.avgReputation);
                        updateProgressBar('usageProgress', data.usagePercent);
                        updateProgressBar('healthProgress', data.systemHealth);
                        
                        // Update reputation icon and status
                        const reputationIcon = document.getElementById('reputationIcon');
                        const reputationStatus = document.getElementById('reputationStatus');
                        if (data.avgReputation > 80) {
                            reputationIcon.className = 'material-symbols-outlined text-green-400';
                            reputationIcon.textContent = 'verified';
                            reputationStatus.className = 'text-green-400 text-sm';
                            reputationStatus.textContent = 'Excellent';
                        } else if (data.avgReputation > 60) {
                            reputationIcon.className = 'material-symbols-outlined text-yellow-400';
                            reputationIcon.textContent = 'gpp_maybe';
                            reputationStatus.className = 'text-yellow-400 text-sm';
                            reputationStatus.textContent = 'Moderate';
                        } else {
                            reputationIcon.className = 'material-symbols-outlined text-red-400';
                            reputationIcon.textContent = 'gpp_bad';
                            reputationStatus.className = 'text-red-400 text-sm';
                            reputationStatus.textContent = 'Critical';
                        }
                        
                        // Update health icon and status
                        const healthIcon = document.getElementById('healthIcon');
                        const healthStatus = document.getElementById('healthStatus');
                        if (data.systemHealth > 80) {
                            healthIcon.className = 'material-symbols-outlined text-green-400';
                            healthIcon.textContent = 'health_and_safety';
                            healthStatus.className = 'text-green-400 text-sm';
                            healthStatus.textContent = 'Optimal';
                        } else if (data.systemHealth > 60) {
                            healthIcon.className = 'material-symbols-outlined text-yellow-400';
                            healthIcon.textContent = 'healing';
                            healthStatus.className = 'text-yellow-400 text-sm';
                            healthStatus.textContent = 'Stable';
                        } else {
                            healthIcon.className = 'material-symbols-outlined text-red-400';
                            healthIcon.textContent = 'sick';
                            healthStatus.className = 'text-red-400 text-sm';
                            healthStatus.textContent = 'Attention Needed';
                        }
                        
                        // Update health distribution
                        updateHealthDistribution(data.healthStats, data.totalSMTPs);
                        
                        showToast('SMTP stats updated!', 'success');
                    } else {
                        showToast('Failed to refresh stats', 'error');
                    }
                });
        }

        function updateStat(elementId, value) {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = value;
            }
        }

        function updateProgressBar(barId, percent) {
            const bar = document.getElementById(barId);
            if (bar) {
                bar.style.width = percent + '%';
            }
        }

        function updateHealthDistribution(stats, total) {
            const levels = ['excellent', 'good', 'risky', 'critical', 'disabled'];
            levels.forEach(level => {
                const countElement = document.getElementById('health' + level.charAt(0).toUpperCase() + level.slice(1));
                const percentElement = document.getElementById('health' + level.charAt(0).toUpperCase() + level.slice(1) + 'Percent');
                const barElement = document.getElementById('health' + level.charAt(0).toUpperCase() + level.slice(1) + 'Bar');
                
                if (countElement && percentElement && barElement) {
                    const count = stats[level] || 0;
                    const percent = total > 0 ? (count / total) * 100 : 0;
                    
                    countElement.textContent = count;
                    percentElement.textContent = percent.toFixed(1) + '%';
                    barElement.style.width = percent + '%';
                }
            });
        }

        function showEmergencyActions() {
            document.getElementById('emergencyModal').classList.remove('hidden');
            document.getElementById('emergencyModal').classList.add('flex');
        }

        function hideEmergencyActions() {
            document.getElementById('emergencyModal').classList.add('hidden');
            document.getElementById('emergencyModal').classList.remove('flex');
        }

        function emergencyStopAll() {
            if(confirm('⚠️ EMERGENCY STOP ALL SMTPs?\n\nThis will disable ALL SMTP servers immediately. Only use if system is at risk.')) {
                fetch('/admin/smtp/emergency-stop-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('All SMTPs disabled', 'success');
                        refreshSMTPStats();
                    }
                    hideEmergencyActions();
                });
            }
        }

        function resetAllReputations() {
            if(confirm('Reset ALL reputations to 100%?\n\nThis affects all SMTP servers.')) {
                fetch('/admin/smtp/reset-all-reputations', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('All reputations reset', 'success');
                        refreshSMTPStats();
                    }
                    hideEmergencyActions();
                });
            }
        }

        function clearAllCounters() {
            if(confirm('Clear ALL daily/hourly counters?\n\nThis will reset usage tracking for all SMTPs.')) {
                fetch('/admin/smtp/clear-all-counters', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('All counters cleared', 'success');
                        refreshSMTPStats();
                    }
                    hideEmergencyActions();
                });
            }
        }

        function pauseAllWarmup() {
            if(confirm('Pause warmup for ALL SMTPs?\n\nThis will stop all warmup processes.')) {
                fetch('/admin/smtp/pause-all-warmup', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('All warmups paused', 'success');
                        refreshSMTPStats();
                    }
                    hideEmergencyActions();
                });
            }
        }

        function addNewSMTP() {
            document.getElementById('addSmtpModal').classList.remove('hidden');
            document.getElementById('addSmtpModal').classList.add('flex');
        }

        function hideAddSMTP() {
            document.getElementById('addSmtpModal').classList.add('hidden');
            document.getElementById('addSmtpModal').classList.remove('flex');
        }

        function submitSMTPServer(form) {
            const formData = new FormData(form);
            fetch('/admin/smtp', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('SMTP added successfully!', 'success');
                    hideAddSMTP();
                    refreshSMTPStats();
                    form.reset();
                } else {
                    showToast('Failed to add SMTP: ' + data.message, 'error');
                }
            });
            return false;
        }

        function showToast(message, type = 'info') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            } text-white`;
            toast.innerHTML = `
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined">
                        ${type === 'success' ? 'check_circle' : 
                          type === 'error' ? 'error' : 'info'}
                    </span>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Remove toast after 3 seconds
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Auto-refresh countdown
        let countdown = 30;
        const countdownElement = document.getElementById('refreshCountdown');
        
        setInterval(() => {
            countdown--;
            if (countdown <= 0) {
                countdown = 30;
                refreshSMTPStats();
            }
            if (countdownElement) {
                countdownElement.textContent = countdown;
            }
        }, 1000);

        // Auto-refresh stats every 30 seconds
        setInterval(refreshSMTPStats, 30000);

        document.addEventListener('DOMContentLoaded', function() {
            console.log('🌐 Global SMTP Control Room Loaded Successfully');
            console.log('Total SMTPs: {{ $totalSMTPs }}');
            console.log('Avg Reputation: {{ $avgReputation }}%');
            console.log('Real-time monitoring enabled');
            
            // Set up initial filters
            const urlParams = new URLSearchParams(window.location.search);
            const statusFilter = urlParams.get('status') || 'all';
            const sortFilter = urlParams.get('sort') || 'reputation';
            
            if (document.getElementById('statusFilter')) {
                document.getElementById('statusFilter').value = statusFilter;
                filterSMTPs(statusFilter);
            }
            if (document.getElementById('sortFilter')) {
                document.getElementById('sortFilter').value = sortFilter;
            }
        });
    </script>
</body>
</html>

<!-- BACKEND FUNCTIONS (Add to your Controller) -->
<?php
/*
// Helper functions for SMTP data
function maskEmail($email) {
    if (!str_contains($email, '@')) return $email;
    [$local, $domain] = explode('@', $email);
    if (strlen($local) <= 2) return '**@' . $domain;
    return substr($local, 0, 2) . '****' . substr($local, -1) . '@' . $domain;
}

function calculateHealthScore($smtp) {
    $score = 0;
    
    // Reputation contributes 40%
    $score += ($smtp->reputation_score ?? 0) * 0.4;
    
    // Recent failures reduce score
    $failures = ($smtp->soft_bounces_24h ?? 0) + ($smtp->hard_bounces_24h ?? 0) * 2;
    $score -= min($failures * 5, 30);
    
    // Active status adds 20
    if ($smtp->is_active) $score += 20;
    
    // Recent usage bonus (used in last 24h)
    if ($smtp->last_used_at && now()->diffInHours($smtp->last_used_at) < 24) {
        $score += 10;
    }
    
    // Warmup stage bonus
    if (($smtp->warmup_stage ?? 'new') === 'stable') {
        $score += 10;
    }
    
    return max(0, min(100, $score));
}

// Route for real-time stats
Route::get('/admin/smtp/stats', function() {
    try {
        $totalSMTPs = DB::table('smtp_servers')->count();
        $activeSMTPs = DB::table('smtp_servers')->where('is_active', true)->count();
        $disabledSMTPs = DB::table('smtp_servers')->where('is_active', false)->count();
        
        // Calculate risky SMTPs (health < 60)
        $allSMTPs = DB::table('smtp_servers')->get();
        $riskySMTPs = 0;
        $healthStats = ['excellent' => 0, 'good' => 0, 'risky' => 0, 'critical' => 0, 'disabled' => 0];
        
        foreach ($allSMTPs as $smtp) {
            $health = calculateHealthScore($smtp);
            if (!$smtp->is_active) {
                $healthStats['disabled']++;
            } elseif ($health >= 80) {
                $healthStats['excellent']++;
            } elseif ($health >= 60) {
                $healthStats['good']++;
            } elseif ($health >= 40) {
                $healthStats['risky']++;
                $riskySMTPs++;
            } else {
                $healthStats['critical']++;
                $riskySMTPs++;
            }
        }
        
        $avgReputation = DB::table('smtp_servers')->where('is_active', true)->avg('reputation_score') ?? 0;
        $avgReputation = round($avgReputation, 1);
        
        $emailsToday = DB::table('email_logs')->whereDate('created_at', today())->count();
        $emailsPerHour = DB::table('email_logs')->where('created_at', '>=', now()->subHour())->count();
        
        $totalEmailsToday = $emailsToday;
        $avgEmailsPerSMTP = $totalSMTPs > 0 ? round($emailsToday / $totalSMTPs, 1) : 0;
        
        // Provider stats
        $providerStats = [
            'gmail' => DB::table('smtp_servers')->where('provider', 'gmail')->count(),
            'outlook' => DB::table('smtp_servers')->where('provider', 'outlook')->count(),
            'yahoo' => DB::table('smtp_servers')->where('provider', 'yahoo')->count(),
            'custom' => DB::table('smtp_servers')->where('provider', 'custom')->count()
        ];
        
        // Failure stats
        $failureStats = [
            'soft_bounces' => DB::table('email_logs')->where('status', 'bounced')->whereDate('created_at', today())->count(),
            'hard_bounces' => 0, // You'll need to track this separately
            'spam_complaints' => 0, // You'll need to track this
            'auth_errors' => DB::table('email_logs')->where('error_message', 'like', '%auth%')->whereDate('created_at', today())->count(),
            'temp_failures' => DB::table('email_logs')->where('status', 'failed')->whereDate('created_at', today())->count()
        ];
        
        $bounceRate = $emailsToday > 0 ? round(($failureStats['soft_bounces'] / $emailsToday) * 100, 1) : 0;
        $spamRate = 0; // You'll need to calculate this
        
        // System health calculation
        $systemHealth = 100;
        if ($bounceRate > 5) $systemHealth -= 20;
        if ($avgReputation < 70) $systemHealth -= 15;
        if ($riskySMTPs / $totalSMTPs > 0.3) $systemHealth -= 15;
        $systemHealth = max(30, $systemHealth);
        
        // Rotation success rate
        $rotationSuccessRate = 95; // You'll need to calculate this from logs
        
        return response()->json([
            'success' => true,
            'totalSMTPs' => $totalSMTPs,
            'activeSMTPs' => $activeSMTPs,
            'disabledSMTPs' => $disabledSMTPs,
            'riskySMTPs' => $riskySMTPs,
            'activePercent' => $totalSMTPs > 0 ? round(($activeSMTPs / $totalSMTPs) * 100, 1) : 0,
            'avgReputation' => $avgReputation,
            'emailsToday' => $emailsToday,
            'emailsPerHour' => $emailsPerHour,
            'avgEmailsPerSMTP' => $avgEmailsPerSMTP,
            'usagePercent' => min(($emailsPerHour / 1000) * 100, 100), // Assuming 1000/hour capacity
            'systemHealth' => $systemHealth,
            'healthStats' => $healthStats,
            'providerStats' => $providerStats,
            'failureStats' => $failureStats,
            'bounceRate' => $bounceRate,
            'spamRate' => $spamRate,
            'rotationSuccessRate' => $rotationSuccessRate,
            'totalEmailsToday' => $totalEmailsToday,
            'systemStability' => 100 - ($failureStats['temp_failures'] / max($emailsToday, 1) * 100),
            'timestamp' => now()->toISOString()
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching SMTP stats',
            'error' => $e->getMessage()
        ], 500);
    }
});

// Other required routes:
Route::get('/admin/smtp/{id}/details', 'SMTPController@showDetails');
Route::post('/admin/smtp/{id}/enable', 'SMTPController@enable');
Route::post('/admin/smtp/{id}/disable', 'SMTPController@disable');
Route::post('/admin/smtp/{id}/reset-reputation', 'SMTPController@resetReputation');
Route::post('/admin/smtp/{id}/reset-warmup', 'SMTPController@resetWarmup');
Route::post('/admin/smtp/{id}/clear-counters', 'SMTPController@clearCounters');
Route::post('/admin/smtp/{id}/pause-warmup', 'SMTPController@pauseWarmup');
Route::post('/admin/smtp/{id}/test-connection', 'SMTPController@testConnection');
Route::delete('/admin/smtp/{id}', 'SMTPController@destroy');

// Emergency routes
Route::post('/admin/smtp/emergency-stop-all', 'SMTPController@emergencyStopAll');
Route::post('/admin/smtp/reset-all-reputations', 'SMTPController@resetAllReputations');
Route::post('/admin/smtp/clear-all-counters', 'SMTPController@clearAllCounters');
Route::post('/admin/smtp/pause-all-warmup', 'SMTPController@pauseAllWarmup');

// Add SMTP route
Route::post('/admin/smtp', 'SMTPController@store');
*/
?>
