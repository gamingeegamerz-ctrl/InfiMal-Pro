<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Billing - InfiMal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
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
        .theme-toggle {
            position: relative;
            width: 60px;
            height: 32px;
            background: #e2e8f0;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .dark .theme-toggle {
            background: #475569;
        }
        .theme-toggle::before {
            content: '';
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
        }
        .dark .theme-toggle::before {
            transform: translateX(28px);
            background: #fbbf24;
        }
        .theme-toggle::after {
            content: '🌙';
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            opacity: 0.5;
            transition: opacity 0.3s ease;
        }
        .theme-toggle::before {
            content: '☀️';
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f59e0b;
        }
        .dark .theme-toggle::after {
            content: '☀️';
            left: 8px;
            right: auto;
            opacity: 0.7;
        }
        .dark .theme-toggle::before {
            content: '🌙';
            color: #fbbf24;
        }
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
        .plan-badge {
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
        .badge-premium { 
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(59, 130, 246, 0.2)); 
            color: #8b5cf6; 
        }
        .dark .badge-premium { 
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.3), rgba(59, 130, 246, 0.3)); 
            color: #a78bfa; 
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
                    <a class="nav-link active flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 dark:text-white font-medium text-sm" href="<?php echo e(url('/billing')); ?>">
                        <span class="material-symbols-outlined text-xl">receipt_long</span>
                        <span>Billing</span>
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="<?php echo e(url('/profile')); ?>">
                        <span class="material-symbols-outlined text-xl">person</span>
                        <span>Profile</span>
                    </a>
                </nav>
                
                <!-- Dark Mode Toggle -->
                <div class="pt-4 border-t border-gray-200 dark:border-slate-700 logout-btn flex items-center justify-between">
                    <div class="flex items-center gap-3 px-3 py-2.5">
                        <span class="material-symbols-outlined text-xl text-gray-600 dark:text-slate-400">dark_mode</span>
                        <span class="text-gray-600 dark:text-slate-400 font-medium text-sm">Theme</span>
                    </div>
                    <div class="theme-toggle" id="themeToggle"></div>
                </div>
                
                <!-- Logout -->
                <div class="pt-4 border-t border-gray-200 dark:border-slate-700 logout-btn">
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
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Billing & Subscription</h2>
                            <p class="text-gray-600 dark:text-slate-400 text-sm mt-1">Manage your InfiMal subscription and payments</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="downloadInvoice()" class="border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 px-6 py-2 rounded-lg font-semibold text-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition-all duration-300">
                                <span class="material-symbols-outlined align-middle mr-2 text-sm">download</span>
                                Invoice
                            </button>
                            <button onclick="upgradePlan()" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-2 rounded-lg font-semibold text-sm hover-glow transition-all duration-300">
                                <span class="material-symbols-outlined align-middle mr-2 text-sm">upgrade</span>
                                Upgrade
                            </button>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6 space-y-6">
                <!-- Welcome Banner -->
                <div class="glass-card rounded-2xl p-8 shadow-lg border-2 border-emerald-100 dark:border-emerald-900/50 hover-glow transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <span class="plan-badge badge-success">ACTIVE</span>
                                <span class="plan-badge badge-premium">LIFETIME PRO</span>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Welcome to InfiMal Pro! 🎉</h1>
                            <p class="text-gray-600 dark:text-slate-300 mb-4">You're subscribed to our Lifetime Pro plan. Thank you for your support!</p>
                            <div class="flex items-center gap-6">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-emerald-500 text-xl">verified</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        Plan: Lifetime Pro
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-emerald-500 text-xl">calendar_month</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        Activated: <?php echo e($purchase_date ?? 'Jan 15, 2024'); ?>

                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-emerald-500 text-xl">auto_awesome</span>
                                    <span class="text-gray-600 dark:text-slate-300 text-sm font-medium">
                                        Expires: Never (Lifetime)
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden lg:block">
                            <div class="w-32 h-32 bg-gradient-to-br from-emerald-100 to-green-100 dark:from-emerald-900/30 dark:to-green-900/30 rounded-2xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-6xl">workspace_premium</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Billing Overview -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Current Plan -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg">Current Plan</h3>
                            <span class="plan-badge badge-success">Active</span>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-slate-400">Plan Name</span>
                                <span class="text-gray-900 dark:text-white font-semibold">Lifetime Pro</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-slate-400">Price</span>
                                <span class="text-gray-900 dark:text-white font-semibold">$<?php echo e(number_format($price ?? 299.00, 2)); ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-slate-400">Billing Cycle</span>
                                <span class="text-gray-900 dark:text-white font-semibold">One-Time Payment</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-slate-400">Status</span>
                                <span class="text-green-600 dark:text-green-400 font-semibold">Active</span>
                            </div>
                        </div>
                        <button onclick="downloadInvoice()" class="w-full mt-6 py-3 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-800/30 text-emerald-700 dark:text-emerald-300 rounded-lg font-medium transition-colors">
                            Download Invoice
                        </button>
                    </div>

                    <!-- Payment Info -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg">Payment Information</h3>
                            <span class="material-symbols-outlined text-gray-400 dark:text-slate-600">credit_card</span>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-slate-400">Last Payment</span>
                                <span class="text-gray-900 dark:text-white font-semibold">$<?php echo e(number_format($amount_paid ?? 299.00, 2)); ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-slate-400">Payment Date</span>
                                <span class="text-gray-900 dark:text-white font-semibold"><?php echo e($payment_date ?? $purchase_date ?? 'Jan 15, 2024'); ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-slate-400">Payment Method</span>
                                <span class="text-gray-900 dark:text-white font-semibold">Credit Card</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-slate-400">Transaction ID</span>
                                <span class="text-gray-900 dark:text-white font-semibold text-sm"><?php echo e($transaction_id ?? 'TXN-' . strtoupper(uniqid())); ?></span>
                            </div>
                        </div>
                        <button onclick="updatePaymentMethod()" class="w-full mt-6 py-3 border border-gray-300 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 rounded-lg font-medium transition-colors">
                            Update Payment Method
                        </button>
                    </div>

                    <!-- Plan Features -->
                    <div class="glass-card rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg">Plan Features</h3>
                            <span class="material-symbols-outlined text-emerald-500">star</span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-emerald-500 text-sm">check_circle</span>
                                <span class="text-gray-900 dark:text-white text-sm">Unlimited Email Sending</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-emerald-500 text-sm">check_circle</span>
                                <span class="text-gray-900 dark:text-white text-sm">50 SMTP Accounts</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-emerald-500 text-sm">check_circle</span>
                                <span class="text-gray-900 dark:text-white text-sm">Priority Support</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-emerald-500 text-sm">check_circle</span>
                                <span class="text-gray-900 dark:text-white text-sm">Advanced Analytics</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-emerald-500 text-sm">check_circle</span>
                                <span class="text-gray-900 dark:text-white text-sm">Custom Branding</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-emerald-500 text-sm">check_circle</span>
                                <span class="text-gray-900 dark:text-white text-sm">API Access</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Billing History -->
                <div class="glass-card rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-gray-900 dark:text-white font-bold text-lg">Billing History</h3>
                        <button onclick="viewAllInvoices()" class="text-blue-600 dark:text-blue-400 text-sm font-medium hover:underline">
                            View All
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-gray-600 dark:text-slate-400 text-sm border-b border-gray-200 dark:border-slate-700">
                                    <th class="pb-3 text-left font-semibold pl-4">Invoice ID</th>
                                    <th class="pb-3 text-left font-semibold">Date</th>
                                    <th class="pb-3 text-left font-semibold">Amount</th>
                                    <th class="pb-3 text-left font-semibold">Status</th>
                                    <th class="pb-3 text-left font-semibold">Plan</th>
                                    <th class="pb-3 text-left font-semibold pr-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Most recent invoice -->
                                <tr class="border-b border-gray-100 dark:border-slate-800 last:border-0 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-4 pl-4">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 rounded-lg bg-emerald-100 dark:bg-emerald-900/50">
                                                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-sm">receipt</span>
                                            </div>
                                            <div>
                                                <p class="text-gray-900 dark:text-white font-medium">INV-<?php echo e(strtoupper(uniqid())); ?></p>
                                                <p class="text-gray-600 dark:text-slate-400 text-xs">Lifetime Pro Plan</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <span class="text-gray-900 dark:text-white text-sm"><?php echo e($purchase_date ?? 'Jan 15, 2024'); ?></span>
                                    </td>
                                    <td class="py-4">
                                        <span class="text-gray-900 dark:text-white text-sm font-semibold">$<?php echo e(number_format($price ?? 299.00, 2)); ?></span>
                                    </td>
                                    <td class="py-4">
                                        <span class="plan-badge badge-success">Paid</span>
                                    </td>
                                    <td class="py-4">
                                        <span class="text-gray-900 dark:text-white text-sm">Lifetime Pro</span>
                                    </td>
                                    <td class="py-4 pr-4">
                                        <button onclick="downloadInvoice()" class="flex items-center gap-2 px-3 py-1.5 border border-gray-300 dark:border-slate-700 rounded-lg text-gray-700 dark:text-slate-300 text-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                            <span class="material-symbols-outlined text-sm">download</span>
                                            Download
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Support Section -->
                <div class="glass-card rounded-2xl p-6 shadow-lg">
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                        <div class="flex-1">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-2">Need Help with Billing?</h3>
                            <p class="text-gray-600 dark:text-slate-300 mb-4">Our support team is here to help you with any billing questions or issues.</p>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500">support_agent</span>
                                    <span class="text-gray-900 dark:text-white text-sm">Priority Support</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-500">schedule</span>
                                    <span class="text-gray-900 dark:text-white text-sm">24/7 Response</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button onclick="contactSupport()" class="px-6 py-3 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-slate-300 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                Contact Support
                            </button>
                            <a href="mailto:support@infimal.com" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                Email Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Payment Method Modal -->
    <div id="paymentModal" class="modal-backdrop modal-hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-md shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-gray-900 dark:text-white text-xl font-bold">Update Payment Method</h3>
                <button onclick="closeModal('paymentModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
            </div>
            <form id="paymentForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Card Number</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500">credit_card</span>
                            <input type="text" class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-white bg-white dark:bg-slate-800" placeholder="1234 5678 9012 3456">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Expiry Date</label>
                            <input type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-white bg-white dark:bg-slate-800" placeholder="MM/YY">
                        </div>
                        <div>
                            <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">CVC</label>
                            <input type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-white bg-white dark:bg-slate-800" placeholder="123">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Cardholder Name</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-white bg-white dark:bg-slate-800" placeholder="John Doe">
                    </div>
                    
                    <div class="pt-4">
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="closeModal('paymentModal')" class="px-6 py-2 border border-gray-300 dark:border-slate-700 rounded-lg text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                                Update Card
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="loading-spinner"></div>
    </div>

    <script>
        // Theme Toggle
        function initThemeToggle() {
            const themeToggle = document.getElementById('themeToggle');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const savedTheme = localStorage.getItem('infimal_theme');
            
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            themeToggle.addEventListener('click', () => {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('infimal_theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('infimal_theme', 'dark');
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
            document.querySelectorAll('.toast').forEach(toast => toast.remove());
            
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
            
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 3000);
        }

        // Modal Functions
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('modal-hidden');
        }

        function updatePaymentMethod() {
            document.getElementById('paymentModal').classList.remove('modal-hidden');
        }

        // Billing Actions
        function downloadInvoice() {
            showLoading(true);
            // Simulate download
            setTimeout(() => {
                showToast('✅ Invoice downloaded successfully', 'success');
                showLoading(false);
            }, 1500);
        }

        function upgradePlan() {
            showToast('ℹ️ You already have the highest plan!', 'info');
        }

        function viewAllInvoices() {
            showLoading(true);
            // Simulate loading invoices
            setTimeout(() => {
                showToast('📄 Loading all invoices...', 'info');
                showLoading(false);
            }, 1000);
        }

        function contactSupport() {
            window.open('mailto:support@infimal.com', '_blank');
        }

        // Form submission
        document.getElementById('paymentForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            showLoading(true);
            
            setTimeout(() => {
                showToast('✅ Payment method updated successfully', 'success');
                closeModal('paymentModal');
                showLoading(false);
            }, 2000);
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initThemeToggle();
        });
    </script>
