<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>SMTP Settings - InfiMal</title>
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
            <!-- SideNavBar -->
            <nav class="flex-shrink-0 w-64 p-4">
                <div class="flex flex-col h-full gap-4">
                    <div class="flex items-center gap-3 p-2">
                        <div class="p-2 rounded-full bg-blue-500/20 text-blue-500">
                            <span class="material-symbols-outlined">all_inbox</span>
                        </div>
                        <div class="flex flex-col">
                            <h1 class="text-white text-base font-bold leading-normal">InfiMal</h1>
                            <p class="text-white/60 text-sm font-normal leading-normal">Email Management</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-2 mt-4">
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/dashboard') }}">
                            <span class="material-symbols-outlined">dashboard</span>
                            <p class="text-sm font-medium leading-normal">Dashboard</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/subscribers') }}">
                            <span class="material-symbols-outlined">group</span>
                            <p class="text-sm font-medium leading-normal">Subscribers</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/lists') }}">
                            <span class="material-symbols-outlined">list_alt</span>
                            <p class="text-sm font-medium leading-normal">Lists</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/campaigns') }}">
                            <span class="material-symbols-outlined">campaign</span>
                            <p class="text-sm font-medium leading-normal">Campaigns</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/messages') }}">
                            <span class="material-symbols-outlined">chat</span>
                            <p class="text-sm font-medium leading-normal">Messages</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-blue-500/20 text-white" href="{{ url('/smtp') }}">
                            <span class="material-symbols-outlined text-white">dns</span>
                            <p class="text-sm font-medium leading-normal">SMTP Settings</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/billing') }}">
                            <span class="material-symbols-outlined">receipt_long</span>
                            <p class="text-sm font-medium leading-normal">Billing</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/profile') }}">
                            <span class="material-symbols-outlined">person</span>
                            <p class="text-sm font-medium leading-normal">Profile</p>
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
                                    <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-0 border-none bg-transparent h-full placeholder:text-white/60 px-2 text-base font-normal leading-normal" placeholder="Search SMTP configurations..." value=""/>
                                </div>
                            </label>
                        </div>
                        <div class="flex flex-1 justify-end gap-4 items-center">
                            <button onclick="window.location.href='#add-smtp-modal'" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-blue-500 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-blue-500/90 transition-colors">
                                <span class="truncate">Add SMTP</span>
                            </button>
                            <button onclick="testAllSmtp()" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-green-500 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-green-500/90 transition-colors">
                                <span class="truncate">Test All</span>
                            </button>
                            <button class="flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-white/10 text-white/80 gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5 hover:bg-white/20 transition-colors">
                                <span class="material-symbols-outlined">notifications</span>
                            </button>
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=FFFFFF&background=3B82F6");'></div>
                        </div>
                    </div>
                </header>

                <!-- Hero Section -->
                <div class="relative my-8 glass-card rounded-xl p-8 lg:p-12 overflow-hidden soft-shadow">
                    <div class="absolute -top-1/4 -left-1/4 w-1/2 h-1/2 bg-blue-500/30 rounded-full filter blur-3xl opacity-50"></div>
                    <div class="absolute -bottom-1/4 -right-1/4 w-1/2 h-1/2 bg-[#a855f7]/30 rounded-full filter blur-3xl opacity-50"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <h1 class="text-white tracking-light text-3xl font-bold leading-tight">SMTP Settings</h1>
                            <p class="text-white/70 text-base font-normal leading-normal pt-2">Configure and manage your email delivery servers</p>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-48 h-48 bg-blue-500/20 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-blue-500 text-6xl">dns</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- REAL STATISTICS SECTION -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total SMTP Configurations -->
                    <div class="glass-card rounded-xl p-6 soft-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-base font-medium leading-normal">SMTP Configurations</p>
                                <p class="text-white tracking-light text-3xl font-bold leading-tight">{{ $totalSmtp }}</p>
                                <p class="text-green-400 text-sm font-medium leading-normal">{{ $activeSmtp }} active • Status: {{ $smtpStatus }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-blue-500/20">
                                <span class="material-symbols-outlined text-blue-500">dns</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Emails Sent Today -->
                    <div class="glass-card rounded-xl p-6 soft-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-base font-medium leading-normal">Sent Today</p>
                                <p class="text-white tracking-light text-3xl font-bold leading-tight">{{ number_format($usageStats['sent_today']) }}</p>
                                <p class="text-blue-400 text-sm font-medium leading-normal">Real-time count</p>
                            </div>
                            <div class="p-3 rounded-full bg-green-500/20">
                                <span class="material-symbols-outlined text-green-500">send</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- This Month -->
                    <div class="glass-card rounded-xl p-6 soft-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-base font-medium leading-normal">This Month</p>
                                <p class="text-white tracking-light text-3xl font-bold leading-tight">{{ number_format($usageStats['sent_this_month']) }}</p>
                                <p class="text-purple-400 text-sm font-medium leading-normal">Monthly usage</p>
                            </div>
                            <div class="p-3 rounded-full bg-purple-500/20">
                                <span class="material-symbols-outlined text-purple-500">calendar_month</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Success Rate -->
                    <div class="glass-card rounded-xl p-6 soft-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-base font-medium leading-normal">Success Rate</p>
                                <p class="text-white tracking-light text-3xl font-bold leading-tight">{{ $usageStats['success_rate'] }}%</p>
                                <p class="text-emerald-400 text-sm font-medium leading-normal">Delivery success</p>
                            </div>
                            <div class="p-3 rounded-full bg-emerald-500/20">
                                <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SMTP Configurations Table -->
                <div class="glass-card rounded-xl overflow-hidden soft-shadow mb-8">
                    <div class="p-6 border-b border-white/10 flex items-center justify-between">
                        <div>
                            <h2 class="text-white font-bold text-xl">SMTP Configurations</h2>
                            <p class="text-white/70 text-sm mt-1">Manage your email delivery servers</p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <button onclick="showUsageReport()" class="flex items-center gap-2 cursor-pointer overflow-hidden rounded-lg h-10 px-4 bg-white/10 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-white/20 transition-colors">
                                <span class="material-symbols-outlined text-sm">analytics</span>
                                <span>Usage Report</span>
                            </button>
                            <button onclick="resetDailyCounters()" class="flex items-center gap-2 cursor-pointer overflow-hidden rounded-lg h-10 px-4 bg-white/10 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-white/20 transition-colors">
                                <span class="material-symbols-outlined text-sm">refresh</span>
                                <span>Reset Counters</span>
                            </button>
                        </div>
                    </div>
                    
                    @if($smtpSettings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left p-4 text-white/80 font-medium">Status</th>
                                    <th class="text-left p-4 text-white/80 font-medium">SMTP Name</th>
                                    <th class="text-left p-4 text-white/80 font-medium">Server</th>
                                    <th class="text-left p-4 text-white/80 font-medium">From Address</th>
                                    <th class="text-left p-4 text-white/80 font-medium">Daily Usage</th>
                                    <th class="text-left p-4 text-white/80 font-medium">Total Sent</th>
                                    <th class="text-left p-4 text-white/80 font-medium">Last Used</th>
                                    <th class="text-left p-4 text-white/80 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($smtpSettings as $smtp)
                                <tr class="border-b border-white/5 hover:bg-white/5">
                                    <td class="p-4">
                                        @if($smtp->is_active)
                                        <span class="px-3 py-1 rounded-full text-xs bg-green-500/20 text-green-400">Active</span>
                                        @else
                                        <span class="px-3 py-1 rounded-full text-xs bg-red-500/20 text-red-400">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg {{ $smtp->is_active ? 'bg-green-500/20' : 'bg-red-500/20' }} flex items-center justify-center">
                                                <span class="material-symbols-outlined {{ $smtp->is_active ? 'text-green-500' : 'text-red-500' }}">dns</span>
                                            </div>
                                            <div>
                                                <p class="text-white font-medium">{{ $smtp->name }}</p>
                                                <p class="text-white/60 text-sm">{{ $smtp->host }}:{{ $smtp->port }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-white font-medium">{{ $smtp->host }}</p>
                                        <p class="text-white/60 text-sm">Port: {{ $smtp->port }} ({{ strtoupper($smtp->encryption) }})</p>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-white font-medium">{{ $smtp->from_address }}</p>
                                        <p class="text-white/60 text-sm">{{ $smtp->from_name }}</p>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex flex-col">
                                            <p class="text-white font-medium">{{ $smtp->sent_today }}/{{ $smtp->daily_limit }}</p>
                                            <div class="w-24 h-2 bg-gray-700 rounded-full overflow-hidden mt-1">
                                                @php
                                                    $usagePercentage = $smtp->daily_limit > 0 ? ($smtp->sent_today / $smtp->daily_limit) * 100 : 0;
                                                @endphp
                                                <div class="h-full {{ $usagePercentage > 80 ? 'bg-red-500' : ($usagePercentage > 50 ? 'bg-yellow-500' : 'bg-green-500') }} rounded-full" style="width: {{ min($usagePercentage, 100) }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-white font-medium">{{ number_format($smtp->total_sent) }}</p>
                                        <p class="text-white/60 text-sm">All time</p>
                                    </td>
                                    <td class="p-4">
                                        @if($smtp->last_used_at)
                                        <p class="text-white/70 text-sm">{{ \Carbon\Carbon::parse($smtp->last_used_at)->format('M d, Y') }}</p>
                                        <p class="text-white/50 text-xs">{{ \Carbon\Carbon::parse($smtp->last_used_at)->format('h:i A') }}</p>
                                        @else
                                        <p class="text-white/50 text-sm">Never used</p>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            <button onclick="testSmtp({{ $smtp->id }})" class="p-2 rounded-lg hover:bg-white/10" title="Test Connection">
                                                <span class="material-symbols-outlined text-blue-500">play_arrow</span>
                                            </button>
                                            <button onclick="toggleSmtpStatus({{ $smtp->id }}, {{ $smtp->is_active ? 'true' : 'false' }})" class="p-2 rounded-lg hover:bg-white/10" title="{{ $smtp->is_active ? 'Deactivate' : 'Activate' }}">
                                                <span class="material-symbols-outlined {{ $smtp->is_active ? 'text-yellow-500' : 'text-green-500' }}">{{ $smtp->is_active ? 'toggle_off' : 'toggle_on' }}</span>
                                            </button>
                                            <button onclick="editSmtp({{ $smtp->id }})" class="p-2 rounded-lg hover:bg-white/10" title="Edit">
                                                <span class="material-symbols-outlined text-emerald-500">edit</span>
                                            </button>
                                            <button onclick="deleteSmtp({{ $smtp->id }})" class="p-2 rounded-lg hover:bg-white/10" title="Delete">
                                                <span class="material-symbols-outlined text-red-500">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <!-- Empty State -->
                    <div class="p-12 text-center">
                        <div class="w-20 h-20 rounded-full bg-blue-500/20 flex items-center justify-center mx-auto mb-6">
                            <span class="material-symbols-outlined text-blue-500 text-4xl">dns</span>
                        </div>
                        <h3 class="text-white text-xl font-bold mb-3">No SMTP Configurations</h3>
                        <p class="text-white/70 mb-8">Add your first SMTP server to start sending emails</p>
                        <button onclick="window.location.href='#add-smtp-modal'" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-500 text-white rounded-lg font-bold hover:bg-blue-600 transition-colors">
                            <span class="material-symbols-outlined">add</span>
                            Add SMTP Configuration
                        </button>
                    </div>
                    @endif
                </div>

                <!-- Add SMTP Modal -->
                <div id="add-smtp-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 opacity-0 invisible transition-opacity duration-200" onclick="closeModal(event)">
                    <div class="glass-card rounded-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-white text-2xl font-bold">Add SMTP Configuration</h2>
                            <button onclick="closeModal()" class="p-2 rounded-lg hover:bg-white/10">
                                <span class="material-symbols-outlined text-white/70">close</span>
                            </button>
                        </div>
                        
                        <form id="addSmtpForm" class="space-y-6">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-white/80 text-sm font-medium mb-2">SMTP Name *</label>
                                    <input type="text" name="name" required class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-white/80 text-sm font-medium mb-2">From Name</label>
                                    <input type="text" name="from_name" class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">From Email Address *</label>
                                <input type="email" name="from_address" required class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-white/80 text-sm font-medium mb-2">SMTP Host *</label>
                                    <input type="text" name="host" required class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="smtp.gmail.com">
                                </div>
                                
                                <div>
                                    <label class="block text-white/80 text-sm font-medium mb-2">Port *</label>
                                    <input type="number" name="port" required class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="587">
                                </div>
                                
                                <div>
                                    <label class="block text-white/80 text-sm font-medium mb-2">Encryption *</label>
                                    <select name="encryption" required class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="tls">TLS</option>
                                        <option value="ssl">SSL</option>
                                        <option value="none">None</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-white/80 text-sm font-medium mb-2">Username *</label>
                                    <input type="text" name="username" required class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-white/80 text-sm font-medium mb-2">Password *</label>
                                    <input type="password" name="password" required class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Daily Limit *</label>
                                <input type="number" name="daily_limit" required value="500" class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-white/60 text-sm mt-1">Maximum number of emails that can be sent per day</p>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="is_active" id="is_active" checked class="rounded border-white/30 bg-white/10">
                                <label for="is_active" class="text-white/80 text-sm">Activate this SMTP immediately</label>
                            </div>
                            
                            <div class="flex justify-end gap-4 pt-6">
                                <button type="button" onclick="closeModal()" class="px-6 py-3 bg-white/10 text-white rounded-lg font-bold hover:bg-white/20 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="px-6 py-3 bg-blue-500 text-white rounded-lg font-bold hover:bg-blue-600 transition-colors">
                                    Add SMTP Configuration
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Usage Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="glass-card rounded-xl p-6 soft-shadow">
                        <h3 class="text-white font-bold text-lg mb-4">Daily Usage</h3>
                        <canvas id="dailyUsageChart" width="400" height="250"></canvas>
                    </div>
                    
                    <div class="glass-card rounded-xl p-6 soft-shadow">
                        <h3 class="text-white font-bold text-lg mb-4">SMTP Distribution</h3>
                        <canvas id="smtpDistributionChart" width="400" height="250"></canvas>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Daily Usage Chart
            const dailyCtx = document.getElementById('dailyUsageChart').getContext('2d');
            new Chart(dailyCtx, {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Emails Sent',
                        data: [150, 230, 180, 210, 190, 120, 80],
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: '#3b82f6',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: { color: 'white' }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: 'white' },
                            grid: { color: 'rgba(255,255,255,0.1)' }
                        },
                        x: {
                            ticks: { color: 'white' },
                            grid: { color: 'rgba(255,255,255,0.1)' }
                        }
                    }
                }
            });

            // SMTP Distribution Chart
            const distCtx = document.getElementById('smtpDistributionChart').getContext('2d');
            new Chart(distCtx, {
                type: 'doughnut',
                data: {
                    labels: ['SMTP 1', 'SMTP 2', 'SMTP 3'],
                    datasets: [{
                        data: [1200, 800, 400],
                        backgroundColor: ['#3b82f6', '#10b981', '#8b5cf6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: 'white' }
                        }
                    }
                }
            });

            // Add SMTP Form Submission
            document.getElementById('addSmtpForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch('/smtp', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    alert('Error adding SMTP configuration');
                });
            });
        });

        // Modal Functions
        function closeModal(event) {
            if (event && event.target.id === 'add-smtp-modal') {
                document.getElementById('add-smtp-modal').classList.add('opacity-0', 'invisible');
                document.getElementById('add-smtp-modal').classList.remove('opacity-100', 'visible');
            } else if (!event) {
                document.getElementById('add-smtp-modal').classList.add('opacity-0', 'invisible');
                document.getElementById('add-smtp-modal').classList.remove('opacity-100', 'visible');
            }
        }

        function openModal() {
            document.getElementById('add-smtp-modal').classList.remove('opacity-0', 'invisible');
            document.getElementById('add-smtp-modal').classList.add('opacity-100', 'visible');
        }

        // Open modal when clicking "Add SMTP" button
        document.querySelectorAll('button[onclick*="add-smtp-modal"]').forEach(button => {
            button.addEventListener('click', function() {
                openModal();
            });
        });

        // SMTP Actions
        function testSmtp(smtpId) {
            fetch(`/smtp/${smtpId}/test`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('SMTP connection successful!');
                } else {
                    alert('SMTP connection failed: ' + (data.error || 'Unknown error'));
                }
            });
        }

        function toggleSmtpStatus(smtpId, currentStatus) {
            fetch(`/smtp/${smtpId}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        }

        function deleteSmtp(smtpId) {
            if (confirm('Are you sure you want to delete this SMTP configuration?')) {
                fetch(`/smtp/${smtpId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                });
            }
        }

        function testAllSmtp() {
            if (confirm('Test all SMTP configurations?')) {
                alert('Testing all SMTP configurations...');
                // Implement batch testing
            }
        }

        function showUsageReport() {
            alert('Usage report feature coming soon!');
        }

        function resetDailyCounters() {
            if (confirm('Reset daily usage counters for all SMTPs?')) {
                fetch('/smtp/reset-counters', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                });
            }
        }

        function editSmtp(smtpId) {
            alert('Edit feature coming soon! SMTP ID: ' + smtpId);
        }
    </script>
</body>
</html>
