<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Email Logs - InfiMal Admin</title>
    
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
        .neon-glow-blue {
            box-shadow: 0 0 8px rgba(59, 130, 246, 0.5), 0 0 20px rgba(59, 130, 246, 0.3);
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
            background: rgba(59, 130, 246, 0.5);
            border-radius: 3px;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-delivered { background: linear-gradient(135deg, #10b981 0%, #34d399 100%); color: white; }
        .status-failed { background: linear-gradient(135deg, #ef4444 0%, #f87171 100%); color: white; }
        .status-pending { background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); color: white; }
        .status-bounced { background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%); color: white; }
        .status-sent { background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); color: white; }
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
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-blue-500/20 text-white" href="{{ url('/admin/emails') }}">
                            <span class="material-symbols-outlined text-white">monitoring</span>
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
                                    <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-0 border-none bg-transparent h-full placeholder:text-white/60 px-2 text-base font-normal leading-normal" placeholder="Search emails, users, subjects..." value="" id="searchInput"/>
                                </div>
                            </label>
                        </div>
                        <div class="flex flex-1 justify-end gap-4 items-center">
                            <button onclick="refreshStats()" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-green-500 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-green-500/90 transition-colors">
                                <span class="material-symbols-outlined mr-2">refresh</span>
                                <span class="truncate">Refresh</span>
                            </button>
                            <button onclick="exportEmailLogs()" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-white/10 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-white/20 transition-colors">
                                <span class="material-symbols-outlined mr-2">download</span>
                                <span class="truncate">Export</span>
                            </button>
                            <button onclick="showBulkActions()" class="flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-white/10 text-white/80 gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5 hover:bg-white/20 transition-colors">
                                <span class="material-symbols-outlined">more_vert</span>
                            </button>
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? "Admin") }}&color=FFFFFF&background=8B5CF6");'></div>
                        </div>
                    </div>
                </header>

                <!-- Hero Section -->
                <div class="relative mb-8 glass-card rounded-xl p-8 lg:p-12 overflow-hidden soft-shadow neon-glow-blue">
                    <div class="absolute -top-1/4 -left-1/4 w-1/2 h-1/2 bg-blue-500/30 rounded-full filter blur-3xl opacity-50"></div>
                    <div class="absolute -bottom-1/4 -right-1/4 w-1/2 h-1/2 bg-[#3b82f6]/30 rounded-full filter blur-3xl opacity-50"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <h1 class="text-white tracking-light text-3xl font-bold leading-tight">Email Logs & Analytics</h1>
                            <p class="text-white/70 text-base font-normal leading-normal pt-2">
                                Monitor all platform emails • {{ now()->format('F j, Y') }}
                                <span class="badge-admin ml-2">REAL-TIME TRACKING</span>
                            </p>
                            <div class="flex items-center gap-4 mt-4">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-400">send</span>
                                    <span class="text-white/70 text-sm">Delivered: {{ number_format($deliveredCount) }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-red-400">error</span>
                                    <span class="text-white/70 text-sm">Failed: {{ number_format($failedCount) }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-yellow-400">schedule</span>
                                    <span class="text-white/70 text-sm">Pending: {{ number_format($pendingCount) }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-purple-400">undo</span>
                                    <span class="text-white/70 text-sm">Bounced: {{ number_format($bouncedCount) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-48 h-48 bg-blue-500/20 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-blue-500 text-6xl">monitoring</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- REAL EMAIL STATISTICS CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-white/80 text-base font-medium">Total Emails</p>
                            <span class="material-symbols-outlined text-blue-400">mail</span>
                        </div>
                        <p class="text-white text-4xl font-bold" id="totalEmails">{{ number_format($totalEmails) }}</p>
                        <p class="text-green-400 text-sm" id="emailsToday">{{ number_format($emailsToday) }} sent today</p>
                    </div>
                    
                    <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-white/80 text-base font-medium">Success Rate</p>
                            <span class="material-symbols-outlined {{ $successRate > 90 ? 'text-green-400' : ($successRate > 70 ? 'text-yellow-400' : 'text-red-400') }}" id="successIcon">
                                {{ $successRate > 90 ? 'check_circle' : ($successRate > 70 ? 'warning' : 'error') }}
                            </span>
                        </div>
                        <p class="text-white text-4xl font-bold" id="successRate">{{ $successRate }}%</p>
                        <p class="{{ $successRate > 90 ? 'text-green-400' : ($successRate > 70 ? 'text-yellow-400' : 'text-red-400') }} text-sm" id="successStatus">
                            {{ $successRate > 90 ? 'Excellent' : ($successRate > 70 ? 'Good' : 'Needs Improvement') }}
                        </p>
                    </div>
                    
                    <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-white/80 text-base font-medium">Avg. Open Rate</p>
                            <span class="material-symbols-outlined text-yellow-400">visibility</span>
                        </div>
                        <p class="text-white text-4xl font-bold" id="avgOpenRate">{{ $avgOpenRate }}%</p>
                        <p class="text-blue-400 text-sm" id="opensToday">{{ number_format($opensToday) }} opens today</p>
                    </div>
                    
                    <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-white/80 text-base font-medium">Avg. Click Rate</p>
                            <span class="material-symbols-outlined text-purple-400">click</span>
                        </div>
                        <p class="text-white text-4xl font-bold" id="avgClickRate">{{ $avgClickRate }}%</p>
                        <p class="text-purple-400 text-sm" id="clicksToday">{{ number_format($clicksToday) }} clicks today</p>
                    </div>
                </div>

                <!-- EMAIL STATUS DISTRIBUTION - REAL DATA -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="rounded-xl glass-card soft-shadow p-6">
                        <h3 class="text-white font-bold text-lg mb-4">Email Status Distribution</h3>
                        <div class="space-y-4">
                            @php
                                $statuses = [
                                    'delivered' => ['name' => 'Delivered', 'color' => 'bg-green-500', 'count' => $deliveredCount],
                                    'sent' => ['name' => 'Sent', 'color' => 'bg-blue-500', 'count' => $sentCount],
                                    'pending' => ['name' => 'Pending', 'color' => 'bg-yellow-500', 'count' => $pendingCount],
                                    'failed' => ['name' => 'Failed', 'color' => 'bg-red-500', 'count' => $failedCount],
                                    'bounced' => ['name' => 'Bounced', 'color' => 'bg-purple-500', 'count' => $bouncedCount],
                                ];
                                $totalStatus = $totalEmails;
                            @endphp
                            
                            @foreach($statuses as $status => $data)
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 rounded-full {{ $data['color'] }}"></div>
                                        <span class="text-white/70 text-sm">{{ $data['name'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-white font-bold" id="status{{ ucfirst($status) }}">{{ number_format($data['count']) }}</span>
                                        <span class="text-white/50 text-sm" id="status{{ ucfirst($status) }}Percent">
                                            @if($totalStatus > 0)
                                            {{ number_format(($data['count'] / $totalStatus) * 100, 1) }}%
                                            @else
                                            0%
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full {{ $data['color'] }} rounded-full" id="status{{ ucfirst($status) }}Bar"
                                         style="width: {{ $totalStatus > 0 ? ($data['count'] / $totalStatus) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="rounded-xl glass-card soft-shadow p-6">
                        <h3 class="text-white font-bold text-lg mb-4">Today's Performance • {{ now()->format('F j, Y') }}</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 rounded-lg bg-white/5">
                                <p class="text-white text-2xl font-bold" id="todayEmails">{{ number_format($emailsToday) }}</p>
                                <p class="text-white/70 text-sm">Emails Sent</p>
                            </div>
                            <div class="text-center p-4 rounded-lg bg-white/5">
                                <p class="text-white text-2xl font-bold" id="todayOpens">{{ number_format($opensToday) }}</p>
                                <p class="text-white/70 text-sm">Opens</p>
                            </div>
                            <div class="text-center p-4 rounded-lg bg-white/5">
                                <p class="text-white text-2xl font-bold" id="todayClicks">{{ number_format($clicksToday) }}</p>
                                <p class="text-white/70 text-sm">Clicks</p>
                            </div>
                            <div class="text-center p-4 rounded-lg bg-white/5">
                                <p class="text-white text-2xl font-bold" id="todayFailures">{{ number_format($failuresToday) }}</p>
                                <p class="text-white/70 text-sm">Failures</p>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-white/60 text-sm">
                                Avg. Response Time: <span class="text-white font-bold" id="avgResponseTime">{{ $avgResponseTime }}s</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- EMAIL LOGS TABLE -->
                <div class="mt-6 rounded-xl glass-card soft-shadow overflow-hidden">
                    <div class="p-6 border-b border-white/10 flex justify-between items-center">
                        <div>
                            <h3 class="text-white font-bold text-lg">All Email Logs</h3>
                            <p class="text-white/50 text-sm">Showing {{ $emails->count() }} of {{ number_format($totalEmails) }} emails</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <span class="text-white/50 text-sm">Filter:</span>
                                <select class="bg-transparent border border-white/20 rounded-lg px-3 py-1 text-white text-sm" onchange="filterEmails(this.value)">
                                    <option value="all">All Emails</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="failed">Failed</option>
                                    <option value="pending">Pending</option>
                                    <option value="bounced">Bounced</option>
                                    <option value="sent">Sent</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-white/50 text-sm">Date:</span>
                                <select class="bg-transparent border border-white/20 rounded-lg px-3 py-1 text-white text-sm" onchange="filterByDate(this.value)">
                                    <option value="today">Today</option>
                                    <option value="yesterday">Yesterday</option>
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                    <option value="all">All Time</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Recipient</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Subject</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">User</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Status</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Opens/Clicks</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Sent At</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Response</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="emailsTable">
                                @if($emails->count() > 0)
                                    @foreach($emails as $email)
                                    @php
                                        $emailId = $email->id;
                                        $recipient = $email->to_email ?? 'N/A';
                                        $subject = $email->subject ?? 'No Subject';
                                        $userEmail = $email->user_email ?? 'System';
                                        $userName = $email->user_name ?? 'System';
                                        $status = $email->status ?? 'pending';
                                        $opens = $email->opens_count ?? 0;
                                        $clicks = $email->clicks_count ?? 0;
                                        $openRate = $email->open_rate ?? 0;
                                        $clickRate = $email->click_rate ?? 0;
                                        $responseTime = $email->response_time ?? null;
                                        $errorMessage = $email->error_message ?? null;
                                        $sentAt = $email->sent_at ?? $email->created_at;
                                        $sentTime = \Carbon\Carbon::parse($sentAt);
                                        
                                        // Status colors
                                        $statusClasses = [
                                            'delivered' => 'status-delivered',
                                            'sent' => 'status-sent',
                                            'pending' => 'status-pending',
                                            'failed' => 'status-failed',
                                            'bounced' => 'status-bounced',
                                        ];
                                        $statusClass = $statusClasses[$status] ?? 'status-pending';
                                    @endphp
                                    
                                    <tr class="border-b border-white/5 hover:bg-white/5 email-row" data-status="{{ $status }}">
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-center bg-cover" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode($recipient) }}&color=FFFFFF&background=3B82F6");'></div>
                                                <div>
                                                    <p class="text-white font-medium text-sm">{{ $recipient }}</p>
                                                    <p class="text-white/50 text-xs truncate max-w-xs">{{ $subject }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6">
                                            <p class="text-white text-sm truncate max-w-xs">{{ $subject }}</p>
                                            @if($errorMessage)
                                            <p class="text-red-400 text-xs mt-1 truncate max-w-xs">{{ $errorMessage }}</p>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-6 h-6 rounded-full bg-center bg-cover" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode($userName) }}&color=FFFFFF&background=8B5CF6");'></div>
                                                <div>
                                                    <p class="text-white text-sm">{{ $userName }}</p>
                                                    <p class="text-white/50 text-xs">{{ $userEmail }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6">
                                            <span class="status-badge {{ $statusClass }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-4">
                                                <div class="text-center">
                                                    <p class="text-white text-sm font-bold">{{ $opens }}</p>
                                                    <p class="text-white/50 text-xs">{{ $openRate }}%</p>
                                                </div>
                                                <div class="text-center">
                                                    <p class="text-white text-sm font-bold">{{ $clicks }}</p>
                                                    <p class="text-white/50 text-xs">{{ $clickRate }}%</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6">
                                            <p class="text-white text-sm">{{ $sentTime->format('M d, H:i') }}</p>
                                            <p class="text-white/50 text-xs">
                                                {{ $sentTime->diffForHumans() }}
                                            </p>
                                        </td>
                                        <td class="py-3 px-6">
                                            @if($responseTime)
                                            <p class="text-white text-sm">{{ number_format($responseTime, 2) }}s</p>
                                            <p class="text-white/50 text-xs">Response</p>
                                            @else
                                            <span class="text-white/50 text-sm italic">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-2">
                                                <button onclick="viewEmailDetails('{{ $emailId }}')" class="p-2 hover:bg-white/10 rounded" title="View Details">
                                                    <span class="material-symbols-outlined text-white/70 text-sm">visibility</span>
                                                </button>
                                                @if($status === 'failed' || $status === 'bounced')
                                                <button onclick="resendEmail('{{ $emailId }}')" class="p-2 hover:bg-white/10 rounded" title="Resend Email">
                                                    <span class="material-symbols-outlined text-green-400 text-sm">refresh</span>
                                                </button>
                                                @endif
                                                <button onclick="deleteEmailLog('{{ $emailId }}')" class="p-2 hover:bg-white/10 rounded" title="Delete Log">
                                                    <span class="material-symbols-outlined text-red-400 text-sm">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-white/50">
                                        <span class="material-symbols-outlined text-4xl mb-2">mail</span>
                                        <p class="text-lg">No email logs found</p>
                                        <p class="text-sm mt-2">Email logs will appear here when users send emails</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    @if($emails->hasPages())
                    <div class="p-6 border-t border-white/10">
                        <div class="flex items-center justify-between">
                            <p class="text-white/50 text-sm">
                                Showing {{ $emails->firstItem() }} to {{ $emails->lastItem() }} of {{ $emails->total() }} emails
                            </p>
                            <div class="flex items-center gap-2">
                                @if($emails->onFirstPage())
                                <span class="px-3 py-1 rounded-lg bg-white/5 text-white/30 cursor-not-allowed">Previous</span>
                                @else
                                <a href="{{ $emails->previousPageUrl() }}" class="px-3 py-1 rounded-lg bg-white/10 text-white hover:bg-white/20">Previous</a>
                                @endif
                                
                                @foreach($emails->getUrlRange(max(1, $emails->currentPage() - 2), min($emails->lastPage(), $emails->currentPage() + 2)) as $page => $url)
                                <a href="{{ $url }}" class="px-3 py-1 rounded-lg {{ $emails->currentPage() == $page ? 'bg-blue-500 text-white' : 'bg-white/10 text-white hover:bg-white/20' }}">
                                    {{ $page }}
                                </a>
                                @endforeach
                                
                                @if($emails->hasMorePages())
                                <a href="{{ $emails->nextPageUrl() }}" class="px-3 py-1 rounded-lg bg-white/10 text-white hover:bg-white/20">Next</a>
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

    <!-- Bulk Actions Modal -->
    <div id="bulkActionsModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
        <div class="glass-card rounded-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-white font-bold text-lg mb-4">Bulk Email Actions</h3>
            <div class="space-y-3">
                <button onclick="bulkResendFailed()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-green-500/20 hover:bg-green-500/30 text-green-400">
                    <span class="material-symbols-outlined">refresh</span>
                    <span>Resend All Failed</span>
                </button>
                <button onclick="bulkDeleteOld()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-red-500/20 hover:bg-red-500/30 text-red-400">
                    <span class="material-symbols-outlined">delete</span>
                    <span>Delete Older Than 30 Days</span>
                </button>
                <button onclick="bulkExport()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-blue-500/20 hover:bg-blue-500/30 text-blue-400">
                    <span class="material-symbols-outlined">download</span>
                    <span>Export Selected</span>
                </button>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button onclick="hideBulkActions()" class="px-4 py-2 rounded-lg bg-white/10 text-white hover:bg-white/20">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.email-row');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        function filterEmails(status) {
            const rows = document.querySelectorAll('.email-row');
            rows.forEach(row => {
                if (status === 'all') {
                    row.style.display = '';
                } else {
                    const rowStatus = row.getAttribute('data-status');
                    row.style.display = rowStatus === status ? '' : 'none';
                }
            });
        }

        function filterByDate(range) {
            // Reload page with date filter
            window.location.href = '{{ url("/admin/emails") }}?date_filter=' + range;
        }

        function viewEmailDetails(id) {
            window.location.href = `/admin/emails/${id}`;
        }

        function resendEmail(id) {
            if(confirm('Resend this email?')) {
                fetch(`/admin/emails/${id}/resend`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Email queued for resending');
                        refreshStats();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to resend email'));
                    }
                });
            }
        }

        function deleteEmailLog(id) {
            if(confirm('Delete this email log? This action cannot be undone.')) {
                fetch(`/admin/emails/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        refreshStats();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to delete email log'));
                    }
                });
            }
        }

        function exportEmailLogs() {
            window.location.href = '/admin/emails/export';
        }

        function refreshStats() {
            // Show loading
            document.querySelectorAll('.text-4xl').forEach(el => {
                el.innerHTML = '<span class="animate-pulse">---</span>';
            });
            
            // Fetch updated stats
            fetch('/admin/emails/stats')
                .then(response => response.json())
                .then(data => {
                    // Update all stats
                    updateStat('totalEmails', data.totalEmails);
                    updateStat('emailsToday', data.emailsToday + ' sent today');
                    updateStat('successRate', data.successRate + '%');
                    updateStat('avgOpenRate', data.avgOpenRate + '%');
                    updateStat('avgClickRate', data.avgClickRate + '%');
                    updateStat('opensToday', data.opensToday + ' opens today');
                    updateStat('clicksToday', data.clicksToday + ' clicks today');
                    updateStat('todayEmails', data.emailsToday);
                    updateStat('todayOpens', data.opensToday);
                    updateStat('todayClicks', data.clicksToday);
                    updateStat('todayFailures', data.failuresToday);
                    updateStat('avgResponseTime', data.avgResponseTime + 's');
                    
                    // Update status counts
                    updateStat('statusDelivered', data.deliveredCount);
                    updateStat('statusSent', data.sentCount);
                    updateStat('statusPending', data.pendingCount);
                    updateStat('statusFailed', data.failedCount);
                    updateStat('statusBounced', data.bouncedCount);
                    
                    // Update status percentages and bars
                    const total = data.totalEmails;
                    updateStatusBar('Delivered', data.deliveredCount, total);
                    updateStatusBar('Sent', data.sentCount, total);
                    updateStatusBar('Pending', data.pendingCount, total);
                    updateStatusBar('Failed', data.failedCount, total);
                    updateStatusBar('Bounced', data.bouncedCount, total);
                    
                    // Update success icon and status
                    const successIcon = document.getElementById('successIcon');
                    const successStatus = document.getElementById('successStatus');
                    if (data.successRate > 90) {
                        successIcon.className = 'material-symbols-outlined text-green-400';
                        successIcon.textContent = 'check_circle';
                        successStatus.className = 'text-green-400 text-sm';
                        successStatus.textContent = 'Excellent';
                    } else if (data.successRate > 70) {
                        successIcon.className = 'material-symbols-outlined text-yellow-400';
                        successIcon.textContent = 'warning';
                        successStatus.className = 'text-yellow-400 text-sm';
                        successStatus.textContent = 'Good';
                    } else {
                        successIcon.className = 'material-symbols-outlined text-red-400';
                        successIcon.textContent = 'error';
                        successStatus.className = 'text-red-400 text-sm';
                        successStatus.textContent = 'Needs Improvement';
                    }
                    
                    // Show success message
                    showToast('Stats updated successfully!', 'success');
                })
                .catch(error => {
                    console.error('Error refreshing stats:', error);
                    showToast('Failed to refresh stats', 'error');
                });
        }

        function updateStat(elementId, value) {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = value;
            }
        }

        function updateStatusBar(status, count, total) {
            const percentElement = document.getElementById('status' + status + 'Percent');
            const barElement = document.getElementById('status' + status + 'Bar');
            
            if (percentElement && barElement) {
                const percent = total > 0 ? (count / total) * 100 : 0;
                percentElement.textContent = percent.toFixed(1) + '%';
                barElement.style.width = percent + '%';
            }
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

        function showBulkActions() {
            document.getElementById('bulkActionsModal').classList.remove('hidden');
            document.getElementById('bulkActionsModal').classList.add('flex');
        }

        function hideBulkActions() {
            document.getElementById('bulkActionsModal').classList.add('hidden');
            document.getElementById('bulkActionsModal').classList.remove('flex');
        }

        function bulkResendFailed() {
            if(confirm('Resend all failed emails? This may take some time.')) {
                fetch('/admin/emails/bulk-resend-failed', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        refreshStats();
                    }
                    hideBulkActions();
                });
            }
        }

        function bulkDeleteOld() {
            if(confirm('Delete all email logs older than 30 days?')) {
                fetch('/admin/emails/bulk-delete-old', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        refreshStats();
                    }
                    hideBulkActions();
                });
            }
        }

        function bulkExport() {
            window.location.href = '/admin/emails/export?type=selected';
        }

        // Auto-refresh stats every 30 seconds
        setInterval(refreshStats, 30000);

        document.addEventListener('DOMContentLoaded', function() {
            console.log('📧 Email Logs Management Loaded Successfully');
            console.log('Total Emails: {{ $totalEmails }}');
            console.log('Success Rate: {{ $successRate }}%');
            console.log('Real-time stats enabled');
        });
    </script>
</body>
</html>
