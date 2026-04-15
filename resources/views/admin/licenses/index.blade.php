<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>License Management - InfiMal Admin</title>
    
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
        .neon-glow-green {
            box-shadow: 0 0 8px rgba(34, 197, 94, 0.5), 0 0 20px rgba(34, 197, 94, 0.3);
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
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-white/90 min-h-screen">
    <div class="relative min-h-screen w-full overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-[#0a192f] via-[#020c1b] to-[#020c1b]"></div>
        
        <div class="relative z-10 flex h-full min-h-screen">
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
                            <span class="material-symbols-outlined text-white/70">dashboard</span>
                            <p class="text-sm font-medium leading-normal">Admin Dashboard</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/admin/users') }}">
                            <span class="material-symbols-outlined">group</span>
                            <p class="text-sm font-medium leading-normal">All Users</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-purple-500/20 text-white" href="{{ url('/admin/licenses') }}">
                            <span class="material-symbols-outlined text-white">verified</span>
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
                                    <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-0 border-none bg-transparent h-full placeholder:text-white/60 px-2 text-base font-normal leading-normal" placeholder="Search licenses, users..." value="" id="searchInput"/>
                                </div>
                            </label>
                        </div>
                        <div class="flex flex-1 justify-end gap-4 items-center">
                            <button onclick="window.location.href='/admin/licenses/create'" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-green-500 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-green-500/90 transition-colors">
                                <span class="material-symbols-outlined mr-2">add</span>
                                <span class="truncate">Generate License</span>
                            </button>
                            <button onclick="exportLicenses()" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-white/10 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-white/20 transition-colors">
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

                <div class="relative mb-8 glass-card rounded-xl p-8 lg:p-12 overflow-hidden soft-shadow neon-glow-green">
                    <div class="absolute -top-1/4 -left-1/4 w-1/2 h-1/2 bg-green-500/30 rounded-full filter blur-3xl opacity-50"></div>
                    <div class="absolute -bottom-1/4 -right-1/4 w-1/2 h-1/2 bg-[#3b82f6]/30 rounded-full filter blur-3xl opacity-50"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <h1 class="text-white tracking-light text-3xl font-bold leading-tight">License Management</h1>
                            <p class="text-white/70 text-base font-normal leading-normal pt-2">
                                Manage all platform licenses  {{ now()->format('F j, Y') }}
                                <span class="badge-admin ml-2">ADMIN MODE</span>
                            </p>
                            <div class="flex items-center gap-4 mt-4">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-400">verified</span>
                                    <span class="text-white/70 text-sm">Active Licenses: {{ $activeLicenses ?? 0 }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-yellow-400">schedule</span>
                                    <span class="text-white/70 text-sm">Expiring Soon: {{ $expiringSoon ?? 0 }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-red-400">block</span>
                                    <span class="text-white/70 text-sm">Expired: {{ $expiredLicenses ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-48 h-48 bg-green-500/20 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-green-500 text-6xl">verified</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-white/80 text-base font-medium">Total Licenses</p>
                            <span class="material-symbols-outlined text-blue-400">receipt_long</span>
                        </div>
                        <p class="text-white text-4xl font-bold">{{ $totalLicenses ?? 0 }}</p>
                        <p class="text-green-400 text-sm">{{ $licensesToday ?? 0 }} generated today</p>
                    </div>
                    
                    <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-white/80 text-base font-medium">Active Licenses</p>
                            <span class="material-symbols-outlined text-green-400">verified</span>
                        </div>
                        <p class="text-white text-4xl font-bold">{{ $activeLicenses ?? 0 }}</p>
                        <p class="text-green-400 text-sm">{{ number_format($activePercentage ?? 0) }}% of total</p>
                    </div>
                    
                    <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-white/80 text-base font-medium">Revenue Generated</p>
                            <span class="material-symbols-outlined text-yellow-400">payments</span>
                        </div>
                        <p class="text-white text-4xl font-bold">${{ number_format($totalRevenue ?? 0) }}</p>
                        <p class="text-green-400 text-sm">${{ number_format($revenueToday ?? 0) }} today</p>
                    </div>
                    
                    <div class="flex flex-col gap-2 rounded-xl p-6 glass-card soft-shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-white/80 text-base font-medium">Avg. Duration</p>
                            <span class="material-symbols-outlined text-purple-400">calendar_month</span>
                        </div>
                        <p class="text-white text-4xl font-bold">{{ $avgDuration ?? 30 }}d</p>
                        <p class="text-blue-400 text-sm">Most common: {{ $mostCommonDuration ?? 30 }} days</p>
                    </div>
                </div>

                <div class="mt-6 rounded-xl glass-card soft-shadow overflow-hidden">
                    <div class="p-6 border-b border-white/10 flex justify-between items-center">
                        <div>
                            <h3 class="text-white font-bold text-lg">All Platform Licenses</h3>
                            <p class="text-white/50 text-sm">Showing {{ $licenses->count() ?? 0 }} of {{ $totalLicenses ?? 0 }} licenses</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <span class="text-white/50 text-sm">Filter:</span>
                                <select class="bg-transparent border border-white/20 rounded-lg px-3 py-1 text-white text-sm" onchange="filterLicenses(this.value)">
                                    <option value="all">All Licenses</option>
                                    <option value="active">Active</option>
                                    <option value="expired">Expired</option>
                                    <option value="expiring">Expiring Soon</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">License Key</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">User</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Plan</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Duration</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Status</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Expires</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="licensesTable">
                                @if(isset($licenses) && $licenses->count() > 0)
                                    @foreach($licenses as $license)
                                    @php
                                        $licenseKey = $license->license_key ?? 'N/A';
                                        $userEmail = $license->user_email ?? 'Unassigned';
                                        $userName = $license->user_name ?? 'No Name';
                                        $userId = $license->user_id ?? null;
                                        $plan = $license->plan_type ?? 'Standard';
                                        $duration = $license->duration_days ?? 30;
                                        $createdAt = $license->created_at ?? now();
                                        $expiresAt = $license->expires_at ?? null;

                                        $status = 'active';
                                        if ($expiresAt) {
                                            $isExpired = \Carbon\Carbon::parse($expiresAt)->isPast();
                                            $expiresSoon = \Carbon\Carbon::parse($expiresAt)->diffInDays(now()) <= 7;
                                            if ($isExpired) {
                                                $status = 'expired';
                                            } elseif ($expiresSoon) {
                                                $status = 'expiring';
                                            }
                                        }
                                    @endphp
                                    
                                    <tr class="border-b border-white/5 hover:bg-white/5 license-row" data-status="{{ $status }}">
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-2">
                                                <span class="material-symbols-outlined text-green-400 text-sm">key</span>
                                                <code class="text-white font-mono text-sm bg-white/10 px-2 py-1 rounded">
                                                    {{ substr($licenseKey, 0, 8) }}...{{ substr($licenseKey, -8) }}
                                                </code>
                                                <button onclick="copyLicenseKey('{{ $licenseKey }}')" class="p-1 hover:bg-white/10 rounded" title="Copy License Key">
                                                    <span class="material-symbols-outlined text-white/60 text-sm">content_copy</span>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6">
                                            @if($userId)
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-center bg-cover" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode($userName) }}&color=FFFFFF&background=3B82F6");'></div>
                                                <div>
                                                    <p class="text-white font-medium">{{ $userName }}</p>
                                                    <p class="text-white/50 text-xs">{{ $userEmail }}</p>
                                                </div>
                                            </div>
                                            @else
                                            <span class="text-white/50 italic">Unassigned</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6">
                                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-purple-500/20 text-purple-400">
                                                {{ $plan }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6">
                                            <p class="text-white text-sm">{{ $duration }} days</p>
                                            <p class="text-white/50 text-xs">Created: {{ \Carbon\Carbon::parse($createdAt)->format('M d') }}</p>
                                        </td>
                                        <td class="py-3 px-6">
                                            <span class="px-2 py-1 rounded-full text-xs font-bold 
                                                @if($status == 'active') bg-green-500/20 text-green-400
                                                @elseif($status == 'expiring') bg-yellow-500/20 text-yellow-400
                                                @else bg-red-500/20 text-red-400 @endif
                                                flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs">
                                                    @if($status == 'active') check_circle
                                                    @elseif($status == 'expiring') schedule
                                                    @else block @endif
                                                </span>
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6">
                                            @if($expiresAt)
                                            <p class="text-white text-sm">{{ \Carbon\Carbon::parse($expiresAt)->format('M d, Y') }}</p>
                                            <p class="text-white/50 text-xs">
                                                {{ \Carbon\Carbon::parse($expiresAt)->diffForHumans() }}
                                            </p>
                                            @else
                                            <span class="text-white/50 italic">Lifetime</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-2">
                                                <button onclick="viewLicense('{{ $license->id ?? '' }}')" class="p-2 hover:bg-white/10 rounded" title="View Details">
                                                    <span class="material-symbols-outlined text-white/70">visibility</span>
                                                </button>
                                                <button onclick="editLicense('{{ $license->id ?? '' }}')" class="p-2 hover:bg-white/10 rounded" title="Edit License">
                                                    <span class="material-symbols-outlined text-blue-400">edit</span>
                                                </button>
                                                <button onclick="revokeLicense('{{ $license->id ?? '' }}')" class="p-2 hover:bg-white/10 rounded" title="Revoke License">
                                                    <span class="material-symbols-outlined text-red-400">block</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-white/50">
                                        <span class="material-symbols-outlined text-4xl mb-2">receipt_long</span>
                                        <p class="text-lg">No licenses found</p>
                                        <p class="text-sm mt-2">Generate your first license using the button above</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    @if(isset($licenses) && $licenses->hasPages())
                    <div class="p-6 border-t border-white/10">
                        <div class="flex items-center justify-between">
                            <p class="text-white/50 text-sm">
                                Showing {{ $licenses->firstItem() }} to {{ $licenses->lastItem() }} of {{ $licenses->total() }} licenses
                            </p>
                            <div class="flex items-center gap-2">
                                @if($licenses->onFirstPage())
                                <span class="px-3 py-1 rounded-lg bg-white/5 text-white/30 cursor-not-allowed">Previous</span>
                                @else
                                <a href="{{ $licenses->previousPageUrl() }}" class="px-3 py-1 rounded-lg bg-white/10 text-white hover:bg-white/20">Previous</a>
                                @endif
                                
                                @foreach($licenses->getUrlRange(max(1, $licenses->currentPage() - 2), min($licenses->lastPage(), $licenses->currentPage() + 2)) as $page => $url)
                                <a href="{{ $url }}" class="px-3 py-1 rounded-lg {{ $licenses->currentPage() == $page ? 'bg-green-500 text-white' : 'bg-white/10 text-white hover:bg-white/20' }}">
                                    {{ $page }}
                                </a>
                                @endforeach
                                
                                @if($licenses->hasMorePages())
                                <a href="{{ $licenses->nextPageUrl() }}" class="px-3 py-1 rounded-lg bg-white/10 text-white hover:bg-white/20">Next</a>
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

    <div id="bulkActionsModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
        <div class="glass-card rounded-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-white font-bold text-lg mb-4">Bulk License Actions</h3>
            <div class="space-y-3">
                <button onclick="bulkGenerateLicenses()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-green-500/20 hover:bg-green-500/30 text-green-400">
                    <span class="material-symbols-outlined">add</span>
                    <span>Generate Multiple Licenses</span>
                </button>
                <button onclick="bulkRevokeLicenses()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-red-500/20 hover:bg-red-500/30 text-red-400">
                    <span class="material-symbols-outlined">block</span>
                    <span>Revoke Selected Licenses</span>
                </button>
                <button onclick="bulkExtendLicenses()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-blue-500/20 hover:bg-blue-500/30 text-blue-400">
                    <span class="material-symbols-outlined">calendar_month</span>
                    <span>Extend License Duration</span>
                </button>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button onclick="hideBulkActions()" class="px-4 py-2 rounded-lg bg-white/10 text-white hover:bg-white/20">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.license-row');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        function filterLicenses(status) {
            const rows = document.querySelectorAll('.license-row');
            rows.forEach(row => {
                if (status === 'all') {
                    row.style.display = '';
                } else {
                    const rowStatus = row.getAttribute('data-status');
                    row.style.display = rowStatus === status ? '' : 'none';
                }
            });
        }

        function copyLicenseKey(key) {
            navigator.clipboard.writeText(key).then(() => {
                alert('License key copied to clipboard!');
            });
        }

        function viewLicense(id) {
            window.location.href = `/admin/licenses/${id}`;
        }

        function editLicense(id) {
            window.location.href = `/admin/licenses/${id}/edit`;
        }

        function revokeLicense(id) {
            if(confirm('Are you sure you want to revoke this license? This action cannot be undone.')) {
                fetch(`/admin/licenses/${id}/revoke`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to revoke license'));
                    }
                });
            }
        }

        function exportLicenses() {
            window.location.href = '/admin/licenses/export';
        }

        function showBulkActions() {
            document.getElementById('bulkActionsModal').classList.remove('hidden');
            document.getElementById('bulkActionsModal').classList.add('flex');
        }

        function hideBulkActions() {
            document.getElementById('bulkActionsModal').classList.add('hidden');
            document.getElementById('bulkActionsModal').classList.remove('flex');
        }

        function bulkGenerateLicenses() {
            const count = prompt('How many licenses do you want to generate?', '10');
            if (!count || isNaN(count)) return;
            const days = prompt('License duration in days?', '30');
            if (!days || isNaN(days)) return;
            if (confirm(`Generate ${count} licenses for ${days} days each?`)) {
                fetch('/admin/licenses/bulk-generate', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        count: parseInt(count),
                        days: parseInt(days)
                    })
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`${count} licenses generated successfully!`);
                        location.reload();
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('? License Management Loaded Successfully');
        });
    </script>
</body>
</html>