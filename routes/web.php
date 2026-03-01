<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\SmtpController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =================== PUBLIC ROUTES ===================

Route::get('/', function () {
    return view('public.index');
})->name('home');

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

Route::get('/features', function () {
    return view('features');
})->name('features');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/security', function () {
    return view('security');
})->name('security');

Route::get('/refund', function () {
    return view('refund');
})->name('refund');

Route::get('/help-center', function () {
    return view('help-center');
})->name('help.center');

// =================== AUTH ROUTES ===================

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::get('/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
});

Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');

// Tracking (public routes - no auth required)
Route::get('/track/open/{id}.png', [TrackingController::class, 'openById'])->name('track.open.id');
Route::get('/track/click/{id}', [TrackingController::class, 'clickById'])->name('track.click.id');
Route::get('/track/open', [TrackingController::class, 'trackOpen'])->name('track.open');
Route::get('/track/click', [TrackingController::class, 'trackClick'])->name('track.click');
Route::get('/track/unsubscribe', [TrackingController::class, 'unsubscribe'])->name('track.unsubscribe');
Route::post('/track/bounce', [TrackingController::class, 'trackBounce'])->name('track.bounce');

// =================== PROTECTED ROUTES ===================

Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // WORKSPACES
    Route::prefix('workspaces')->name('workspaces.')->group(function () {
        Route::get('/', [WorkspaceController::class, 'index'])->name('index');
        Route::get('/create', [WorkspaceController::class, 'create'])->name('create');
        Route::post('/', [WorkspaceController::class, 'store'])->name('store');
        Route::get('/{workspace}', [WorkspaceController::class, 'show'])->name('show');
        Route::get('/{workspace}/edit', [WorkspaceController::class, 'edit'])->name('edit');
        Route::put('/{workspace}', [WorkspaceController::class, 'update'])->name('update');
        Route::delete('/{workspace}', [WorkspaceController::class, 'destroy'])->name('destroy');
    });
    
    // CAMPAIGNS
    Route::resource('campaigns', CampaignController::class);
    Route::post('/campaigns/{campaign}/send', [CampaignController::class, 'send'])->name('campaigns.send');
    Route::get('/campaigns/{campaign}/preview', [CampaignController::class, 'preview'])->name('campaigns.preview');
    Route::get('/campaigns/{campaign}/analytics', [CampaignController::class, 'analytics'])->name('campaigns.analytics');
    Route::get('/campaigns/{campaign}/duplicate', [CampaignController::class, 'duplicate'])->name('campaigns.duplicate');
    
    // Subscribers Routes
    Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
    Route::post('/subscribers', [SubscriberController::class, 'store'])->name('subscribers.store');
    Route::post('/subscribers/import', [SubscriberController::class, 'import'])->name('subscribers.import');
    Route::get('/subscribers/export', [SubscriberController::class, 'export'])->name('subscribers.export');
    Route::delete('/subscribers/{id}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy');
    Route::get('/subscribers/{id}/edit', [SubscriberController::class, 'edit'])->name('subscribers.edit');
    Route::put('/subscribers/{id}', [SubscriberController::class, 'update'])->name('subscribers.update');
  
    // Lists Routes
    Route::get('/lists', [ListController::class, 'index'])->name('lists.index');
    Route::post('/lists', [ListController::class, 'store'])->name('lists.store');
    Route::put('/lists/{id}', [ListController::class, 'update'])->name('lists.update');
    Route::delete('/lists/{id}', [ListController::class, 'destroy'])->name('lists.destroy');
   
    // MESSAGES
    Route::resource('messages', MessageController::class);
    Route::post('/messages/{message}/duplicate', [MessageController::class, 'duplicate'])->name('messages.duplicate');
    Route::get('/messages/{message}/preview', [MessageController::class, 'preview'])->name('messages.preview');
    
    // SMTP
    Route::resource('smtp', SmtpController::class);
    Route::post('/smtp/{smtp}/test', [SmtpController::class, 'test'])->name('smtp.test');
    Route::post('/smtp/{smtp}/verify', [SmtpController::class, 'verify'])->name('smtp.verify');
    Route::post('/smtp/{smtp}/set-default', [SmtpController::class, 'setDefault'])->name('smtp.set-default');
    
    // BILLING
    Route::get('/billing', [BillingController::class, 'index'])->name('billing');
    
    // ✅✅✅ PAYMENT - PAYPAL INTEGRATED ✅✅✅
    Route::get('/payment', function () {
        return view('payments.checkout');
    })->name('payment');
    
    
    Route::post('/paypal/create-order', [PayPalController::class, 'createOrder']);
    Route::post('/paypal/capture-order/{orderId}', [PayPalController::class, 'captureOrder']);
    
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::post('/payment/create-checkout', [PaymentController::class, 'processPaddleCheckout'])->name('payment.create');
    Route::post('/payment/process', [PaymentController::class, 'processPaddleCheckout'])->name('payment.process');
    
    // PROFILE
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::post('/update-avatar', [ProfileController::class, 'updateAvatar'])->name('update-avatar');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
        Route::post('/settings/update', [ProfileController::class, 'updateSettings'])->name('settings.update');
    });
    
    // ANALYTICS
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');
        Route::get('/campaigns', [AnalyticsController::class, 'campaigns'])->name('campaigns');
        Route::get('/subscribers', [AnalyticsController::class, 'subscribers'])->name('subscribers');
        Route::get('/reports', [AnalyticsController::class, 'reports'])->name('reports');
        Route::get('/export', [AnalyticsController::class, 'export'])->name('export');
    });
    
    Route::get('/templates', function () {
        return view('pages.templates');
    })->name('templates');
    
    Route::get('/automation', function () {
        return view('pages.automation');
    })->name('automation');
    
    // API ROUTES
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/smtp/credentials', [SmtpController::class, 'getCredentials'])->name('smtp.credentials');
        Route::get('/limits', [DashboardController::class, 'getLimits'])->name('limits');
        Route::get('/stats', [DashboardController::class, 'getStats'])->name('stats');
        Route::get('/recent-activity', [DashboardController::class, 'getRecentActivity'])->name('recent-activity');
        Route::get('/admin/analytics/users', [AdminAnalyticsController::class, 'users'])->name('admin.analytics.users');
        Route::get('/admin/analytics/users/{userId}', [AdminAnalyticsController::class, 'userDetail'])->name('admin.analytics.user-detail');
    });
});

