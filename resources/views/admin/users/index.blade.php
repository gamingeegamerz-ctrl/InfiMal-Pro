<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <!-- CSRF TOKEN MUST BE HERE -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Users Management - InfiMal</title>
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
            <!-- SideNavBar (ADMIN VERSION) - SAME AS DASHBOARD -->
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
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-purple-500/20 text-white" href="{{ url('/admin/users') }}">
                            <span class="material-symbols-outlined text-white">group</span>
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
                <!-- TopNavBar - SAME AS DASHBOARD -->
                <header class="flex-shrink-0 glass-card rounded-xl px-6 py-3 sticky top-6 z-20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-8">
                            <label class="flex flex-col w-72">
                                <div class="flex w-full flex-1 items-stretch rounded-lg h-10">
                                    <div class="text-white/60 flex bg-transparent items-center justify-center pl-3">
                                        <span class="material-symbols-outlined">search</span>
                                    </div>
                                    <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-0 border-none bg-transparent h-full placeholder:text-white/60 px-2 text-base font-normal leading-normal" placeholder="Search users by name, email..." value="" id="searchInput" onkeyup="searchUsers()"/>
                                </div>
                            </label>
                        </div>
                        <div class="flex flex-1 justify-end gap-4 items-center">
                            <button onclick="window.location.href='/admin/users/create'" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-purple-500 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-purple-500/90 transition-colors">
                                <span class="material-symbols-outlined mr-2">add</span>
                                <span class="truncate">Add User</span>
                            </button>
                            <button onclick="exportUsers()" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-white/10 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-white/20 transition-colors">
                                <span class="material-symbols-outlined mr-2">download</span>
                                <span class="truncate">Export CSV</span>
                            </button>
                            <button onclick="showBulkActions()" class="flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-white/10 text-white/80 gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5 hover:bg-white/20 transition-colors">
                                <span class="material-symbols-outlined">more_vert</span>
                            </button>
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? "Admin") }}&color=FFFFFF&background=8B5CF6");'></div>
                        </div>
                    </div>
                </header>

                <!-- Hero Section - CUSTOMIZED FOR USERS -->
                <div class="relative my-8 glass-card rounded-xl p-8 lg:p-12 overflow-hidden soft-shadow">
                    <div class="absolute -top-1/4 -left-1/4 w-1/2 h-1/2 bg-purple-500/30 rounded-full filter blur-3xl opacity-50"></div>
                    <div class="absolute -bottom-1/4 -right-1/4 w-1/2 h-1/2 bg-[#f59e0b]/30 rounded-full filter blur-3xl opacity-50"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <h1 class="text-white tracking-light text-3xl font-bold leading-tight">User Management</h1>
                            <p class="text-white/70 text-base font-normal leading-normal pt-2">
                                Manage all platform users • Total: {{ $totalUsers ?? 0 }} users
                                <span class="badge-admin ml-2">ADMIN MODE</span>
                            </p>
                            <div class="flex items-center gap-4 mt-4">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-purple-400">group</span>
                                    <span class="text-white/70 text-sm">{{ $totalUsers ?? 0 }} Total Users</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-400">verified</span>
                                    <span class="text-white/70 text-sm">{{ $verifiedUsers ?? 0 }} Verified Users</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-red-400">block</span>
                                    <span class="text-white/70 text-sm">Active Users: {{ $activeUsers ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-48 h-48 bg-purple-500/20 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-purple-500 text-6xl">manage_accounts</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="mt-6 rounded-xl glass-card soft-shadow overflow-hidden">
                    <div class="p-6 border-b border-white/10 flex justify-between items-center">
                        <div>
                            <h3 class="text-white font-bold text-lg">All Platform Users</h3>
                            <p class="text-white/50 text-sm">Total {{ $totalUsers ?? 0 }} users</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-white/50 text-sm">Selected: </span>
                            <span id="selectedCount" class="text-white font-bold">0</span>
                            <button onclick="deleteSelected()" class="ml-4 p-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30">
                                <span class="material-symbols-outlined text-sm">delete</span>
                            </button>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">
                                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                                    </th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">User</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Email</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Email Status</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Account Status</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Joined</th>
                                    <th class="text-left py-3 px-6 text-white/70 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTable">
                                @if(isset($users) && $users->count() > 0)
                                    @foreach($users as $user)
                                    @php
                                        // SAFE VARIABLES - NO ERRORS
                                        $userName = $user->name ?? 'No Name';
                                        $userEmail = $user->email ?? 'No Email';
                                        $userId = $user->id ?? '';
                                        $userPhone = $user->phone ?? '';
                                        
                                        // Check if email is verified
                                        $isEmailVerified = !empty($user->email_verified_at);
                                        
                                        // Check account status (frozen or active)
                                        $isFrozen = false;
                                        if (isset($user->is_frozen)) {
                                            $isFrozen = (bool)$user->is_frozen;
                                        }
                                        
                                        // Handle created_at date SAFELY
                                        $createdDate = 'N/A';
                                        $createdAgo = '';
                                        
                                        if (isset($user->created_at)) {
                                            if (is_object($user->created_at)) {
                                                // It's a Carbon instance
                                                $createdDate = $user->created_at->format('M d, Y');
                                                $createdAgo = $user->created_at->diffForHumans();
                                            } elseif (is_string($user->created_at)) {
                                                // It's a string, convert to Carbon
                                                try {
                                                    $carbonDate = \Carbon\Carbon::parse($user->created_at);
                                                    $createdDate = $carbonDate->format('M d, Y');
                                                    $createdAgo = $carbonDate->diffForHumans();
                                                } catch (\Exception $e) {
                                                    $createdDate = date('M d, Y', strtotime($user->created_at));
                                                }
                                            }
                                        }
                                    @endphp
                                    
                                    <tr class="border-b border-white/5 hover:bg-white/5 user-row" 
                                        data-user-id="{{ $userId }}" 
                                        data-email="{{ strtolower($userEmail) }}"
                                        data-name="{{ strtolower($userName) }}">
                                        <td class="py-3 px-6">
                                            <input type="checkbox" class="user-checkbox" value="{{ $userId }}" onchange="updateSelectedCount()">
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-center bg-cover" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode($userName) }}&color=FFFFFF&background=3B82F6");'></div>
                                                <div>
                                                    <p class="text-white font-medium">{{ $userName }}</p>
                                                    <p class="text-white/50 text-xs">ID: {{ substr($userId, 0, 8) }}...</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6">
                                            <p class="text-white text-sm">{{ $userEmail }}</p>
                                            @if(!empty($userPhone))
                                            <p class="text-white/50 text-xs">{{ $userPhone }}</p>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6">
                                            @if($isEmailVerified)
                                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-400 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs">check_circle</span>
                                                Verified
                                            </span>
                                            @else
                                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-yellow-500/20 text-yellow-400 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs">pending</span>
                                                Unverified
                                            </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6">
                                            @if($isFrozen)
                                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-500/20 text-red-400 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs">block</span>
                                                Frozen
                                            </span>
                                            @else
                                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-400 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs">check_circle</span>
                                                Active
                                            </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6">
                                            <p class="text-white text-sm">{{ $createdDate }}</p>
                                            @if(!empty($createdAgo))
                                            <p class="text-white/50 text-xs">{{ $createdAgo }}</p>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-2">
                                                <button onclick="viewUser('{{ $userId }}')" class="p-2 hover:bg-white/10 rounded" title="View Details">
                                                    <span class="material-symbols-outlined text-white/70">visibility</span>
                                                </button>
                                                <button onclick="editUser('{{ $userId }}')" class="p-2 hover:bg-white/10 rounded" title="Edit User">
                                                    <span class="material-symbols-outlined text-blue-400">edit</span>
                                                </button>
                                                @if($isFrozen)
                                                <button onclick="unfreezeUser('{{ $userId }}')" class="p-2 hover:bg-white/10 rounded" title="Unfreeze User">
                                                    <span class="material-symbols-outlined text-green-400">lock_open</span>
                                                </button>
                                                @else
                                                <button onclick="freezeUser('{{ $userId }}')" class="p-2 hover:bg-white/10 rounded" title="Freeze User">
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
                                        <span class="material-symbols-outlined text-4xl mb-2">group_off</span>
                                        <p class="text-lg">No users found</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
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
                                <a href="{{ $url }}" class="px-3 py-1 rounded-lg {{ $users->currentPage() == $page ? 'bg-purple-500 text-white' : 'bg-white/10 text-white hover:bg-white/20' }}">
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

    <!-- Bulk Actions Modal -->
    <div id="bulkActionsModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
        <div class="glass-card rounded-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-white font-bold text-lg mb-4">Bulk Actions</h3>
            <div class="space-y-3">
                <button onclick="bulkDelete()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-red-500/20 hover:bg-red-500/30 text-red-400">
                    <span class="material-symbols-outlined">delete</span>
                    <span>Delete Selected Users</span>
                </button>
                <button onclick="bulkSendEmail()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-blue-500/20 hover:bg-blue-500/30 text-blue-400">
                    <span class="material-symbols-outlined">send</span>
                    <span>Send Email to Selected</span>
                </button>
                <button onclick="bulkVerifyEmail()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-green-500/20 hover:bg-green-500/30 text-green-400">
                    <span class="material-symbols-outlined">verified</span>
                    <span>Verify Email for Selected</span>
                </button>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button onclick="hideBulkActions()" class="px-4 py-2 rounded-lg bg-white/10 text-white hover:bg-white/20">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        function searchUsers() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.user-row');
            
            rows.forEach(row => {
                const email = row.getAttribute('data-email') || '';
                const name = row.getAttribute('data-name') || '';
                
                if (email.includes(searchTerm) || name.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Selection functionality
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const selected = document.querySelectorAll('.user-checkbox:checked').length;
            document.getElementById('selectedCount').textContent = selected;
        }

        function deleteSelected() {
            const selected = Array.from(document.querySelectorAll('.user-checkbox:checked'))
                .map(cb => cb.value);
            
            if (selected.length === 0) {
                alert('Please select at least one user');
                return;
            }
            
            if (confirm(`Delete ${selected.length} selected user(s)? This action cannot be undone.`)) {
                fetch('/admin/users/bulk-delete', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ user_ids: selected })
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error'));
                    }
                }).catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        }

        // Bulk actions modal
        function showBulkActions() {
            document.getElementById('bulkActionsModal').classList.remove('hidden');
            document.getElementById('bulkActionsModal').classList.add('flex');
        }

        function hideBulkActions() {
            document.getElementById('bulkActionsModal').classList.add('hidden');
            document.getElementById('bulkActionsModal').classList.remove('flex');
        }

        function bulkDelete() {
            deleteSelected();
        }

        function bulkVerifyEmail() {
            const selected = getSelectedUsers();
            if (selected.length === 0) {
                alert('Please select users first');
                return;
            }
            
            if (confirm(`Verify email for ${selected.length} selected user(s)?`)) {
                fetch('/admin/users/bulk-verify-email', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ user_ids: selected })
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }

        function getSelectedUsers() {
            return Array.from(document.querySelectorAll('.user-checkbox:checked'))
                .map(cb => cb.value);
        }

        // Export functionality
        function exportUsers() {
            window.location.href = '/admin/users/export';
        }

        // User actions
        function viewUser(id) {
            window.location.href = `/admin/users/${id}`;
        }

        function editUser(id) {
            window.location.href = `/admin/users/${id}/edit`;
        }

        function freezeUser(id) {
            if(confirm('Freeze this user?')) {
                fetch(`/admin/users/${id}/freeze`, {
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

        function unfreezeUser(id) {
            if(confirm('Unfreeze this user?')) {
                fetch(`/admin/users/${id}/unfreeze`, {
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

        // Bulk email
        function bulkSendEmail() {
            const selected = getSelectedUsers();
            if (selected.length === 0) {
                alert('Please select users first');
                return;
            }
            
            const subject = prompt('Enter email subject:');
            if (!subject) return;
            
            const message = prompt('Enter email message:');
            if (!message) return;
            
            if (confirm(`Send email to ${selected.length} users?`)) {
                fetch('/admin/users/bulk-email', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        user_ids: selected,
                        subject: subject,
                        message: message
                    })
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Emails sent successfully!');
                    }
                });
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('✅ Admin Users Management Loaded Successfully');
            updateSelectedCount();
        });
    </script>
</body>
</html>
