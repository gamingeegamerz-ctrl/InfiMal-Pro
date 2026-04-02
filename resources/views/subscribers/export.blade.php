<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Export Subscribers - InfiMal</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
            <!-- SIDEBAR -->
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
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ route('dashboard') }}">
                            <span class="material-symbols-outlined">dashboard</span>
                            <p class="text-sm font-medium leading-normal">Dashboard</p>
                        </a>
                        
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-blue-500/20 text-white" href="{{ route('subscribers.index') }}">
                            <span class="material-symbols-outlined">group</span>
                            <p class="text-sm font-medium leading-normal">Subscribers</p>
                        </a>
                        
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ route('lists.index') }}">
                            <span class="material-symbols-outlined">list_alt</span>
                            <p class="text-sm font-medium leading-normal">Lists</p>
                        </a>
                        
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ route('campaigns.index') }}">
                            <span class="material-symbols-outlined">campaign</span>
                            <p class="text-sm font-medium leading-normal">Campaigns</p>
                        </a>
                        
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ route('messages.index') }}">
                            <span class="material-symbols-outlined">chat</span>
                            <p class="text-sm font-medium leading-normal">Messages</p>
                        </a>
                        
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ route('smtp.index') }}">
                            <span class="material-symbols-outlined">dns</span>
                            <p class="text-sm font-medium leading-normal">SMTP Settings</p>
                        </a>
                        
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ route('billing.index') }}">
                            <span class="material-symbols-outlined">receipt_long</span>
                            <p class="text-sm font-medium leading-normal">Billing</p>
                        </a>
                        
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ route('profile') }}">
                            <span class="material-symbols-outlined text-white">person</span>
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
                                    <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-0 border-none bg-transparent h-full placeholder:text-white/60 px-2 text-base font-normal leading-normal" placeholder="Search export options..." value=""/>
                                </div>
                            </label>
                        </div>
                        <div class="flex flex-1 justify-end gap-4 items-center">
                            <button onclick="window.location.href='{{ route('subscribers.index') }}'" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-blue-500 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-blue-500/90 transition-colors">
                                <span class="truncate">Back</span>
                            </button>
                            <button class="flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-white/10 text-white/80 gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5 hover:bg-white/20 transition-colors">
                                <span class="material-symbols-outlined">notifications</span>
                            </button>
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://ui-avatars.com/api/?name=Admin+User&color=FFFFFF&background=3B82F6");'></div>
                        </div>
                    </div>
                </header>

                <!-- Export Content -->
                <div class="my-8">
                    <h1 class="text-white tracking-light text-3xl font-bold leading-tight">Export Subscribers</h1>
                    <p class="text-white/70 text-base font-normal leading-normal pt-2">Export your subscribers to CSV file</p>
                </div>

                <!-- Export Options -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Export Settings -->
                    <div class="glass-card rounded-xl p-6 lg:col-span-2">
                        <h2 class="text-white font-bold text-xl mb-6">Export Settings</h2>
                        
                        <form id="exportForm" action="{{ route('subscribers.export') }}" method="GET" class="space-y-8">
                            @csrf
                            
                            <!-- List Selection -->
                            <div>
                                <h3 class="text-white font-bold text-lg mb-4">Select List</h3>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="md:col-span-2">
                                            <label class="block text-white/80 text-sm font-medium mb-2">Choose List *</label>
                                            <select name="list_id" id="listSelect" class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <option value="all" selected>All Subscribers</option>
                                                <option value="1">Main List (2,500 subscribers)</option>
                                                <option value="2">Premium Users (500 subscribers)</option>
                                                <option value="3">Free Trial Users (1,200 subscribers)</option>
                                                <option value="4">Newsletter Subscribers (3,800 subscribers)</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Status Filter -->
                                    <div>
                                        <label class="block text-white/80 text-sm font-medium mb-2">Subscriber Status</label>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <label class="flex items-center space-x-3">
                                                <input type="checkbox" name="status[]" value="active" class="w-4 h-4 text-blue-600 bg-white/10 border-white/20 rounded focus:ring-blue-500" checked>
                                                <span class="text-white text-sm">Active</span>
                                            </label>
                                            <label class="flex items-center space-x-3">
                                                <input type="checkbox" name="status[]" value="unsubscribed" class="w-4 h-4 text-blue-600 bg-white/10 border-white/20 rounded focus:ring-blue-500">
                                                <span class="text-white text-sm">Unsubscribed</span>
                                            </label>
                                            <label class="flex items-center space-x-3">
                                                <input type="checkbox" name="status[]" value="bounced" class="w-4 h-4 text-blue-600 bg-white/10 border-white/20 rounded focus:ring-blue-500">
                                                <span class="text-white text-sm">Bounced</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Fields Selection -->
                            <div>
                                <h3 class="text-white font-bold text-lg mb-4">Select Fields to Export</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="fields[]" value="email" class="w-4 h-4 text-blue-600 bg-white/10 border-white/20 rounded focus:ring-blue-500" checked>
                                        <span class="text-white text-sm">Email Address</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="fields[]" value="first_name" class="w-4 h-4 text-blue-600 bg-white/10 border-white/20 rounded focus:ring-blue-500" checked>
                                        <span class="text-white text-sm">First Name</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="fields[]" value="last_name" class="w-4 h-4 text-blue-600 bg-white/10 border-white/20 rounded focus:ring-blue-500" checked>
                                        <span class="text-white text-sm">Last Name</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="fields[]" value="status" class="w-4 h-4 text-blue-600 bg-white/10 border-white/20 rounded focus:ring-blue-500" checked>
                                        <span class="text-white text-sm">Status</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="fields[]" value="created_at" class="w-4 h-4 text-blue-600 bg-white/10 border-white/20 rounded focus:ring-blue-500" checked>
                                        <span class="text-white text-sm">Created Date</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="fields[]" value="updated_at" class="w-4 h-4 text-blue-600 bg-white/10 border-white/20 rounded focus:ring-blue-500">
                                        <span class="text-white text-sm">Updated Date</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="fields[]" value="confirmed_at" class="w-4 h-4 text-blue-600 bg-white/10 border-white/20 rounded focus:ring-blue-500">
                                        <span class="text-white text-sm">Confirmed Date</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="fields[]" value="unsubscribed_at" class="w-4 h-4 text-blue-600 bg-white/10 border-white/20 rounded focus:ring-blue-500">
                                        <span class="text-white text-sm">Unsubscribed Date</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Export Format -->
                            <div>
                                <h3 class="text-white font-bold text-lg mb-4">Export Format</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <label class="flex items-center space-x-3 p-4 bg-white/10 rounded-lg cursor-pointer hover:bg-white/20">
                                        <input type="radio" name="format" value="csv" class="w-4 h-4 text-blue-600 bg-white/10 border-white/20" checked>
                                        <div>
                                            <span class="text-white font-medium">CSV</span>
                                            <p class="text-white/70 text-sm">Comma separated values</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center space-x-3 p-4 bg-white/10 rounded-lg cursor-pointer hover:bg-white/20">
                                        <input type="radio" name="format" value="excel" class="w-4 h-4 text-blue-600 bg-white/10 border-white/20">
                                        <div>
                                            <span class="text-white font-medium">Excel</span>
                                            <p class="text-white/70 text-sm">Microsoft Excel format</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center space-x-3 p-4 bg-white/10 rounded-lg cursor-pointer hover:bg-white/20">
                                        <input type="radio" name="format" value="json" class="w-4 h-4 text-blue-600 bg-white/10 border-white/20">
                                        <div>
                                            <span class="text-white font-medium">JSON</span>
                                            <p class="text-white/70 text-sm">JavaScript Object Notation</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Export Actions -->
                            <div class="flex justify-between pt-6 border-t border-white/10">
                                <button type="button" onclick="window.location.href='{{ route('subscribers.index') }}'" class="px-6 py-3 bg-white/10 text-white rounded-lg font-bold hover:bg-white/20 transition-colors">
                                    Cancel
                                </button>
                                <div class="flex gap-3">
                                    <button type="button" onclick="previewExport()" class="px-6 py-3 bg-yellow-500 text-white rounded-lg font-bold hover:bg-yellow-600 transition-colors">
                                        Preview Export
                                    </button>
                                    <button type="submit" class="px-6 py-3 bg-green-500 text-white rounded-lg font-bold hover:bg-green-600 transition-colors">
                                        <span class="material-symbols-outlined align-middle">download</span>
                                        Export Now
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Export Stats -->
                    <div class="space-y-6">
                        <!-- Stats Card -->
                        <div class="glass-card rounded-xl p-6">
                            <h3 class="text-white font-bold text-lg mb-4">Export Statistics</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-white/70">Total Subscribers</span>
                                    <span class="text-white font-bold">8,000</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-white/70">Active</span>
                                    <span class="text-white font-bold">7,200</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-white/70">Unsubscribed</span>
                                    <span class="text-white font-bold">500</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-white/70">Bounced</span>
                                    <span class="text-white font-bold">300</span>
                                </div>
                                <div class="pt-4 border-t border-white/10">
                                    <div class="flex items-center justify-between">
                                        <span class="text-white/70">Estimated File Size</span>
                                        <span class="text-white font-bold" id="fileSize">~2.5 MB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Export -->
                        <div class="glass-card rounded-xl p-6">
                            <h3 class="text-white font-bold text-lg mb-4">Quick Export</h3>
                            <div class="space-y-3">
                                <a href="{{ route('subscribers.export') }}?list_id=all&format=csv&fields[]=email&fields[]=first_name&fields[]=last_name" class="w-full flex items-center gap-3 p-3 rounded-lg bg-white/10 hover:bg-white/20 transition-colors">
                                    <span class="material-symbols-outlined text-green-500">file_download</span>
                                    <div>
                                        <span class="text-white text-sm font-medium">Basic Info</span>
                                        <p class="text-white/70 text-xs">Email, First & Last Name</p>
                                    </div>
                                </a>
                                <a href="{{ route('subscribers.export') }}?list_id=all&format=csv&fields[]=email&fields[]=status&fields[]=created_at" class="w-full flex items-center gap-3 p-3 rounded-lg bg-white/10 hover:bg-white/20 transition-colors">
                                    <span class="material-symbols-outlined text-blue-500">file_download</span>
                                    <div>
                                        <span class="text-white text-sm font-medium">Status Report</span>
                                        <p class="text-white/70 text-xs">Email with status info</p>
                                    </div>
                                </a>
                                <a href="{{ route('subscribers.export') }}?list_id=all&format=csv&fields[]=email&fields[]=first_name&fields[]=last_name&fields[]=status&fields[]=created_at&fields[]=unsubscribed_at" class="w-full flex items-center gap-3 p-3 rounded-lg bg-white/10 hover:bg-white/20 transition-colors">
                                    <span class="material-symbols-outlined text-purple-500">file_download</span>
                                    <div>
                                        <span class="text-white text-sm font-medium">Full Export</span>
                                        <p class="text-white/70 text-xs">All subscriber data</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Recent Exports -->
                        <div class="glass-card rounded-xl p-6">
                            <h3 class="text-white font-bold text-lg mb-4">Recent Exports</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-2">
                                    <div>
                                        <span class="text-white text-sm">subscribers_2024_01_15.csv</span>
                                        <p class="text-white/50 text-xs">2 days ago • 1.8 MB</p>
                                    </div>
                                    <a href="#" class="text-blue-400 hover:text-blue-300">
                                        <span class="material-symbols-outlined text-sm">download</span>
                                    </a>
                                </div>
                                <div class="flex items-center justify-between p-2">
                                    <div>
                                        <span class="text-white text-sm">premium_users.json</span>
                                        <p class="text-white/50 text-xs">1 week ago • 450 KB</p>
                                    </div>
                                    <a href="#" class="text-blue-400 hover:text-blue-300">
                                        <span class="material-symbols-outlined text-sm">download</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 hidden">
        <div class="glass-card rounded-2xl p-8 max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-white text-2xl font-bold">Export Preview</h3>
                <button onclick="closePreviewModal()" class="p-2 rounded-lg hover:bg-white/10">
                    <span class="material-symbols-outlined text-white">close</span>
                </button>
            </div>
            
            <div class="bg-white/10 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-white font-bold">Export Details</h4>
                        <p class="text-white/70 text-sm" id="previewDetails">All subscribers • CSV format</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs bg-blue-500/20 text-blue-400">
                        <span id="previewCount">8,000</span> records
                    </span>
                </div>
                
                <div class="bg-black/30 rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-white text-sm">
                            <thead class="bg-white/10">
                                <tr>
                                    <th class="px-4 py-3 text-left">Email</th>
                                    <th class="px-4 py-3 text-left">First Name</th>
                                    <th class="px-4 py-3 text-left">Last Name</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                <tr>
                                    <td class="px-4 py-3">john.doe@example.com</td>
                                    <td class="px-4 py-3">John</td>
                                    <td class="px-4 py-3">Doe</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-400">Active</span></td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3">jane.smith@example.com</td>
                                    <td class="px-4 py-3">Jane</td>
                                    <td class="px-4 py-3">Smith</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-400">Active</span></td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3">mike.johnson@example.com</td>
                                    <td class="px-4 py-3">Mike</td>
                                    <td class="px-4 py-3">Johnson</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs bg-red-500/20 text-red-400">Unsubscribed</span></td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3">sarah.williams@example.com</td>
                                    <td class="px-4 py-3">Sarah</td>
                                    <td class="px-4 py-3">Williams</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-400">Active</span></td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3">david.brown@example.com</td>
                                    <td class="px-4 py-3">David</td>
                                    <td class="px-4 py-3">Brown</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs bg-yellow-500/20 text-yellow-400">Bounced</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <p class="text-white/50 text-sm mt-4">Showing 5 of <span id="totalRecords">8,000</span> records</p>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button onclick="closePreviewModal()" class="px-6 py-3 bg-white/10 text-white rounded-lg font-bold hover:bg-white/20 transition-colors">
                    Cancel
                </button>
                <button onclick="submitExport()" class="px-6 py-3 bg-green-500 text-white rounded-lg font-bold hover:bg-green-600 transition-colors">
                    Confirm Export
                </button>
            </div>
        </div>
    </div>

    <script>
        // Preview export
        function previewExport() {
            const listSelect = document.getElementById('listSelect');
            const selectedList = listSelect.options[listSelect.selectedIndex].text;
            const format = document.querySelector('input[name="format"]:checked').value;
            
            // Update preview details
            document.getElementById('previewDetails').textContent = 
                `${selectedList} • ${format.toUpperCase()} format`;
            
            // Calculate estimated count based on selection
            let estimatedCount = 8000; // Default total
            if (listSelect.value !== 'all') {
                estimatedCount = parseInt(selectedList.match(/\((\d+)/)[1]) || 2500;
            }
            
            document.getElementById('previewCount').textContent = estimatedCount.toLocaleString();
            document.getElementById('totalRecords').textContent = estimatedCount.toLocaleString();
            
            // Show preview modal
            document.getElementById('previewModal').classList.remove('hidden');
        }
        
        // Close preview modal
        function closePreviewModal() {
            document.getElementById('previewModal').classList.add('hidden');
        }
        
        // Submit export
        function submitExport() {
            const form = document.getElementById('exportForm');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            // Show loading state
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Exporting...';
            submitBtn.disabled = true;
            
            // Submit form
            form.submit();
            
            // Reset button after 3 seconds (if page doesn't redirect)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                closePreviewModal();
            }, 3000);
        }
        
        // Update file size estimate
        function updateFileSize() {
            const listSelect = document.getElementById('listSelect');
            const fields = document.querySelectorAll('input[name="fields[]"]:checked').length;
            
            let estimatedCount = 8000; // Default total
            if (listSelect.value !== 'all') {
                const selectedList = listSelect.options[listSelect.selectedIndex].text;
                estimatedCount = parseInt(selectedList.match(/\((\d+)/)[1]) || 2500;
            }
            
            // Rough calculation: ~300 bytes per record per field
            const sizeBytes = estimatedCount * fields * 300;
            let sizeText;
            
            if (sizeBytes < 1024 * 1024) { // Less than 1MB
                sizeText = `~${Math.round(sizeBytes / 1024)} KB`;
            } else {
                sizeText = `~${(sizeBytes / (1024 * 1024)).toFixed(1)} MB`;
            }
            
            document.getElementById('fileSize').textContent = sizeText;
        }
        
        // Initialize event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Update file size when selections change
            document.querySelectorAll('#exportForm input, #exportForm select').forEach(element => {
                element.addEventListener('change', updateFileSize);
            });
            
            // Initial calculation
            updateFileSize();
            
            // Quick export links
            document.querySelectorAll('.quick-export').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    window.location.href = url;
                });
            });
        });
        
        // Simple export simulation
        document.getElementById('exportForm').addEventListener('submit', function(e) {
            // Don't prevent default - let it submit
            // This is just for simulation
            console.log('Export form submitted');
        });
    </script>
</body>
</html>