// =================== ADMIN ROUTES ===================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin check helper
    $checkAdmin = function() {
        $user = auth()->user();
        
        // List of admin emails
        $adminEmails = [
            'admin@infimal.site',
            'contact@infimal.site',
            'sainikhilsaini143@gmail.com',
            'khileshrathod1729@gmail.com',
            'kanishghongade@gmail.com',
            'gamingeegamerz@gmail.com'
        ];
        
        // Check if user email is in admin list
        if (in_array($user->email, $adminEmails)) {
            return true;
        }
        
        // Check if is_admin column exists and is true
        if (isset($user->is_admin) && $user->is_admin) {
            return true;
        }
        
        // If neither condition is met, deny access
        abort(403, 'Admin access required.');
    };
    
    // DASHBOARD
    Route::get('/dashboard', function() use ($checkAdmin) {
        $checkAdmin();
        
        try {
            $totalUsers = DB::table('users')->count();
            $activeLicenses = DB::table('licenses')->where('status', 'active')->count();
            $emailsToday = DB::table('email_logs')->whereDate('created_at', today())->count();
            $totalEmailsSent = DB::table('email_logs')->count();
            $frozenUsers = DB::table('user_trust')->where('is_frozen', true)->count();
            $usersToday = DB::table('users')->whereDate('created_at', today())->count();
            $licensesToday = DB::table('licenses')->whereDate('created_at', today())->count();
            $activeLicensesPercentage = $totalUsers > 0 ? round(($activeLicenses / $totalUsers) * 100, 1) : 0;
            $totalRevenue = DB::table('payments')->where('status', 'completed')->sum('amount') / 100;
            $revenueToday = DB::table('payments')->where('status', 'completed')->whereDate('created_at', today())->sum('amount') / 100;
            $avgTrustScore = round(DB::table('user_trust')->avg('trust_score') ?? 85, 1);
            $trustStats = DB::table('user_trust')->select('stage', DB::raw('count(*) as total'))->groupBy('stage')->orderBy('stage')->get();
            $users = DB::table('users')->leftJoin('user_trust', 'users.id', '=', 'user_trust.user_id')->leftJoin('licenses', 'users.id', '=', 'licenses.user_id')->select('users.id', 'users.name', 'users.email', 'users.is_admin', 'users.created_at', 'user_trust.stage', 'user_trust.trust_score', 'user_trust.is_frozen', 'licenses.status as license_status')->orderByDesc('users.id')->limit(15)->get();
            $recentActivity = collect([(object)['type' => 'user', 'description' => 'New user registered', 'time' => 'Just now'], (object)['type' => 'license', 'description' => 'License activated', 'time' => '5 mins ago'], (object)['type' => 'email', 'description' => 'Campaign sent', 'time' => '10 mins ago']]);
        } catch (\Exception $e) {
            $totalUsers = $activeLicenses = $emailsToday = $totalEmailsSent = $frozenUsers = $usersToday = $licensesToday = $activeLicensesPercentage = $totalRevenue = $revenueToday = $avgTrustScore = 0;
            $trustStats = $users = $recentActivity = collect();
        }
        
        return view('admin.dashboard', compact('totalUsers', 'activeLicenses', 'emailsToday', 'totalEmailsSent', 'frozenUsers', 'usersToday', 'licensesToday', 'activeLicensesPercentage', 'totalRevenue', 'revenueToday', 'avgTrustScore', 'trustStats', 'users', 'recentActivity'));
    })->name('dashboard');
    
    // TEST ROUTES
    Route::get('/test', function() {
        return response()->json(['success' => true, 'message' => 'Admin route working', 'user' => auth()->user()->email, 'is_admin' => auth()->user()->is_admin ?? 'not_set']);
    })->name('test');
    
    Route::post('/test-csrf', function() {
        return response()->json(['success' => true, 'message' => 'CSRF valid', 'user' => auth()->user()->email]);
    })->name('test.csrf');
    
    // MAKE ADMIN
    Route::get('/make-admin', function() {
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS is_admin BOOLEAN DEFAULT 0");
        } catch (\Exception $e) {}
        DB::table('users')->where('id', auth()->id())->update(['is_admin' => 1]);
        return response()->json(['success' => true, 'message' => 'Admin status granted', 'email' => auth()->user()->email]);
    })->name('make.admin');
    
    // USERS
    Route::get('/users', function() use ($checkAdmin) {
        $checkAdmin();
        try {
            $users = DB::table('users')->select('id', 'name', 'email', 'is_admin', 'created_at')->orderByDesc('id')->paginate(20);
        } catch (\Exception $e) {
            $users = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20, 1, ['path' => request()->url()]);
        }
        return view('admin.users.index', compact('users'));
    })->name('users.index');
    
    // LICENSES
    Route::get('/licenses', function() use ($checkAdmin) {
        $checkAdmin();
        try {
            $licenses = DB::table('licenses')->leftJoin('users', 'licenses.user_id', '=', 'users.id')->select('licenses.*', 'users.name as user_name', 'users.email as user_email')->orderByDesc('licenses.created_at')->paginate(20);
            $totalLicenses = DB::table('licenses')->count();
            $activeLicenses = DB::table('licenses')->where('is_active', true)->count();
            $totalRevenue = DB::table('licenses')->sum('price') ?? 0;
        } catch (\Exception $e) {
            $licenses = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20, 1, ['path' => request()->url()]);
            $totalLicenses = $activeLicenses = $totalRevenue = 0;
        }
        return view('admin.licenses.index', compact('licenses', 'totalLicenses', 'activeLicenses', 'totalRevenue'));
    })->name('licenses.index');
    
    Route::post('/licenses/generate', function() use ($checkAdmin) {
        $checkAdmin();
        $licenseKey = 'INFIMAL-' . strtoupper(bin2hex(random_bytes(4))) . '-' . strtoupper(bin2hex(random_bytes(4))) . '-' . strtoupper(bin2hex(random_bytes(4))) . '-' . strtoupper(bin2hex(random_bytes(4)));
        DB::table('licenses')->insert(['license_key' => $licenseKey, 'plan_type' => 'Premium', 'duration_days' => 30, 'is_active' => true, 'price' => 299.00, 'expires_at' => now()->addDays(30), 'created_at' => now(), 'updated_at' => now()]);
        return response()->json(['success' => true, 'license_key' => $licenseKey]);
    })->name('licenses.generate');
    
    Route::post('/licenses/bulk-generate', function() use ($checkAdmin) {
        $checkAdmin();
        $count = request('count', 10);
        $days = request('days', 30);
        for ($i = 0; $i < $count; $i++) {
            $licenseKey = 'INFIMAL-' . strtoupper(bin2hex(random_bytes(4))) . '-' . strtoupper(bin2hex(random_bytes(4))) . '-' . strtoupper(bin2hex(random_bytes(4))) . '-' . strtoupper(bin2hex(random_bytes(4)));
            DB::table('licenses')->insert(['license_key' => $licenseKey, 'plan_type' => 'Premium', 'duration_days' => $days, 'is_active' => true, 'price' => 299.00, 'expires_at' => now()->addDays($days), 'created_at' => now(), 'updated_at' => now()]);
        }
        return response()->json(['success' => true, 'message' => "$count licenses generated"]);
    })->name('licenses.bulk-generate');
    
    Route::get('/licenses/export', function() use ($checkAdmin) {
        $checkAdmin();
        try {
            $licenses = DB::table('licenses')->get();
            $filename = 'licenses_export_' . date('Y-m-d') . '.csv';
            $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="' . $filename . '"'];
            $callback = function() use ($licenses) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['License Key', 'Plan', 'Price', 'Status', 'Expires', 'Created']);
                foreach ($licenses as $license) {
                    fputcsv($file, [$license->license_key, $license->plan_type, '$' . $license->price, $license->is_active ? 'Active' : 'Inactive', $license->expires_at, $license->created_at]);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return back()->with('error', 'Export failed');
        }
    })->name('licenses.export');
    
    // TRUST
    Route::get('/trust', function() use ($checkAdmin) {
        $checkAdmin();
        try {
            $totalUsers = DB::table('users')->count();
            $highTrustUsers = DB::table('user_trust')->where('trust_score', '>=', 80)->count();
            $mediumTrustUsers = DB::table('user_trust')->whereBetween('trust_score', [50, 79])->count();
            $lowTrustUsers = DB::table('user_trust')->where('trust_score', '<', 50)->count();
            $frozenUsers = DB::table('user_trust')->where('is_frozen', true)->count();
            $avgTrustScore = round(DB::table('user_trust')->avg('trust_score') ?? 85, 1);
            $stage1Count = DB::table('user_trust')->where('stage', 1)->count();
            $stage2Count = DB::table('user_trust')->where('stage', 2)->count();
            $stage3Count = DB::table('user_trust')->where('stage', 3)->count();
            $stage4Count = DB::table('user_trust')->where('stage', 4)->count();
            $stage5Count = DB::table('user_trust')->where('stage', 5)->count();
            $newFrozenToday = DB::table('user_trust')->where('is_frozen', true)->whereDate('frozen_at', today())->count();
            $stageDistribution = collect(['stage1' => $stage1Count, 'stage2' => $stage2Count, 'stage3' => $stage3Count, 'stage4' => $stage4Count, 'stage5' => $stage5Count]);
            $monitoredUsers = DB::table('user_trust')->where('stage', '>=', 3)->count();
            $alertsToday = DB::table('user_trust')->where('trust_score', '<', 50)->whereDate('updated_at', today())->count();
            $users = DB::table('users')->leftJoin('user_trust', 'users.id', '=', 'user_trust.user_id')->select('users.id', 'users.name', 'users.email', 'users.created_at', 'user_trust.user_id', 'user_trust.trust_score', 'user_trust.stage', 'user_trust.emails_last_hour', 'user_trust.last_activity_at', 'user_trust.is_frozen', 'user_trust.frozen_at')->orderByDesc('user_trust.trust_score')->paginate(20);
        } catch (\Exception $e) {
            $totalUsers = $highTrustUsers = $mediumTrustUsers = $lowTrustUsers = $frozenUsers = 0;
            $avgTrustScore = 85;
            $stage1Count = $stage2Count = $stage3Count = $stage4Count = $stage5Count = 0;
            $newFrozenToday = 0;
            $stageDistribution = collect(['stage1' => 0, 'stage2' => 0, 'stage3' => 0, 'stage4' => 0, 'stage5' => 0]);
            $monitoredUsers = $alertsToday = 0;
            $users = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20, 1, ['path' => request()->url()]);
        }
        return view('admin.trust', compact('totalUsers', 'highTrustUsers', 'mediumTrustUsers', 'lowTrustUsers', 'frozenUsers', 'avgTrustScore', 'stage1Count', 'stage2Count', 'stage3Count', 'stage4Count', 'stage5Count', 'newFrozenToday', 'stageDistribution', 'monitoredUsers', 'alertsToday', 'users'));
    })->name('trust.index');
    
    // TRUST ACTIONS
    Route::post('/trust/{userId}/adjust', function($userId) use ($checkAdmin) {
        $checkAdmin();
        try {
            $trustScore = request('trust_score');
            DB::table('user_trust')->where('user_id', $userId)->update(['trust_score' => $trustScore]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    })->name('trust.adjust');
    
    Route::post('/trust/{userId}/freeze', function($userId) use ($checkAdmin) {
        $checkAdmin();
        try {
            DB::table('user_trust')->where('user_id', $userId)->update(['is_frozen' => true, 'frozen_at' => now()]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    })->name('trust.freeze');
    
    Route::post('/trust/{userId}/unfreeze', function($userId) use ($checkAdmin) {
        $checkAdmin();
        try {
            DB::table('user_trust')->where('user_id', $userId)->update(['is_frozen' => false, 'frozen_at' => null]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    })->name('trust.unfreeze');
    
    Route::get('/trust/export', function() use ($checkAdmin) {
        $checkAdmin();
        try {
            $users = DB::table('users')->leftJoin('user_trust', 'users.id', '=', 'user_trust.user_id')->select('users.*', 'user_trust.*')->get();
            $filename = 'trust_export_' . date('Y-m-d') . '.csv';
            $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="' . $filename . '"'];
            $callback = function() use ($users) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['User ID', 'Name', 'Email', 'Trust Score', 'Stage', 'Status', 'Frozen At']);
                foreach ($users as $user) {
                    fputcsv($file, [$user->id, $user->name, $user->email, $user->trust_score ?? 100, $user->stage ?? 1, $user->is_frozen ? 'Frozen' : 'Active', $user->frozen_at ?? 'N/A']);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return back()->with('error', 'Export failed');
        }
    })->name('trust.export');
    
    // EMAILS
    Route::get('/emails', function() use ($checkAdmin) {
        $checkAdmin();
        try {
            $totalEmails = DB::table('email_logs')->count();
            $emailsToday = DB::table('email_logs')->whereDate('created_at', today())->count();
            $deliveredCount = DB::table('email_logs')->where('status', 'delivered')->count();
            $failedCount = DB::table('email_logs')->where('status', 'failed')->count();
            $pendingCount = DB::table('email_logs')->where('status', 'pending')->count();
            $bouncedCount = DB::table('email_logs')->where('status', 'bounced')->count();
            $sentCount = DB::table('email_logs')->where('status', 'sent')->count();
            $successful = $deliveredCount + $sentCount;
            $attempted = $totalEmails;
            $successRate = $attempted > 0 ? round(($successful / $attempted) * 100, 1) : 0;
            $opensToday = DB::table('email_logs')->whereDate('created_at', today())->sum('opens_count') ?? 0;
            $clicksToday = DB::table('email_logs')->whereDate('created_at', today())->sum('clicks_count') ?? 0;
            $failuresToday = DB::table('email_logs')->where('status', 'failed')->whereDate('created_at', today())->count();
            $avgOpenRate = DB::table('email_logs')->where('opens_count', '>', 0)->avg('open_rate') ?? 0;
            $avgOpenRate = round($avgOpenRate, 1);
            $avgClickRate = DB::table('email_logs')->where('clicks_count', '>', 0)->avg('click_rate') ?? 0;
            $avgClickRate = round($avgClickRate, 1);
            $avgResponseTime = DB::table('email_logs')->whereNotNull('response_time')->avg('response_time') ?? 0;
            $avgResponseTime = round($avgResponseTime, 2);
            $emails = DB::table('email_logs')->leftJoin('users', 'email_logs.user_id', '=', 'users.id')->leftJoin('campaigns', 'email_logs.campaign_id', '=', 'campaigns.id')->select('email_logs.id', 'email_logs.to_email', 'email_logs.subject', 'email_logs.status', 'email_logs.opens_count', 'email_logs.clicks_count', 'email_logs.open_rate', 'email_logs.click_rate', 'email_logs.response_time', 'email_logs.error_message', 'email_logs.sent_at', 'email_logs.created_at', 'users.email as user_email', 'users.name as user_name', 'campaigns.name as campaign_name')->orderByDesc('email_logs.created_at')->paginate(20);
        } catch (\Exception $e) {
            $totalEmails = $emailsToday = 0;
            $deliveredCount = $failedCount = $pendingCount = $bouncedCount = $sentCount = 0;
            $successRate = 0;
            $opensToday = $clicksToday = $failuresToday = 0;
            $avgOpenRate = $avgClickRate = 0;
            $avgResponseTime = 0;
            $emails = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20, 1, ['path' => request()->url()]);
        }
        return view('admin.emails', compact('totalEmails', 'emailsToday', 'deliveredCount', 'failedCount', 'pendingCount', 'bouncedCount', 'sentCount', 'successRate', 'opensToday', 'clicksToday', 'failuresToday', 'avgOpenRate', 'avgClickRate', 'avgResponseTime', 'emails'));
    })->name('emails.index');
    
    Route::get('/emails/stats', function() use ($checkAdmin) {
        $checkAdmin();
        try {
            $totalEmails = DB::table('email_logs')->count();
            $emailsToday = DB::table('email_logs')->whereDate('created_at', today())->count();
            $deliveredCount = DB::table('email_logs')->where('status', 'delivered')->count();
            $sentCount = DB::table('email_logs')->where('status', 'sent')->count();
            $pendingCount = DB::table('email_logs')->where('status', 'pending')->count();
            $failedCount = DB::table('email_logs')->where('status', 'failed')->count();
            $bouncedCount = DB::table('email_logs')->where('status', 'bounced')->count();
            $successful = $deliveredCount + $sentCount;
            $attempted = $totalEmails;
            $successRate = $attempted > 0 ? round(($successful / $attempted) * 100, 1) : 0;
            $opensToday = DB::table('email_logs')->whereDate('created_at', today())->sum('opens_count') ?? 0;
            $clicksToday = DB::table('email_logs')->whereDate('created_at', today())->sum('clicks_count') ?? 0;
            $failuresToday = DB::table('email_logs')->where('status', 'failed')->whereDate('created_at', today())->count();
            $avgOpenRate = DB::table('email_logs')->where('opens_count', '>', 0)->avg('open_rate') ?? 0;
            $avgOpenRate = round($avgOpenRate, 1);
            $avgClickRate = DB::table('email_logs')->where('clicks_count', '>', 0)->avg('click_rate') ?? 0;
            $avgClickRate = round($avgClickRate, 1);
            $avgResponseTime = DB::table('email_logs')->whereNotNull('response_time')->avg('response_time') ?? 0;
            $avgResponseTime = round($avgResponseTime, 2);
            return response()->json(['success' => true, 'totalEmails' => $totalEmails, 'emailsToday' => $emailsToday, 'deliveredCount' => $deliveredCount, 'sentCount' => $sentCount, 'pendingCount' => $pendingCount, 'failedCount' => $failedCount, 'bouncedCount' => $bouncedCount, 'successRate' => $successRate, 'opensToday' => $opensToday, 'clicksToday' => $clicksToday, 'failuresToday' => $failuresToday, 'avgOpenRate' => $avgOpenRate, 'avgClickRate' => $avgClickRate, 'avgResponseTime' => $avgResponseTime, 'timestamp' => now()->toISOString()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error fetching stats', 'error' => $e->getMessage()], 500);
        }
    })->name('emails.stats');
    
    Route::post('/emails/bulk-resend-failed', function() use ($checkAdmin) {
        $checkAdmin();
        try {
            $failedEmails = DB::table('email_logs')->where('status', 'failed')->get();
            foreach ($failedEmails as $email) {
                DB::table('email_logs')->where('id', $email->id)->update(['status' => 'pending', 'updated_at' => now()]);
            }
            return response()->json(['success' => true, 'message' => 'Queued ' . count($failedEmails) . ' failed emails for resending']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    })->name('emails.bulk-resend');
    
    Route::post('/emails/bulk-delete-old', function() use ($checkAdmin) {
        $checkAdmin();
        $thirtyDaysAgo = now()->subDays(30);
        try {
            $deleted = DB::table('email_logs')->where('created_at', '<', $thirtyDaysAgo)->delete();
            return response()->json(['success' => true, 'message' => "Deleted $deleted old email logs"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    })->name('emails.bulk-delete');
    
    // SMTP
    Route::get('/smtp', function() use ($checkAdmin) {
        $checkAdmin();
        try {
            $smtpServers = DB::table('smtp_servers')->orderByDesc('created_at')->get();
            $totalSMTPs = $smtpServers->count();
            $activeSMTPs = $smtpServers->where('is_active', true)->count();
            $disabledSMTPs = $smtpServers->where('is_active', false)->count();
            $riskySMTPs = $smtpServers->where('reputation_score', '<', 60)->where('is_active', true)->count();
            $avgReputation = $activeSMTPs > 0 ? round($smtpServers->where('is_active', true)->avg('reputation_score') ?? 100, 1) : 100;
            $avgRotationScore = $totalSMTPs > 0 ? round($smtpServers->avg('rotation_score') ?? 50, 1) : 50;
            $emailsToday = DB::table('email_logs')->whereDate('created_at', today())->count();
            $emailsPerHour = DB::table('email_logs')->where('created_at', '>=', now()->subHour())->count();
            $avgEmailsPerSMTP = $totalSMTPs > 0 ? round($emailsToday / $totalSMTPs, 1) : 0;
            $systemHealth = 100;
            if ($avgReputation < 70) $systemHealth -= 20;
            if ($totalSMTPs > 0 && ($riskySMTPs / $totalSMTPs) > 0.3) $systemHealth -= 20;
            if ($totalSMTPs > 0 && ($disabledSMTPs / $totalSMTPs) > 0.5) $systemHealth -= 15;
            $systemHealth = max(30, round($systemHealth));
            $healthStats = ['excellent' => $smtpServers->where('reputation_score', '>=', 80)->where('is_active', true)->count(), 'good' => $smtpServers->whereBetween('reputation_score', [60, 79])->where('is_active', true)->count(), 'risky' => $smtpServers->whereBetween('reputation_score', [40, 59])->where('is_active', true)->count(), 'critical' => $smtpServers->where('reputation_score', '<', 40)->where('is_active', true)->count(), 'disabled' => $disabledSMTPs];
            $providerStats = ['gmail' => $smtpServers->where('provider', 'gmail')->count(), 'outlook' => $smtpServers->where('provider', 'outlook')->count(), 'yahoo' => $smtpServers->where('provider', 'yahoo')->count(), 'custom' => $smtpServers->where('provider', 'custom')->count()];
            $failureStats = ['soft_bounces' => $smtpServers->sum('soft_bounces_24h') ?? 0, 'hard_bounces' => $smtpServers->sum('hard_bounces_24h') ?? 0, 'spam_complaints' => $smtpServers->sum('spam_complaints_24h') ?? 0, 'auth_errors' => $smtpServers->sum('auth_errors_24h') ?? 0, 'temp_failures' => $smtpServers->sum('temp_failures_24h') ?? 0];
            $totalEmailsToday = $emailsToday;
            $bounceRate = $emailsToday > 0 ? round((($failureStats['soft_bounces'] + $failureStats['hard_bounces']) / $emailsToday) * 100, 1) : 0;
            $spamRate = $emailsToday > 0 ? round(($failureStats['spam_complaints'] / $emailsToday) * 100, 1) : 0;
            $systemStability = $emailsToday > 0 ? round(100 - (($failureStats['temp_failures'] / $emailsToday) * 100), 1) : 100;
            $rotationSuccessRate = 95;
            $perPage = 20;
            $currentPage = request('page', 1);
            $smtps = new \Illuminate\Pagination\LengthAwarePaginator($smtpServers->forPage($currentPage, $perPage), $totalSMTPs, $perPage, $currentPage, ['path' => request()->url()]);
        } catch (\Exception $e) {
            $totalSMTPs = 1;
            $activeSMTPs = $disabledSMTPs = $riskySMTPs = 0;
            $avgReputation = $avgRotationScore = $systemHealth = 100;
            $emailsToday = $emailsPerHour = $avgEmailsPerSMTP = $totalEmailsToday = 0;
            $healthStats = ['excellent' => 0, 'good' => 0, 'risky' => 0, 'critical' => 0, 'disabled' => 0];
            $providerStats = ['gmail' => 0, 'outlook' => 0, 'yahoo' => 0, 'custom' => 0];
            $failureStats = ['soft_bounces' => 0, 'hard_bounces' => 0, 'spam_complaints' => 0, 'auth_errors' => 0, 'temp_failures' => 0];
            $bounceRate = $spamRate = $systemStability = 0;
            $rotationSuccessRate = 95;
            $smtps = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20, 1, ['path' => request()->url()]);
        }
        return view('admin.smtp', compact('totalSMTPs', 'activeSMTPs', 'disabledSMTPs', 'riskySMTPs', 'avgReputation', 'avgRotationScore', 'emailsToday', 'emailsPerHour', 'avgEmailsPerSMTP', 'systemHealth', 'healthStats', 'providerStats', 'failureStats', 'totalEmailsToday', 'bounceRate', 'spamRate', 'systemStability', 'rotationSuccessRate', 'smtps'));
    })->name('smtp.index');
    
    // REVENUE
    Route::get('/revenue', [RevenueController::class, 'index'])->name('revenue.index');
    Route::get('/revenue/stats', [RevenueController::class, 'stats'])->name('revenue.stats');
    Route::get('/revenue/transaction/{id}', [RevenueController::class, 'getTransaction'])->name('revenue.transaction');
    Route::post('/revenue/transaction/{id}/approve', [RevenueController::class, 'approveTransaction'])->name('revenue.approve');
    Route::post('/revenue/transaction/{id}/refund', [RevenueController::class, 'refundTransaction'])->name('revenue.refund');
    Route::post('/revenue/generate-report', [RevenueController::class, 'generateReport'])->name('revenue.generate-report');
    Route::post('/revenue/update-pricing', [RevenueController::class, 'updatePricing'])->name('revenue.update-pricing');
    Route::post('/revenue/send-reminders', [RevenueController::class, 'sendReminders'])->name('revenue.send-reminders');
    Route::post('/revenue/reconcile', [RevenueController::class, 'reconcile'])->name('revenue.reconcile');
    
    // API STATS
    Route::get('/api/stats', function() use ($checkAdmin) {
        $checkAdmin();
        try {
            return response()->json(['totalUsers' => DB::table('users')->count(), 'activeLicenses' => DB::table('licenses')->where('status', 'active')->count(), 'emailsToday' => DB::table('email_logs')->whereDate('created_at', today())->count(), 'frozenUsers' => DB::table('user_trust')->where('is_frozen', true)->count()]);
        } catch (\Exception $e) {
            return response()->json(['totalUsers' => 0, 'activeLicenses' => 0, 'emailsToday' => 0, 'frozenUsers' => 0]);
        }
    })->name('api.stats');
});

// =================== WEBHOOK ===================
Route::post('/webhook/paddle', [PaymentController::class, 'handleWebhook'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// =================== HEALTH ===================
Route::get('/health', function () {
    return response()->json(['status' => 'OK', 'timestamp' => now(), 'php_version' => PHP_VERSION, 'laravel_version' => app()->version(), 'environment' => app()->environment()]);
});

// =================== FALLBACK ===================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});