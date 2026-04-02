<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 Fixing Views Location\n";
echo "=======================\n";

// 1. Fix DashboardController
echo "Fixing DashboardController...\n";
$dashboardController = file_get_contents(__DIR__ . '/app/Http/Controllers/DashboardController.php');
$dashboardController = str_replace("view('dashboard.index'", "view('dashboard'", $dashboardController);
file_put_contents(__DIR__ . '/app/Http/Controllers/DashboardController.php', $dashboardController);
echo "✅ DashboardController fixed\n";

// 2. Fix SubscriberController
echo "Fixing SubscriberController...\n";
$subscriberController = file_get_contents(__DIR__ . '/app/Http/Controllers/SubscriberController.php');
$subscriberController = str_replace("view('dashboard.subscribers'", "view('subscribers'", $subscriberController);
file_put_contents(__DIR__ . '/app/Http/Controllers/SubscriberController.php', $subscriberController);
echo "✅ SubscriberController fixed\n";

// 3. Fix ListController
echo "Fixing ListController...\n";
$listController = file_get_contents(__DIR__ . '/app/Http/Controllers/ListController.php');
$listController = str_replace("view('dashboard.lists'", "view('lists'", $listController);
file_put_contents(__DIR__ . '/app/Http/Controllers/ListController.php', $listController);
echo "✅ ListController fixed\n";

// 4. Fix Routes
echo "Fixing Routes...\n";
$routes = file_get_contents(__DIR__ . '/routes/web.php');
$routes = str_replace("view('dashboard.", "view('", $routes);
file_put_contents(__DIR__ . '/routes/web.php', $routes);
echo "✅ Routes fixed\n";

// 5. Create missing views
echo "Creating missing views...\n";
$views = ['campaigns', 'messages', 'workspaces', 'smtp', 'billing', 'profile'];
$template = <<<'HTML'
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{TITLE}} - InfiMal</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .glass-card { background-color: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .soft-shadow { box-shadow: 0px 25px 50px -12px rgba(0, 0, 0, 0.4); }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-white/90">
    <div class="relative min-h-screen w-full overflow-hidden">
        <div class="relative z-10 flex h-full min-h-screen">
            <!-- SideNavBar -->
            <nav class="flex-shrink-0 w-64 p-4">
                <div class="flex flex-col h-full gap-4">
                    <div class="flex items-center gap-3 p-2">
                        <div class="p-2 rounded-full bg-primary/20 text-primary">
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
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ACTIVE}}" href="{{ url('/{{PAGE}}') }}">
                            <span class="material-symbols-outlined">{{ICON}}</span>
                            <p class="text-sm font-medium leading-normal">{{NAME}}</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/messages') }}">
                            <span class="material-symbols-outlined">chat</span>
                            <p class="text-sm font-medium leading-normal">Messages</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/70" href="{{ url('/workspaces') }}">
                            <span class="material-symbols-outlined">workspaces</span>
                            <p class="text-sm font-medium leading-normal">Workspaces</p>
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
                </div>
            </nav>

            <!-- Main Content -->
            <main class="flex-1 flex flex-col p-6 overflow-y-auto">
                <header class="flex-shrink-0 glass-card rounded-xl px-6 py-3 sticky top-6 z-20 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-8">
                            <h1 class="text-white text-xl font-bold">{{NAME}}</h1>
                        </div>
                        <div class="flex gap-4 items-center">
                            <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
                                Create New
                            </button>
                        </div>
                    </div>
                </header>

                <div class="text-center py-20 text-white/50">
                    <span class="material-symbols-outlined text-6xl mb-4">{{ICON}}</span>
                    <h2 class="text-2xl mb-4">{{NAME}} Page</h2>
                    <p>{{NAME}} functionality coming soon!</p>
                    <p class="mt-4"><a href="/subscribers" class="text-primary hover:underline">Go to Subscribers</a> or <a href="/lists" class="text-primary hover:underline">Go to Lists</a></p>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
HTML;

$pageConfig = [
    'campaigns' => ['name' => 'Campaigns', 'icon' => 'campaign'],
    'messages' => ['name' => 'Messages', 'icon' => 'chat'],
    'workspaces' => ['name' => 'Workspaces', 'icon' => 'workspaces'],
    'smtp' => ['name' => 'SMTP Settings', 'icon' => 'dns'],
    'billing' => ['name' => 'Billing', 'icon' => 'receipt_long'],
    'profile' => ['name' => 'Profile', 'icon' => 'person'],
];

foreach ($pageConfig as $page => $config) {
    $viewFile = __DIR__ . "/resources/views/{$page}.blade.php";
    
    if (!file_exists($viewFile)) {
        $content = str_replace(
            ['{{TITLE}}', '{{NAME}}', '{{ICON}}', '{{PAGE}}', '{{ACTIVE}}'],
            [$config['name'], $config['name'], $config['icon'], $page, 'bg-primary/20 text-white'],
            $template
        );
        
        file_put_contents($viewFile, $content);
        echo "✅ {$config['name']} view created\n";
    } else {
        echo "✅ {$config['name']} view already exists\n";
    }
}

echo "\n✅ ALL FIXES COMPLETED!\n";
echo "Now you can access:\n";
echo "1. Dashboard: https://infimal.site/dashboard\n";
echo "2. Subscribers: https://infimal.site/subscribers\n";
echo "3. Lists: https://infimal.site/lists\n";
echo "4. Campaigns: https://infimal.site/campaigns\n";
echo "5. Messages: https://infimal.site/messages\n";
echo "6. Workspaces: https://infimal.site/workspaces\n";
echo "7. SMTP: https://infimal.site/smtp\n";
echo "8. Billing: https://infimal.site/billing\n";
echo "9. Profile: https://infimal.site/profile\n";
