<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Profile - InfiMal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        'display': ['Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
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
        /* Theme Toggle - FIXED */
        .theme-toggle-container {
            position: relative;
            width: 60px;
            height: 32px;
            border-radius: 16px;
            cursor: pointer;
            background: #e2e8f0;
        }
        .dark .theme-toggle-container {
            background: #475569;
        }
        .theme-toggle-handle {
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
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .dark .theme-toggle-handle {
            transform: translateX(28px);
            background: #fbbf24;
        }
        .theme-toggle-sun {
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            opacity: 0.7;
        }
        .dark .theme-toggle-sun {
            opacity: 0.3;
        }
        .theme-toggle-moon {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            opacity: 0.3;
        }
        .dark .theme-toggle-moon {
            opacity: 0.7;
        }
        /* Modal Styles */
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
        /* Toast Styles */
        .toast {
            animation: slideInRight 0.3s ease, fadeOut 0.3s ease 2.7s;
        }
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
        /* Loading Spinner */
        .loading-spinner {
            border: 3px solid rgba(59, 130, 246, 0.1);
            border-radius: 50%;
            border-top: 3px solid #3b82f6;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        /* Status Badges */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success { 
            background: rgba(34, 197, 94, 0.2); 
            color: #22c55e; 
        }
        .dark .badge-success { 
            background: rgba(34, 197, 94, 0.3); 
            color: #4ade80; 
        }
        .badge-warning { 
            background: rgba(245, 158, 11, 0.2); 
            color: #f59e0b; 
        }
        .dark .badge-warning { 
            background: rgba(245, 158, 11, 0.3); 
            color: #fbbf24; 
        }
        .badge-danger { 
            background: rgba(239, 68, 68, 0.2); 
            color: #ef4444; 
        }
        .dark .badge-danger { 
            background: rgba(239, 68, 68, 0.3); 
            color: #f87171; 
        }
        /* Form Styles */
        .breeze-input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            background: white;
            color: #111827;
            transition: all 0.2s;
        }
        .dark .breeze-input {
            border-color: #4b5563;
            background: #1f2937;
            color: #f9fafb;
        }
        .breeze-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .dark .breeze-input:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.2);
        }
        .breeze-button {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 600;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }
        .breeze-button-primary {
            background: #3b82f6;
            color: white;
        }
        .breeze-button-primary:hover {
            background: #2563eb;
        }
        .breeze-button-danger {
            background: #ef4444;
            color: white;
        }
        .breeze-button-danger:hover {
            background: #dc2626;
        }
        .breeze-button-secondary {
            background: #6b7280;
            color: white;
        }
        .breeze-button-secondary:hover {
            background: #4b5563;
        }
        /* Checkbox Toggle */
        .toggle-checkbox {
            display: none;
        }
        .toggle-label {
            display: inline-flex;
            align-items: center;
            cursor: pointer;
        }
        .toggle-switch {
            width: 44px;
            height: 24px;
            background: #d1d5db;
            border-radius: 12px;
            position: relative;
            transition: background 0.3s;
        }
        .dark .toggle-switch {
            background: #4b5563;
        }
        .toggle-switch::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            top: 2px;
            left: 2px;
            transition: transform 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .toggle-checkbox:checked + .toggle-label .toggle-switch {
            background: #3b82f6;
        }
        .dark .toggle-checkbox:checked + .toggle-label .toggle-switch {
            background: #60a5fa;
        }
        .toggle-checkbox:checked + .toggle-label .toggle-switch::after {
            transform: translateX(20px);
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
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="<?php echo e(url('/dashboard')); ?>">
                        <span class="material-symbols-outlined text-xl">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="<?php echo e(url('/subscribers')); ?>">
                        <span class="material-symbols-outlined text-xl">group</span>
                        <span>Subscribers</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="<?php echo e(url('/lists')); ?>">
                        <span class="material-symbols-outlined text-xl">list_alt</span>
                        <span>Lists</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="<?php echo e(url('/campaigns')); ?>">
                        <span class="material-symbols-outlined text-xl">campaign</span>
                        <span>Campaigns</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="<?php echo e(url('/messages')); ?>">
                        <span class="material-symbols-outlined text-xl">chat</span>
                        <span>Messages</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="<?php echo e(url('/smtp')); ?>">
                        <span class="material-symbols-outlined text-xl">dns</span>
                        <span>SMTP Settings</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="<?php echo e(url('/billing')); ?>">
                        <span class="material-symbols-outlined text-xl">receipt_long</span>
                        <span>Billing</span>
                    </a>
                    <a class="nav-link active flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 dark:text-white font-medium text-sm" href="<?php echo e(url('/profile')); ?>">
                        <span class="material-symbols-outlined text-xl">person</span>
                        <span>Profile</span>
                    </a>
                </nav>
                
                <!-- Dark Mode Toggle -->
                <div class="pt-4 border-t border-gray-200 dark:border-slate-700 flex items-center justify-between">
                    <div class="flex items-center gap-3 px-3 py-2.5">
                        <span class="material-symbols-outlined text-xl text-gray-600 dark:text-slate-400">dark_mode</span>
                        <span class="text-gray-600 dark:text-slate-400 font-medium text-sm">Theme</span>
                    </div>
                    <div class="theme-toggle-container" id="themeToggle">
                        <div class="theme-toggle-sun">☀️</div>
                        <div class="theme-toggle-moon">🌙</div>
                        <div class="theme-toggle-handle"></div>
                    </div>
                </div>
                
                <!-- Logout -->
                <div class="pt-4 border-t border-gray-200 dark:border-slate-700">
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
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
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Profile Settings</h2>
                            <p class="text-gray-600 dark:text-slate-400 text-sm mt-1">Manage your account information and preferences</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="refreshStats()" class="border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 px-6 py-2 rounded-lg font-semibold text-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition-all duration-300">
                                <span class="material-symbols-outlined align-middle mr-2 text-sm">refresh</span>
                                Refresh Stats
                            </button>
                            <a href="<?php echo e(url('/dashboard')); ?>" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg font-semibold text-sm hover-glow transition-all duration-300">
                                <span class="material-symbols-outlined align-middle mr-2 text-sm">dashboard</span>
                                Dashboard
                            </a>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold cursor-pointer" id="userAvatarBtn" onclick="toggleUserMenu()">
                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

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
                            <div class="flex items-center gap-3 mb-4">
                                <span class="status-badge badge-success">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paymentStatus === 'paid'): ?>
                                        <span class="material-symbols-outlined align-middle text-sm">verified</span> Active Account
                                    <?php elseif($paymentStatus === 'pending'): ?>
                                        <span class="material-symbols-outlined align-middle text-sm">pending</span> Payment Pending
                                    <?php else: ?>
                                        <span class="material-symbols-outlined align-middle text-sm">error</span> Free Account
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </span>
                                <span class="status-badge badge-success">
                                    <span class="material-symbols-outlined align-middle text-sm">check_circle</span> Verified
                                </span>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Welcome, <?php echo e($user->name); ?>! 👋</h1>
                            <p class="text-gray-600 dark:text-slate-300 mb-4">Manage your InfiMal account settings and preferences</p>
                            <div class="flex items-center gap-6">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500 text-xl">mail</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        <?php echo e($user->email); ?>

                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-500 text-xl">calendar_month</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        Member since <?php echo e($user->created_at->format('M d, Y')); ?>

                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-purple-500 text-xl">schedule</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        <?php echo e($stats['account_age'] ?? 'N/A'); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden lg:block">
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 rounded-2xl flex items-center justify-center">
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold">
                                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Campaigns -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Total Campaigns</h3>
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">campaign</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2"><?php echo e($stats['total_campaigns'] ?? 0); ?></p>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-500 text-sm">trending_up</span>
                            <span class="text-gray-600 dark:text-slate-400 text-sm">Active campaigns</span>
                        </div>
                    </div>
                    
                    <!-- Subscribers -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Total Subscribers</h3>
                            <div class="p-2 bg-green-100 dark:bg-green-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">group</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2"><?php echo e($stats['total_subscribers'] ?? 0); ?></p>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-500 text-sm">group_add</span>
                            <span class="text-gray-600 dark:text-slate-400 text-sm">Active contacts</span>
                        </div>
                    </div>
                    
                    <!-- Emails Sent -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Emails Sent</h3>
                            <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">send</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2"><?php echo e(number_format($stats['total_sent'] ?? 0)); ?></p>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-500 text-sm">rocket_launch</span>
                            <span class="text-gray-600 dark:text-slate-400 text-sm">All time total</span>
                        </div>
                    </div>
                    
                    <!-- Performance -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover-glow transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-600 dark:text-slate-300 font-semibold text-sm">Account Status</h3>
                            <div class="p-2 bg-orange-100 dark:bg-orange-900/50 rounded-lg">
                                <span class="material-symbols-outlined text-orange-600 dark:text-orange-400">verified_user</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paymentStatus === 'paid'): ?>
                                Pro
                            <?php else: ?>
                                Free
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined <?php echo e($paymentStatus === 'paid' ? 'text-green-500' : 'text-yellow-500'); ?> text-sm">
                                <?php echo e($paymentStatus === 'paid' ? 'verified' : 'warning'); ?>

                            </span>
                            <span class="text-gray-600 dark:text-slate-400 text-sm">
                                <?php echo e($paymentStatus === 'paid' ? 'Active subscription' : 'Limited features'); ?>

                            </span>
                        </div>
                    </div>
                </div>

                <!-- Main Profile Content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Personal Information -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Profile Information Form -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                    <?php echo e(__('Profile Information')); ?>

                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-6">
                                    <?php echo e(__("Update your account's profile information and email address.")); ?>

                                </p>
                            </header>

                            <form id="send-verification" method="post" action="<?php echo e(route('verification.send')); ?>">
                                <?php echo csrf_field(); ?>
                            </form>

                            <form method="post" action="<?php echo e(route('profile.update')); ?>" class="space-y-6">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('patch'); ?>

                                <!-- Profile Picture -->
                                <div class="flex items-center gap-6 mb-6">
                                    <div class="relative">
                                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold">
                                            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 dark:text-white text-lg font-bold"><?php echo e($user->name); ?></h3>
                                        <p class="text-gray-600 dark:text-slate-400"><?php echo e($user->email); ?></p>
                                        <p class="text-gray-500 dark:text-slate-500 text-sm mt-1">
                                            Member since <?php echo e($user->created_at->format('M d, Y')); ?>

                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('Name')); ?></label>
                                    <input id="name" name="name" type="text" class="breeze-input" value="<?php echo e(old('name', $user->name)); ?>" required autofocus autocomplete="name" />
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('Email')); ?></label>
                                    <input id="email" name="email" type="email" class="breeze-input" value="<?php echo e(old('email', $user->email)); ?>" required autocomplete="username" />
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail()): ?>
                                        <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                            <p class="text-sm text-gray-800 dark:text-gray-200">
                                                <?php echo e(__('Your email address is unverified.')); ?>


                                                <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none">
                                                    <?php echo e(__('Click here to re-send the verification email.')); ?>

                                                </button>
                                            </p>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status') === 'verification-link-sent'): ?>
                                                <p class="mt-2 text-sm text-green-600 dark:text-green-400">
                                                    <?php echo e(__('A new verification link has been sent to your email address.')); ?>

                                                </p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <!-- Additional Fields -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                                        <input id="phone" name="phone" type="tel" class="breeze-input" value="<?php echo e(old('phone', $user->phone ?? '')); ?>" placeholder="+1 (555) 123-4567" />
                                    </div>
                                    
                                    <div>
                                        <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Timezone</label>
                                        <select id="timezone" name="timezone" class="breeze-input">
                                            <option value="UTC" <?php echo e(($user->timezone ?? 'UTC') === 'UTC' ? 'selected' : ''); ?>>UTC</option>
                                            <option value="Asia/Kolkata" <?php echo e(($user->timezone ?? 'UTC') === 'Asia/Kolkata' ? 'selected' : ''); ?>>India (IST)</option>
                                            <option value="America/New_York" <?php echo e(($user->timezone ?? 'UTC') === 'America/New_York' ? 'selected' : ''); ?>>New York (EST)</option>
                                            <option value="America/Los_Angeles" <?php echo e(($user->timezone ?? 'UTC') === 'America/Los_Angeles' ? 'selected' : ''); ?>>Los Angeles (PST)</option>
                                            <option value="Europe/London" <?php echo e(($user->timezone ?? 'UTC') === 'Europe/London' ? 'selected' : ''); ?>>London (GMT)</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bio / Description</label>
                                    <textarea id="bio" name="bio" rows="3" class="breeze-input" placeholder="Tell us about yourself..."><?php echo e(old('bio', $user->bio ?? '')); ?></textarea>
                                    <p class="text-gray-500 dark:text-slate-500 text-xs mt-1">Maximum 500 characters</p>
                                </div>

                                <div class="flex items-center gap-4 pt-4">
                                    <button type="submit" class="breeze-button breeze-button-primary">
                                        <?php echo e(__('Save')); ?>

                                    </button>

                                    <?php if(session('status') === 'profile-updated'): ?>
                                        <p id="profile-success-message" class="text-sm text-green-600 dark:text-green-400">
                                            <?php echo e(__('Saved.')); ?>

                                        </p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </form>
                        </div>

                        <!-- Update Password Form -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                    <?php echo e(__('Update Password')); ?>

                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-6">
                                    <?php echo e(__('Ensure your account is using a long, random password to stay secure.')); ?>

                                </p>
                            </header>

                            <form method="post" action="<?php echo e(route('password.update')); ?>" class="space-y-6">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('put'); ?>

                                <div>
                                    <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('Current Password')); ?></label>
                                    <input id="update_password_current_password" name="current_password" type="password" class="breeze-input" autocomplete="current-password" />
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['current_password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div>
                                    <label for="update_password_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('New Password')); ?></label>
                                    <input id="update_password_password" name="password" type="password" class="breeze-input" autocomplete="new-password" />
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div>
                                    <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('Confirm Password')); ?></label>
                                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="breeze-input" autocomplete="new-password" />
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password_confirmation', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="flex items-center gap-4">
                                    <button type="submit" class="breeze-button breeze-button-primary">
                                        <?php echo e(__('Save')); ?>

                                    </button>

                                    <?php if(session('status') === 'password-updated'): ?>
                                        <p id="password-success-message" class="text-sm text-green-600 dark:text-green-400">
                                            <?php echo e(__('Saved.')); ?>

                                        </p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </form>
                        </div>

                        <!-- Delete Account Form -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    <?php echo e(__('Delete Account')); ?>

                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    <?php echo e(__('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.')); ?>

                                </p>
                            </header>

                            <button
                                onclick="openDeleteModal()"
                                class="mt-6 breeze-button breeze-button-danger"
                            ><?php echo e(__('Delete Account')); ?></button>

                            <!-- Delete Account Modal -->
                            <div id="deleteModal" class="modal-backdrop modal-hidden">
                                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-md shadow-2xl">
                                    <form method="post" action="<?php echo e(route('profile.destroy')); ?>" class="space-y-6">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('delete'); ?>

                                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            <?php echo e(__('Are you sure you want to delete your account?')); ?>

                                        </h2>

                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                            <?php echo e(__('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.')); ?>

                                        </p>

                                        <div>
                                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('Password')); ?></label>
                                            <input
                                                id="password"
                                                name="password"
                                                type="password"
                                                class="breeze-input"
                                                placeholder="<?php echo e(__('Password')); ?>"
                                            />
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password', 'userDeletion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>

                                        <div class="flex justify-end gap-3">
                                            <button 
                                                type="button" 
                                                onclick="closeDeleteModal()"
                                                class="breeze-button breeze-button-secondary"
                                            >
                                                <?php echo e(__('Cancel')); ?>

                                            </button>

                                            <button type="submit" class="breeze-button breeze-button-danger">
                                                <?php echo e(__('Delete Account')); ?>

                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Sidebar -->
                    <div class="space-y-6">
                        <!-- Account Status -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">Account Status</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 dark:text-slate-400">Account Type</span>
                                    <span class="text-gray-900 dark:text-white font-semibold">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paymentStatus === 'paid'): ?>
                                            Pro
                                        <?php else: ?>
                                            Free
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 dark:text-slate-400">Email Verification</span>
                                    <span class="status-badge badge-success">
                                        <span class="material-symbols-outlined align-middle text-sm">check_circle</span> Verified
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 dark:text-slate-400">2FA Status</span>
                                    <span class="text-gray-900 dark:text-white font-semibold">Not Enabled</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 dark:text-slate-400">Last Login</span>
                                    <span class="text-gray-900 dark:text-white font-semibold">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->last_login_at): ?>
                                            <?php echo e($user->last_login_at->diffForHumans()); ?>

                                        <?php else: ?>
                                            Never
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">
                                <h4 class="text-gray-900 dark:text-white font-bold text-sm mb-3">Quick Actions</h4>
                                <div class="space-y-2">
                                    <a href="<?php echo e(url('/billing')); ?>" class="w-full flex items-center gap-3 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-800/30 transition-colors">
                                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">receipt_long</span>
                                        <span class="text-gray-900 dark:text-white text-sm">Billing & Subscription</span>
                                    </a>
                                    <a href="<?php echo e(url('/smtp')); ?>" class="w-full flex items-center gap-3 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-800/30 transition-colors">
                                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">dns</span>
                                        <span class="text-gray-900 dark:text-white text-sm">SMTP Settings</span>
                                    </a>
                                    <button onclick="exportData()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-800/30 transition-colors">
                                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">download</span>
                                        <span class="text-gray-900 dark:text-white text-sm">Export Data</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Preferences -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">Preferences</h3>
                            
                            <form id="preferencesForm" class="space-y-4">
                                <?php echo csrf_field(); ?>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-900 dark:text-white font-medium">Email Notifications</p>
                                        <p class="text-gray-600 dark:text-slate-400 text-sm">Receive email updates</p>
                                    </div>
                                    <input type="checkbox" id="email_notifications" name="email_notifications" class="toggle-checkbox" checked>
                                    <label for="email_notifications" class="toggle-label">
                                        <div class="toggle-switch"></div>
                                    </label>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-900 dark:text-white font-medium">Campaign Updates</p>
                                        <p class="text-gray-600 dark:text-slate-400 text-sm">Campaign status notifications</p>
                                    </div>
                                    <input type="checkbox" id="campaign_notifications" name="campaign_notifications" class="toggle-checkbox" checked>
                                    <label for="campaign_notifications" class="toggle-label">
                                        <div class="toggle-switch"></div>
                                    </label>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-900 dark:text-white font-medium">Weekly Reports</p>
                                        <p class="text-gray-600 dark:text-slate-400 text-sm">Send weekly analytics</p>
                                    </div>
                                    <input type="checkbox" id="weekly_reports" name="weekly_reports" class="toggle-checkbox" checked>
                                    <label for="weekly_reports" class="toggle-label">
                                        <div class="toggle-switch"></div>
                                    </label>
                                </div>
                                
                                <div class="pt-4">
                                    <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Theme Preference</label>
                                    <select name="theme" id="themePreference" class="breeze-input">
                                        <option value="system">System Default</option>
                                        <option value="light">Light Mode</option>
                                        <option value="dark">Dark Mode</option>
                                    </select>
                                </div>
                                
                                <button type="button" onclick="savePreferences()" class="w-full mt-4 breeze-button breeze-button-primary">
                                    Save Preferences
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal-backdrop modal-hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-md shadow-2xl">
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-3xl">check_circle</span>
                </div>
                <h3 class="text-gray-900 dark:text-white text-xl font-bold mb-2" id="successTitle">Success!</h3>
                <p class="text-gray-600 dark:text-slate-300 mb-6" id="successMessage">Your changes have been saved successfully.</p>
                <button onclick="closeSuccessModal()" class="w-full breeze-button breeze-button-primary">
                    Continue
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="loading-spinner"></div>
    </div>

    <script>
        // Dark Mode Functionality - FIXED
        function initDarkMode() {
            // Check localStorage first
            const savedTheme = localStorage.getItem('infimal_theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            // Set initial theme
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
                document.getElementById('themePreference').value = 'dark';
            } else {
                document.documentElement.classList.remove('dark');
                document.getElementById('themePreference').value = 'light';
            }
            
            // Theme toggle click handler
            document.getElementById('themeToggle').addEventListener('click', function() {
                const isDark = document.documentElement.classList.contains('dark');
                
                if (isDark) {
                    // Switch to light mode
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('infimal_theme', 'light');
                    document.getElementById('themePreference').value = 'light';
                } else {
                    // Switch to dark mode
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('infimal_theme', 'dark');
                    document.getElementById('themePreference').value = 'dark';
                }
            });
            
            // Theme preference dropdown handler
            document.getElementById('themePreference').addEventListener('change', function(e) {
                const theme = e.target.value;
                
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('infimal_theme', 'dark');
                } else if (theme === 'light') {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('infimal_theme', 'light');
                } else {
                    // System default - remove localStorage
                    localStorage.removeItem('infimal_theme');
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            });
            
            // Listen for system theme changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                const savedTheme = localStorage.getItem('infimal_theme');
                if (!savedTheme) { // Only change if user hasn't set preference
                    if (e.matches) {
                        document.documentElement.classList.add('dark');
                        document.getElementById('themePreference').value = 'dark';
                    } else {
                        document.documentElement.classList.remove('dark');
                        document.getElementById('themePreference').value = 'light';
                    }
                }
            });
        }

        // Show/Hide Loading
        function showLoading(show = true) {
            const spinner = document.getElementById('loadingSpinner');
            if (show) {
                spinner.classList.remove('hidden');
            } else {
                spinner.classList.add('hidden');
            }
        }

        // Toast Notifications
        function showToast(message, type = 'info') {
            // Remove existing toasts
            document.querySelectorAll('.toast').forEach(toast => toast.remove());
            
            // Create toast element
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : 
                           type === 'error' ? 'bg-red-500' : 
                           type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
            
            toast.className = `toast fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg ${bgColor} text-white flex items-center gap-2`;
            toast.innerHTML = `
                <span class="material-symbols-outlined">
                    ${type === 'success' ? 'check_circle' : 
                      type === 'error' ? 'error' : 
                      type === 'warning' ? 'warning' : 'info'}
                </span>
                <span>${message}</span>
            `;
            
            document.body.appendChild(toast);
            
            // Remove after 3 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 3000);
        }

        // Show Success Modal
        function showSuccess(title, message) {
            document.getElementById('successTitle').textContent = title;
            document.getElementById('successMessage').textContent = message;
            document.getElementById('successModal').classList.remove('modal-hidden');
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.add('modal-hidden');
        }

        // Delete Account Modal Functions
        function openDeleteModal() {
            document.getElementById('deleteModal').classList.remove('modal-hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('modal-hidden');
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            const deleteModal = document.getElementById('deleteModal');
            const successModal = document.getElementById('successModal');
            
            if (deleteModal && !deleteModal.classList.contains('modal-hidden')) {
                if (e.target === deleteModal) {
                    closeDeleteModal();
                }
            }
            
            if (successModal && !successModal.classList.contains('modal-hidden')) {
                if (e.target === successModal) {
                    closeSuccessModal();
                }
            }
        });

        // Save Preferences
        function savePreferences() {
            showLoading(true);
            
            const formData = new FormData(document.getElementById('preferencesForm'));
            const data = {
                email_notifications: document.getElementById('email_notifications').checked,
                campaign_notifications: document.getElementById('campaign_notifications').checked,
                weekly_reports: document.getElementById('weekly_reports').checked,
                theme: document.getElementById('themePreference').value
            };
            
            // Simulate API call
            setTimeout(() => {
                showLoading(false);
                showToast('Preferences saved successfully!', 'success');
                
                // Apply theme if changed
                if (data.theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('infimal_theme', 'dark');
                } else if (data.theme === 'light') {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('infimal_theme', 'light');
                } else {
                    // System default
                    localStorage.removeItem('infimal_theme');
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            }, 1000);
        }

        // Auto-hide success messages
        function autoHideSuccessMessages() {
            const profileMsg = document.getElementById('profile-success-message');
            const passwordMsg = document.getElementById('password-success-message');
            
            if (profileMsg) {
                setTimeout(() => {
                    profileMsg.style.display = 'none';
                }, 2000);
            }
            
            if (passwordMsg) {
                setTimeout(() => {
                    passwordMsg.style.display = 'none';
                }, 2000);
            }
        }

        // Other Functions
        function refreshStats() {
            showLoading(true);
            
            // Simulate API call
            setTimeout(() => {
                showLoading(false);
                showToast('Stats refreshed successfully!', 'success');
            }, 1000);
        }

        function exportData() {
            showLoading(true);
            
            // Simulate export process
            setTimeout(() => {
                showLoading(false);
                showSuccess('Export Started', 'Your data export has been initiated. You will receive an email when it\'s ready to download.');
            }, 2000);
        }

        function toggleUserMenu() {
            showToast('User menu clicked', 'info');
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize dark mode
            initDarkMode();
            
            // Auto-hide success messages
            autoHideSuccessMessages();
            
            console.log('Dark mode initialized. Current theme:', 
                document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        });
    </script>
</body>
</html><?php /**PATH /var/www/infimal--Local/resources/views/profile/index.blade.php ENDPATH**/ ?>