</body>
</html>
<script src="https://www.paypal.com/sdk/js?client-id=sb&currency=USD"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var payBtn = document.getElementById('payWithPayPal');
    if (!payBtn) return;
    var hiddenContainer = document.createElement('div');
    hiddenContainer.style.display = 'none';
    hiddenContainer.id = 'paypal-hidden-container';
    document.body.appendChild(hiddenContainer);
    let paypalRendered = false;
    payBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (paypalRendered) return;
        paypalRendered = true;
        paypal.Buttons({
            style: { layout: 'vertical' },
            createOrder: function(data, actions) {
                return fetch('/paypal/create-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                })
                .then(res => res.json())
                .then(data => data.id);
            },
            onApprove: function(data, actions) {
                return fetch('/paypal/capture-order/' + data.orderID, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(details => {
                    if(details.status === 'COMPLETED') {
                        window.location.reload();
                    } else {
                        alert('Payment not completed.');
                    }
                });
            },
            onError: function(err) {
                alert('PayPal error: ' + err);
            }
        }).render('#paypal-hidden-container');
        // Programmatically click the hidden PayPal button
        setTimeout(function() {
            var btn = hiddenContainer.querySelector('iframe');
            if(btn) {
                btn.contentWindow.focus();
            }
        }, 500);
    });
});
</script><?php /**PATH /var/www/infimal--Local/resources/views/billing/index.blade.php ENDPATH**/ ?>