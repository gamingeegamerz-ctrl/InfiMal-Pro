<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Get REAL analytics data
        $stats = $this->getRealAnalytics($user);
        
        return view('dashboard', $stats);
    }
    
    private function getRealAnalytics($user)
    {
        $data = [
            // Initialize all variables with default values
            'totalSubscribers' => 0,
            'activeSubscribers' => 0,
            'subscriberGrowth' => 0,
            'campaignsTotal' => 0,
            'campaignsSent' => 0,
            'campaignGrowth' => 0,
            'totalEmailsSent' => 0,
            'totalOpens' => 0,
            'totalClicks' => 0,
            'emailsToday' => 0,
            'opensToday' => 0,
            'clicksToday' => 0,
            'activeCampaigns' => 0,
            'openRate' => 0,
            'clickRate' => 0,
            'openGrowth' => 0,
            'clickGrowth' => 0,
            'bounceRate' => 0,
            'engagementRate' => 0,
            'engagementGrowth' => 0,
            'totalSpamReports' => 0,
            'unsubscribeRate' => 0,
            'recentCampaigns' => [],
            'recentSubscribers' => [],
            'growthLabels' => [],
            'growthData' => [],
            'campaignLabels' => [],
            'campaignOpens' => [],
            'campaignClicks' => [],
        ];
        
        try {
            // ========== SUBSCRIBER ANALYTICS ==========
            $subscribers = DB::table('subscribers')
                ->where('user_id', $user->id)
                ->selectRaw('COUNT(*) as total')
                ->selectRaw('SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active')
                ->first();
            
            $data['totalSubscribers'] = $subscribers->total ?? 0;
            $data['activeSubscribers'] = $subscribers->active ?? 0;
            
            // Subscriber growth (last 30 days vs previous 30 days)
            $currentPeriodStart = Carbon::now()->subDays(30);
            $previousPeriodStart = Carbon::now()->subDays(60);
            
            $currentPeriodSubscribers = DB::table('subscribers')
                ->where('user_id', $user->id)
                ->where('created_at', '>=', $currentPeriodStart)
                ->count();
            
            $previousPeriodSubscribers = DB::table('subscribers')
                ->where('user_id', $user->id)
                ->whereBetween('created_at', [$previousPeriodStart, $currentPeriodStart])
                ->count();
            
            if ($previousPeriodSubscribers > 0) {
                $data['subscriberGrowth'] = round(
                    (($currentPeriodSubscribers - $previousPeriodSubscribers) / $previousPeriodSubscribers) * 100,
                    1
                );
            }
            
            // ========== CAMPAIGN ANALYTICS ==========
            $campaigns = DB::table('campaigns')
                ->where('user_id', $user->id)
                ->selectRaw('COUNT(*) as total')
                ->selectRaw('SUM(CASE WHEN status = "sent" THEN 1 ELSE 0 END) as sent')
                ->selectRaw('SUM(CASE WHEN status IN ("scheduled", "sending") THEN 1 ELSE 0 END) as active')
                ->first();
            
            $data['campaignsTotal'] = $campaigns->total ?? 0;
            $data['campaignsSent'] = $campaigns->sent ?? 0;
            $data['activeCampaigns'] = $campaigns->active ?? 0;
            
            // Campaign growth
            $currentPeriodCampaigns = DB::table('campaigns')
                ->where('user_id', $user->id)
                ->where('created_at', '>=', $currentPeriodStart)
                ->count();
            
            $previousPeriodCampaigns = DB::table('campaigns')
                ->where('user_id', $user->id)
                ->whereBetween('created_at', [$previousPeriodStart, $currentPeriodStart])
                ->count();
            
            if ($previousPeriodCampaigns > 0) {
                $data['campaignGrowth'] = round(
                    (($currentPeriodCampaigns - $previousPeriodCampaigns) / $previousPeriodCampaigns) * 100,
                    1
                );
            }
            
            // ========== EMAIL ANALYTICS ==========
            if ($this->tableExists('email_logs')) {
                $emailStats = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->selectRaw('COUNT(*) as total')
                    ->selectRaw('SUM(CASE WHEN opened_at IS NOT NULL THEN 1 ELSE 0 END) as opens')
                    ->selectRaw('SUM(CASE WHEN clicked_at IS NOT NULL THEN 1 ELSE 0 END) as clicks')
                    ->selectRaw('SUM(CASE WHEN bounced_at IS NOT NULL THEN 1 ELSE 0 END) as bounces')
                    ->selectRaw('SUM(CASE WHEN spam_reported = 1 THEN 1 ELSE 0 END) as spam')
                    ->first();
                
                $data['totalEmailsSent'] = $emailStats->total ?? 0;
                $data['totalOpens'] = $emailStats->opens ?? 0;
                $data['totalClicks'] = $emailStats->clicks ?? 0;
                $data['totalSpamReports'] = $emailStats->spam ?? 0;
                $data['bounceRate'] = $emailStats->total > 0 ? 
                    round(($emailStats->bounces / $emailStats->total) * 100, 2) : 0;
                
                // Today's stats
                $today = Carbon::today();
                $todayStats = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->whereDate('created_at', $today)
                    ->selectRaw('COUNT(*) as emails')
                    ->selectRaw('SUM(CASE WHEN opened_at IS NOT NULL THEN 1 ELSE 0 END) as opens')
                    ->selectRaw('SUM(CASE WHEN clicked_at IS NOT NULL THEN 1 ELSE 0 END) as clicks')
                    ->first();
                
                $data['emailsToday'] = $todayStats->emails ?? 0;
                $data['opensToday'] = $todayStats->opens ?? 0;
                $data['clicksToday'] = $todayStats->clicks ?? 0;
                
                // Open and click rates
                $data['openRate'] = $data['totalEmailsSent'] > 0 ? 
                    round(($data['totalOpens'] / $data['totalEmailsSent']) * 100, 2) : 0;
                $data['clickRate'] = $data['totalOpens'] > 0 ? 
                    round(($data['totalClicks'] / $data['totalOpens']) * 100, 2) : 0;
                
                // Growth rates (current period vs previous period)
                $currentPeriodEmails = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->where('created_at', '>=', $currentPeriodStart)
                    ->count();
                
                $currentPeriodOpens = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->where('opened_at', '>=', $currentPeriodStart)
                    ->count();
                
                $currentPeriodClicks = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->where('clicked_at', '>=', $currentPeriodStart)
                    ->count();
                
                $previousPeriodEmails = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->whereBetween('created_at', [$previousPeriodStart, $currentPeriodStart])
                    ->count();
                
                $previousPeriodOpens = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->whereBetween('opened_at', [$previousPeriodStart, $currentPeriodStart])
                    ->count();
                
                $previousPeriodClicks = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->whereBetween('clicked_at', [$previousPeriodStart, $currentPeriodStart])
                    ->count();
                
                if ($previousPeriodEmails > 0) {
                    $data['openGrowth'] = round(
                        (($currentPeriodOpens - $previousPeriodOpens) / $previousPeriodOpens) * 100, 
                        1
                    );
                    $data['clickGrowth'] = round(
                        (($currentPeriodClicks - $previousPeriodClicks) / $previousPeriodClicks) * 100, 
                        1
                    );
                }
                
                // Engagement rate (opens + clicks) / emails sent
                $data['engagementRate'] = $data['totalEmailsSent'] > 0 ? 
                    round((($data['totalOpens'] + $data['totalClicks']) / $data['totalEmailsSent']) * 100, 2) : 0;
            }
            
            // ========== RECENT CAMPAIGNS ==========
            $data['recentCampaigns'] = DB::table('campaigns')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($campaign) {
                    $openRate = $campaign->sent_count > 0 ? 
                        round(($campaign->open_count / $campaign->sent_count) * 100, 1) : 0;
                    
                    return [
                        'id' => $campaign->id,
                        'name' => $campaign->name,
                        'subject' => $campaign->subject,
                        'status' => $campaign->status,
                        'sent_count' => $campaign->sent_count,
                        'open_count' => $campaign->open_count,
                        'click_count' => $campaign->click_count,
                        'open_rate' => $openRate,
                        'date' => Carbon::parse($campaign->created_at)->format('M d, Y'),
                        'status_class' => $this->getStatusClass($campaign->status)
                    ];
                })
                ->toArray();
            
            // ========== RECENT SUBSCRIBERS ==========
            $data['recentSubscribers'] = DB::table('subscribers')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($subscriber) {
                    return [
                        'email' => $subscriber->email,
                        'name' => ($subscriber->first_name ?? '') . ' ' . ($subscriber->last_name ?? ''),
                        'status' => $subscriber->status,
                        'status_class' => $this->getSubscriberStatusClass($subscriber->status)
                    ];
                })
                ->toArray();
            
            // ========== CHART DATA ==========
            // Last 7 days data for charts
            $data['growthLabels'] = [];
            $data['growthData'] = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dayStart = $date->copy()->startOfDay();
                $dayEnd = $date->copy()->endOfDay();
                
                $data['growthLabels'][] = $date->format('D');
                
                $daySubscribers = DB::table('subscribers')
                    ->where('user_id', $user->id)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->count();
                
                $data['growthData'][] = $daySubscribers;
            }
            
            // Campaign performance data (last 6 campaigns)
            $recentCampaigns = DB::table('campaigns')
                ->where('user_id', $user->id)
                ->where('status', 'sent')
                ->orderBy('sent_at', 'desc')
                ->limit(6)
                ->get();
            
            foreach ($recentCampaigns as $campaign) {
                $data['campaignLabels'][] = substr($campaign->name, 0, 15) . 
                    (strlen($campaign->name) > 15 ? '...' : '');
                $data['campaignOpens'][] = $campaign->open_count;
                $data['campaignClicks'][] = $campaign->click_count;
            }
            
            // Unsubscribe rate calculation
            $totalUnsubscribes = DB::table('subscribers')
                ->where('user_id', $user->id)
                ->where('status', 'unsubscribed')
                ->count();
            
            $data['unsubscribeRate'] = $data['totalSubscribers'] > 0 ? 
                round(($totalUnsubscribes / $data['totalSubscribers']) * 100, 2) : 0;
            
        } catch (\Exception $e) {
            \Log::error('Dashboard analytics error: ' . $e->getMessage());
        }
        
        return $data;
    }
    
    private function getStatusClass($status)
    {
        $classes = [
            'sent' => 'bg-green-500/20 text-green-400',
            'scheduled' => 'bg-blue-500/20 text-blue-400',
            'sending' => 'bg-yellow-500/20 text-yellow-400',
            'draft' => 'bg-gray-500/20 text-gray-400',
            'failed' => 'bg-red-500/20 text-red-400',
        ];
        
        return $classes[$status] ?? 'bg-gray-500/20 text-gray-400';
    }
    
    private function getSubscriberStatusClass($status)
    {
        $classes = [
            'active' => 'bg-green-500/20 text-green-400',
            'unsubscribed' => 'bg-red-500/20 text-red-400',
            'bounced' => 'bg-orange-500/20 text-orange-400',
        ];
        
        return $classes[$status] ?? 'bg-gray-500/20 text-gray-400';
    }
    
    private function tableExists($tableName)
    {
        try {
            DB::select("SELECT 1 FROM {$tableName} LIMIT 1");
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    // API endpoints for live updates and charts
    public function getLiveStats()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        $stats = [
            'emailsToday' => 0,
            'opensToday' => 0,
            'clicksToday' => 0,
            'activeCampaigns' => 0,
            'totalSubscribers' => 0,
        ];
        
        if ($user && $this->tableExists('subscribers')) {
            $stats['totalSubscribers'] = DB::table('subscribers')
                ->where('user_id', $user->id)
                ->count();
            
            $stats['activeCampaigns'] = DB::table('campaigns')
                ->where('user_id', $user->id)
                ->whereIn('status', ['scheduled', 'sending'])
                ->count();
            
            if ($this->tableExists('email_logs')) {
                $stats['emailsToday'] = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->whereDate('created_at', $today)
                    ->count();
                
                $stats['opensToday'] = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->whereDate('opened_at', $today)
                    ->count();
                
                $stats['clicksToday'] = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->whereDate('clicked_at', $today)
                    ->count();
            }
        }
        
        return response()->json($stats);
    }
    
    public function getGrowthChart(Request $request)
    {
        $user = Auth::user();
        $range = $request->get('range', '7d');
        
        $labels = [];
        $data = [];
        
        if ($range === '30d') {
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('M d');
                
                $dayStart = $date->copy()->startOfDay();
                $dayEnd = $date->copy()->endOfDay();
                
                $data[] = DB::table('subscribers')
                    ->where('user_id', $user->id)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->count();
            }
        } elseif ($range === '90d') {
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i * 7);
                $labels[] = 'Week ' . ($i + 1);
                
                $weekStart = $date->copy()->subDays(6)->startOfDay();
                $weekEnd = $date->copy()->endOfDay();
                
                $data[] = DB::table('subscribers')
                    ->where('user_id', $user->id)
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->count();
            }
        } else {
            // 7 days default
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('D');
                
                $dayStart = $date->copy()->startOfDay();
                $dayEnd = $date->copy()->endOfDay();
                
                $data[] = DB::table('subscribers')
                    ->where('user_id', $user->id)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->count();
            }
        }
        
        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
    
    public function getEmailChart(Request $request)
    {
        $user = Auth::user();
        $range = $request->get('range', '7d');
        
        $labels = [];
        $emails = [];
        $opens = [];
        $clicks = [];
        
        if ($range === '30d') {
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('M d');
                
                $dayStart = $date->copy()->startOfDay();
                $dayEnd = $date->copy()->endOfDay();
                
                $emails[] = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->count();
                
                $opens[] = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->whereBetween('opened_at', [$dayStart, $dayEnd])
                    ->count();
                
                $clicks[] = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->whereBetween('clicked_at', [$dayStart, $dayEnd])
                    ->count();
            }
        } else {
            // 7 days default
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('D');
                
                $dayStart = $date->copy()->startOfDay();
                $dayEnd = $date->copy()->endOfDay();
                
                $emails[] = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->count();
                
                $opens[] = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->whereBetween('opened_at', [$dayStart, $dayEnd])
                    ->count();
                
                $clicks[] = DB::table('email_logs')
                    ->where('user_id', $user->id)
                    ->whereBetween('clicked_at', [$dayStart, $dayEnd])
                    ->count();
            }
        }
        
        return response()->json([
            'labels' => $labels,
            'emails' => $emails,
            'opens' => $opens,
            'clicks' => $clicks
        ]);
    }


    public function getStats(AnalyticsService $analyticsService)
    {
        return response()->json($analyticsService->userStats(Auth::id()));
    }

    public function getRecentActivity(AnalyticsService $analyticsService)
    {
        return response()->json($analyticsService->userStats(Auth::id())['recent_activity']);
    }

    public function getLimits()
    {
        $smtp = \App\Models\SMTPAccount::ownedBy(Auth::id())
            ->orderByDesc('is_default')
            ->first();

        return response()->json([
            'daily_limit' => $smtp?->daily_limit ?? 0,
            'per_minute_limit' => $smtp?->per_minute_limit ?? 0,
            'warmup_enabled' => (bool) ($smtp?->warmup_enabled ?? false),
        ]);
    }

}
