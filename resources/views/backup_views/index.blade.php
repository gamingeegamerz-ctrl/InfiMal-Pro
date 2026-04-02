<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Subscribers - InfiMal</title>
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
                            <span class="material-symbols-outlined text-white">dashboard</span>
                            <p class="text-sm font-medium leading-normal">Dashboard</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-blue-500/20 text-white" href="{{ url('/subscribers') }}">
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
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/smtp') }}">
                            <span class="material-symbols-outlined">dns</span>
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
                            <div>
                                <h1 class="text-white text-xl font-bold">Subscribers</h1>
                                <p class="text-white/60 text-sm">Manage your email list</p>
                            </div>
                            
                            <label class="flex flex-col w-72">
                                <div class="flex w-full flex-1 items-stretch rounded-lg h-10">
                                    <div class="text-white/60 flex bg-transparent items-center justify-center pl-3">
                                        <span class="material-symbols-outlined">search</span>
                                    </div>
                                    <form method="GET" action="{{ route('subscribers.index') }}" class="flex w-full">
                                        <input 
                                            name="search"
                                            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-0 border-none bg-transparent h-full placeholder:text-white/60 px-2 text-base font-normal leading-normal" 
                                            placeholder="Search subscribers..." 
                                            value="{{ request('search') }}"
                                        />
                                    </form>
                                </div>
                            </label>
                        </div>
                        <div class="flex flex-1 justify-end gap-4 items-center">
                            <button onclick="window.location.href='{{ route('subscribers.import') }}'" class="flex min-w-[100px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-white/10 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-white/20 transition-colors">
                                <span class="material-symbols-outlined text-sm mr-2">upload</span>
                                <span class="truncate">Import CSV</span>
                            </button>
                            <button onclick="window.location.href='{{ route('subscribers.create') }}'" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-blue-500 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-blue-500/90 transition-colors">
                                <span class="material-symbols-outlined text-sm mr-2">add</span>
                                <span class="truncate">Add Subscriber</span>
                            </button>
                            <button onclick="window.location.href='{{ route('subscribers.export') }}'" class="flex min-w-[100px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-green-500/20 text-green-400 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-green-500/30 transition-colors">
                                <span class="material-symbols-outlined text-sm mr-2">download</span>
                                <span class="truncate">Export CSV</span>
                            </button>
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=FFFFFF&background=3B82F6");'></div>
                        </div>
                    </div>
                </header>

                <!-- Hero Section -->
                <div class="relative my-8 glass-card rounded-xl p-8 lg:p-12 overflow-hidden soft-shadow">
                    <div class="absolute -top-1/4 -left-1/4 w-1/2 h-1/2 bg-blue-500/30 rounded-full filter blur-3xl opacity-50"></div>
                    <div class="absolute -bottom-1/4 -right-1/4 w-1/2 h-1/2 bg-[#a855f7]/30 rounded-full filter blur-3xl opacity-50"></div>
                    <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Stats Cards -->
                        <div class="flex flex-col gap-2 rounded-xl p-6 bg-white/5">
                            <p class="text-white/80 text-base font-medium leading-normal">Total Subscribers</p>
                            <p class="text-white tracking-light text-4xl font-bold leading-tight">{{ number_format($stats['total']) }}</p>
                        </div>
                        <div class="flex flex-col gap-2 rounded-xl p-6 bg-white/5">
                            <p class="text-white/80 text-base font-medium leading-normal">Active Subscribers</p>
                            <p class="text-white tracking-light text-4xl font-bold leading-tight">{{ number_format($stats['active']) }}</p>
                        </div>
                        <div class="flex flex-col gap-2 rounded-xl p-6 bg-white/5">
                            <p class="text-white/80 text-base font-medium leading-normal">Inactive</p>
                            <p class="text-white tracking-light text-4xl font-bold leading-tight">{{ number_format($stats['inactive']) }}</p>
                        </div>
                        <div class="flex flex-col gap-2 rounded-xl p-6 bg-white/5">
                            <p class="text-white/80 text-base font-medium leading-normal">New Today</p>
                            <p class="text-white tracking-light text-4xl font-bold leading-tight">{{ number_format($stats['new_today']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Main Grid -->
                <div class="flex-1 grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Left Panel - Lists & Filters -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Status Filter -->
                        <div class="glass-card rounded-xl p-6 soft-shadow">
                            <h3 class="text-white font-bold text-lg mb-4">Filter by Status</h3>
                            <div class="space-y-2">
                                <a href="{{ route('subscribers.index') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-white/10 transition-colors {{ !request('status') ? 'bg-white/10' : '' }}">
                                    <span class="text-white">All Subscribers</span>
                                    <span class="text-white/60">{{ $stats['total'] }}</span>
                                </a>
                                <a href="{{ route('subscribers.index', ['status' => 'active']) }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-white/10 transition-colors {{ request('status') == 'active' ? 'bg-white/10' : '' }}">
                                    <span class="text-green-400">Active</span>
                                    <span class="text-white/60">{{ $stats['active'] }}</span>
                                </a>
                                <a href="{{ route('subscribers.index', ['status' => 'inactive']) }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-white/10 transition-colors {{ request('status') == 'inactive' ? 'bg-white/10' : '' }}">
                                    <span class="text-yellow-400">Inactive</span>
                                    <span class="text-white/60">{{ $stats['inactive'] }}</span>
                                </a>
                                <a href="{{ route('subscribers.index', ['status' => 'unsubscribed']) }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-white/10 transition-colors {{ request('status') == 'unsubscribed' ? 'bg-white/10' : '' }}">
                                    <span class="text-red-400">Unsubscribed</span>
                                    <span class="text-white/60">{{ $stats['total'] - $stats['active'] - $stats['inactive'] }}</span>
                                </a>
                            </div>
                        </div>

                        <!-- Lists -->
                        <div class="glass-card rounded-xl p-6 soft-shadow">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-white font-bold text-lg">Your Lists</h3>
                                <a href="{{ route('lists.create') }}" class="text-blue-400 hover:text-blue-300 text-sm">
                                    + New List
                                </a>
                            </div>
                            <div class="space-y-3">
                                @foreach($lists as $list)
                                <a href="#" class="flex items-center justify-between p-2 rounded-lg hover:bg-white/10 transition-colors">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm text-white/60">list</span>
                                        <span class="text-white">{{ $list->name }}</span>
                                    </div>
                                    <span class="text-white/60 text-sm">{{ $list->subscribers_count }}</span>
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Growth Chart -->
                        <div class="glass-card rounded-xl p-6 soft-shadow">
                            <h3 class="text-white font-bold text-lg mb-4">Growth (7 Days)</h3>
                            <canvas id="growthChart" width="300" height="200"></canvas>
                        </div>
                    </div>

                    <!-- Right Panel - Subscribers Table -->
                    <div class="lg:col-span-3">
                        <div class="glass-card rounded-xl overflow-hidden soft-shadow">
                            <!-- Table Header -->
                            <div class="p-6 border-b border-white/10">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-white font-bold text-lg">All Subscribers</h3>
                                    <div class="flex items-center gap-2">
                                        <span class="text-white/60 text-sm">Showing {{ $subscribers->firstItem() }}-{{ $subscribers->lastItem() }} of {{ $subscribers->total() }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="border-b border-white/10">
                                        <tr>
                                            <th class="text-left p-4 text-white/60 font-medium">Email</th>
                                            <th class="text-left p-4 text-white/60 font-medium">Name</th>
                                            <th class="text-left p-4 text-white/60 font-medium">Status</th>
                                            <th class="text-left p-4 text-white/60 font-medium">Source</th>
                                            <th class="text-left p-4 text-white/60 font-medium">Subscribed</th>
                                            <th class="text-left p-4 text-white/60 font-medium">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($subscribers as $subscriber)
                                        <tr class="border-b border-white/5 hover:bg-white/5">
                                            <td class="p-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center">
                                                        <span class="material-symbols-outlined text-blue-500 text-sm">mail</span>
                                                    </div>
                                                    <div>
                                                        <p class="text-white font-medium">{{ $subscriber->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-4">
                                                <p class="text-white">{{ $subscriber->name ?? 'N/A' }}</p>
                                            </td>
                                            <td class="p-4">
                                                @php
                                                    $statusColors = [
                                                        'active' => 'bg-green-500/20 text-green-400',
                                                        'inactive' => 'bg-yellow-500/20 text-yellow-400',
                                                        'unsubscribed' => 'bg-red-500/20 text-red-400'
                                                    ];
                                                @endphp
                                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$subscriber->status] ?? 'bg-gray-500/20 text-gray-400' }}">
                                                    {{ ucfirst($subscriber->status) }}
                                                </span>
                                            </td>
                                            <td class="p-4">
                                                <p class="text-white/60">{{ ucfirst($subscriber->source) }}</p>
                                            </td>
                                            <td class="p-4">
                                                <p class="text-white/60">{{ $subscriber->subscribed_at?->format('M d, Y') }}</p>
                                            </td>
                                            <td class="p-4">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('subscribers.edit', $subscriber) }}" class="p-2 rounded-lg hover:bg-white/10 text-white/60 hover:text-white">
                                                        <span class="material-symbols-outlined text-sm">edit</span>
                                                    </a>
                                                    <form action="{{ route('subscribers.destroy', $subscriber) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-white/60 hover:text-red-400">
                                                            <span class="material-symbols-outlined text-sm">delete</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="p-8 text-center">
                                                <div class="flex flex-col items-center justify-center gap-4">
                                                    <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center">
                                                        <span class="material-symbols-outlined text-white/60 text-2xl">group</span>
                                                    </div>
                                                    <p class="text-white/60">No subscribers found</p>
                                                    <a href="{{ route('subscribers.create') }}" class="text-blue-400 hover:text-blue-300">Add your first subscriber</a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if($subscribers->hasPages())
                            <div class="p-6 border-t border-white/10">
                                {{ $subscribers->links() }}
                            </div>
                            @endif
                        </div>

                        <!-- Bulk Actions -->
                        <div class="mt-6 glass-card rounded-xl p-6 soft-shadow">
                            <h3 class="text-white font-bold text-lg mb-4">Bulk Actions</h3>
                            <div class="flex flex-wrap gap-4">
                                <button onclick="showBulkModal('add_to_list')" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition-colors">
                                    <span class="material-symbols-outlined text-sm">add</span>
                                    <span>Add to List</span>
                                </button>
                                <button onclick="showBulkModal('change_status')" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition-colors">
                                    <span class="material-symbols-outlined text-sm">swap_horiz</span>
                                    <span>Change Status</span>
                                </button>
                                <button onclick="showBulkModal('export_selected')" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition-colors">
                                    <span class="material-symbols-outlined text-sm">download</span>
                                    <span>Export Selected</span>
                                </button>
                                <button onclick="showBulkModal('delete')" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500/20 hover:bg-red-500/30 text-red-400 transition-colors">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                    <span>Delete Selected</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Bulk Action Modal -->
        <div id="bulkModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center">
            <div class="glass-card rounded-xl p-6 w-full max-w-md">
                <h3 id="modalTitle" class="text-white font-bold text-xl mb-4"></h3>
                <div id="modalContent"></div>
                <div class="flex justify-end gap-3 mt-6">
                    <button onclick="hideBulkModal()" class="px-4 py-2 rounded-lg hover:bg-white/10 text-white/70">Cancel</button>
                    <button onclick="processBulkAction()" class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600">Apply</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Growth Chart
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('growthChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($growthLabels),
                    datasets: [{
                        label: 'Subscribers',
                        data: @json($growthData),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
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
        });

        // Bulk Actions Modal
        function showBulkModal(action) {
            const modal = document.getElementById('bulkModal');
            const title = document.getElementById('modalTitle');
            const content = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            
            switch(action) {
                case 'add_to_list':
                    title.textContent = 'Add to List';
                    content.innerHTML = `
                        <p class="text-white/70 mb-4">Select a list to add selected subscribers:</p>
                        <select id="listSelect" class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select List</option>
                            @foreach($lists as $list)
                            <option value="{{ $list->id }}">{{ $list->name }}</option>
                            @endforeach
                        </select>
                    `;
                    break;
                    
                case 'change_status':
                    title.textContent = 'Change Status';
                    content.innerHTML = `
                        <p class="text-white/70 mb-4">Select new status for selected subscribers:</p>
                        <select id="statusSelect" class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="unsubscribed">Unsubscribed</option>
                        </select>
                    `;
                    break;
                    
                case 'delete':
                    title.textContent = 'Delete Subscribers';
                    content.innerHTML = `
                        <p class="text-white/70 mb-4">Are you sure you want to delete selected subscribers? This action cannot be undone.</p>
                    `;
                    break;
            }
        }

        function hideBulkModal() {
            document.getElementById('bulkModal').classList.add('hidden');
        }

        function processBulkAction() {
            // Implement bulk action logic here
            alert('Bulk action would be processed here');
            hideBulkModal();
        }

        // Live search with debounce
        let searchTimeout;
        document.querySelector('input[name="search"]').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                e.target.form.submit();
            }, 500);
        });
    </script>
</body>
</html>
