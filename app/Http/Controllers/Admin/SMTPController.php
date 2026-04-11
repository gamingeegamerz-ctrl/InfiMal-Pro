<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class SMTPController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display SMTP admin dashboard
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->is_admin) {
            abort(403, 'Unauthorized access');
        }

        // Get filter parameters
        $statusFilter = $request->get('status', 'all');
        $sortBy = $request->get('sort', 'reputation');
        $providerFilter = $request->get('provider', null);
        $search = $request->get('search', null);

        // Base query
        $query = DB::table('smtp_servers')
            ->select('*')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'disabled') {
            $query->where('is_active', false);
        }

        if ($providerFilter && in_array($providerFilter, ['gmail', 'outlook', 'yahoo', 'custom'])) {
            $query->where('provider', $providerFilter);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('host', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('provider', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        switch ($sortBy) {
            case 'reputation_low':
                $query->orderBy('reputation_score', 'asc');
                break;
            case 'usage':
                $query->orderBy('emails_today', 'desc');
                break;
            case 'created':
                $query->orderBy('created_at', 'desc');
                break;
            case 'health':
                // We'll sort by multiple factors for health
                $query->orderBy('is_active', 'desc')
                      ->orderBy('reputation_score', 'desc')
                      ->orderBy('emails_today', 'asc');
                break;
            case 'rotation':
                $query->orderBy('rotation_score', 'desc');
                break;
            default: // reputation
                $query->orderBy('reputation_score', 'desc');
        }

        $smtps = $query->paginate(20);

        // Calculate statistics
        $totalSMTPs = DB::table('smtp_servers')->count();
        $activeSMTPs = DB::table('smtp_servers')->where('is_active', true)->count();
        $disabledSMTPs = DB::table('smtp_servers')->where('is_active', false)->count();
        $riskySMTPs = 0;

        // Calculate health stats
        $allSMTPs = DB::table('smtp_servers')->get();
        $healthStats = ['excellent' => 0, 'good' => 0, 'risky' => 0, 'critical' => 0, 'disabled' => 0];
        
        foreach ($allSMTPs as $smtp) {
            $health = $this->calculateHealthScore($smtp);
            if (!$smtp->is_active) {
                $healthStats['disabled']++;
            } elseif ($health >= 80) {
                $healthStats['excellent']++;
            } elseif ($health >= 60) {
                $healthStats['good']++;
            } elseif ($health >= 40) {
                $healthStats['risky']++;
                $riskySMTPs++;
            } else {
                $healthStats['critical']++;
                $riskySMTPs++;
            }
        }

        // Calculate average reputation
        $avgReputation = DB::table('smtp_servers')->where('is_active', true)->avg('reputation_score') ?? 0;
        $avgReputation = round($avgReputation, 1);

        // Calculate emails stats
        $emailsToday = DB::table('email_logs')->whereDate('created_at', today())->count();
        $emailsPerHour = DB::table('email_logs')->where('created_at', '>=', now()->subHour())->count();
        $totalEmailsToday = $emailsToday;
        $avgEmailsPerSMTP = $totalSMTPs > 0 ? round($emailsToday / $totalSMTPs, 1) : 0;

        // Provider stats
        $providerStats = [
            'gmail' => DB::table('smtp_servers')->where('provider', 'gmail')->count(),
            'outlook' => DB::table('smtp_servers')->where('provider', 'outlook')->count(),
            'yahoo' => DB::table('smtp_servers')->where('provider', 'yahoo')->count(),
            'custom' => DB::table('smtp_servers')->where('provider', 'custom')->orWhereNull('provider')->count()
        ];

        // Failure stats (last 24 hours)
        $failureStats = [
            'soft_bounces' => DB::table('email_logs')
                ->where('status', 'bounced')
                ->where('created_at', '>=', now()->subDay())
                ->count(),
            'hard_bounces' => DB::table('smtp_servers')->sum('hard_bounces_24h') ?? 0,
            'spam_complaints' => DB::table('smtp_servers')->sum('spam_complaints_24h') ?? 0,
            'auth_errors' => DB::table('email_logs')
                ->where(function($q) {
                    $q->where('error_message', 'like', '%auth%')
                      ->orWhere('error_message', 'like', '%login%')
                      ->orWhere('error_message', 'like', '%credential%');
                })
                ->where('created_at', '>=', now()->subDay())
                ->count(),
            'temp_failures' => DB::table('email_logs')
                ->where('status', 'failed')
                ->where('created_at', '>=', now()->subDay())
                ->count()
        ];

        // Calculate rates
        $bounceRate = $emailsToday > 0 ? round(($failureStats['soft_bounces'] / max($emailsToday, 1)) * 100, 1) : 0;
        $spamRate = $emailsToday > 0 ? round(($failureStats['spam_complaints'] / max($emailsToday, 1)) * 100, 1) : 0;

        // System health calculation
        $systemHealth = 100;
        if ($bounceRate > 5) $systemHealth -= 20;
        if ($avgReputation < 70) $systemHealth -= 15;
        if ($totalSMTPs > 0 && ($riskySMTPs / $totalSMTPs) > 0.3) $systemHealth -= 15;
        $systemHealth = max(30, $systemHealth);

        // Calculate average rotation score
        $avgRotationScore = DB::table('smtp_servers')->where('is_active', true)->avg('rotation_score') ?? 0;
        $avgRotationScore = round($avgRotationScore, 1);

        // Calculate rotation success rate (simplified)
        $totalSentToday = DB::table('email_logs')
            ->whereDate('created_at', today())
            ->whereIn('status', ['sent', 'delivered'])
            ->count();
        $totalAttemptedToday = DB::table('email_logs')
            ->whereDate('created_at', today())
            ->count();
        $rotationSuccessRate = $totalAttemptedToday > 0 ? round(($totalSentToday / $totalAttemptedToday) * 100, 1) : 0;

        // Calculate system stability
        $systemStability = 100;
        if ($failureStats['temp_failures'] > 0) {
            $systemStability = max(50, 100 - (($failureStats['temp_failures'] / max($emailsToday, 1)) * 100));
        }

        // Pass all data to view
        return view('admin.smtp', [
            'smtps' => $smtps,
            'totalSMTPs' => $totalSMTPs,
            'activeSMTPs' => $activeSMTPs,
            'disabledSMTPs' => $disabledSMTPs,
            'riskySMTPs' => $riskySMTPs,
            'healthStats' => $healthStats,
            'avgReputation' => $avgReputation,
            'emailsToday' => $emailsToday,
            'emailsPerHour' => $emailsPerHour,
            'avgEmailsPerSMTP' => $avgEmailsPerSMTP,
            'providerStats' => $providerStats,
            'failureStats' => $failureStats,
            'bounceRate' => $bounceRate,
            'spamRate' => $spamRate,
            'systemHealth' => $systemHealth,
            'systemStability' => $systemStability,
            'avgRotationScore' => $avgRotationScore,
            'rotationSuccessRate' => $rotationSuccessRate,
            'totalEmailsToday' => $totalEmailsToday,
            
            // Helper functions data
            'now' => now(),
        ]);
    }

    /**
     * Get real-time SMTP statistics
     */
    public function stats(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $totalSMTPs = DB::table('smtp_servers')->count();
            $activeSMTPs = DB::table('smtp_servers')->where('is_active', true)->count();
            $disabledSMTPs = DB::table('smtp_servers')->where('is_active', false)->count();
            
            // Calculate risky SMTPs
            $allSMTPs = DB::table('smtp_servers')->get();
            $riskySMTPs = 0;
            $healthStats = ['excellent' => 0, 'good' => 0, 'risky' => 0, 'critical' => 0, 'disabled' => 0];
            
            foreach ($allSMTPs as $smtp) {
                $health = $this->calculateHealthScore($smtp);
                if (!$smtp->is_active) {
                    $healthStats['disabled']++;
                } elseif ($health >= 80) {
                    $healthStats['excellent']++;
                } elseif ($health >= 60) {
                    $healthStats['good']++;
                } elseif ($health >= 40) {
                    $healthStats['risky']++;
                    $riskySMTPs++;
                } else {
                    $healthStats['critical']++;
                    $riskySMTPs++;
                }
            }
            
            $avgReputation = DB::table('smtp_servers')->where('is_active', true)->avg('reputation_score') ?? 0;
            $avgReputation = round($avgReputation, 1);
            
            $emailsToday = DB::table('email_logs')->whereDate('created_at', today())->count();
            $emailsPerHour = DB::table('email_logs')->where('created_at', '>=', now()->subHour())->count();
            
            $totalEmailsToday = $emailsToday;
            $avgEmailsPerSMTP = $totalSMTPs > 0 ? round($emailsToday / $totalSMTPs, 1) : 0;
            
            // Provider stats
            $providerStats = [
                'gmail' => DB::table('smtp_servers')->where('provider', 'gmail')->count(),
                'outlook' => DB::table('smtp_servers')->where('provider', 'outlook')->count(),
                'yahoo' => DB::table('smtp_servers')->where('provider', 'yahoo')->count(),
                'custom' => DB::table('smtp_servers')->where('provider', 'custom')->orWhereNull('provider')->count()
            ];
            
            // Failure stats
            $failureStats = [
                'soft_bounces' => DB::table('email_logs')
                    ->where('status', 'bounced')
                    ->where('created_at', '>=', now()->subDay())
                    ->count(),
                'hard_bounces' => DB::table('smtp_servers')->sum('hard_bounces_24h') ?? 0,
                'spam_complaints' => DB::table('smtp_servers')->sum('spam_complaints_24h') ?? 0,
                'auth_errors' => DB::table('email_logs')
                    ->where(function($q) {
                        $q->where('error_message', 'like', '%auth%')
                          ->orWhere('error_message', 'like', '%login%')
                          ->orWhere('error_message', 'like', '%credential%');
                    })
                    ->where('created_at', '>=', now()->subDay())
                    ->count(),
                'temp_failures' => DB::table('email_logs')
                    ->where('status', 'failed')
                    ->where('created_at', '>=', now()->subDay())
                    ->count()
            ];
            
            $bounceRate = $emailsToday > 0 ? round(($failureStats['soft_bounces'] / max($emailsToday, 1)) * 100, 1) : 0;
            $spamRate = $emailsToday > 0 ? round(($failureStats['spam_complaints'] / max($emailsToday, 1)) * 100, 1) : 0;
            
            // System health calculation
            $systemHealth = 100;
            if ($bounceRate > 5) $systemHealth -= 20;
            if ($avgReputation < 70) $systemHealth -= 15;
            if ($totalSMTPs > 0 && ($riskySMTPs / $totalSMTPs) > 0.3) $systemHealth -= 15;
            $systemHealth = max(30, $systemHealth);
            
            // Rotation success rate
            $totalSentToday = DB::table('email_logs')
                ->whereDate('created_at', today())
                ->whereIn('status', ['sent', 'delivered'])
                ->count();
            $totalAttemptedToday = DB::table('email_logs')
                ->whereDate('created_at', today())
                ->count();
            $rotationSuccessRate = $totalAttemptedToday > 0 ? round(($totalSentToday / $totalAttemptedToday) * 100, 1) : 0;
            
            // System stability
            $systemStability = 100;
            if ($failureStats['temp_failures'] > 0) {
                $systemStability = max(50, 100 - (($failureStats['temp_failures'] / max($emailsToday, 1)) * 100));
            }
            
            return response()->json([
                'success' => true,
                'totalSMTPs' => $totalSMTPs,
                'activeSMTPs' => $activeSMTPs,
                'disabledSMTPs' => $disabledSMTPs,
                'riskySMTPs' => $riskySMTPs,
                'activePercent' => $totalSMTPs > 0 ? round(($activeSMTPs / $totalSMTPs) * 100, 1) : 0,
                'avgReputation' => $avgReputation,
                'emailsToday' => $emailsToday,
                'emailsPerHour' => $emailsPerHour,
                'avgEmailsPerSMTP' => $avgEmailsPerSMTP,
                'usagePercent' => min(($emailsPerHour / ($totalSMTPs * 50)) * 100, 100), // Assuming 50 emails/hour per SMTP
                'systemHealth' => $systemHealth,
                'healthStats' => $healthStats,
                'providerStats' => $providerStats,
                'failureStats' => $failureStats,
                'bounceRate' => $bounceRate,
                'spamRate' => $spamRate,
                'rotationSuccessRate' => $rotationSuccessRate,
                'totalEmailsToday' => $totalEmailsToday,
                'systemStability' => $systemStability,
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching SMTP stats',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get SMTP details
     */
    public function showDetails($id)
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $smtp = DB::table('smtp_servers')->find($id);
            if (!$smtp) {
                return response()->json(['success' => false, 'message' => 'SMTP not found'], 404);
            }

            // Calculate health score
            $health = $this->calculateHealthScore($smtp);
            $healthLabel = 'Excellent';
            $healthClass = 'health-excellent';
            
            if ($health >= 80) {
                $healthLabel = 'Excellent';
                $healthClass = 'health-excellent';
            } elseif ($health >= 60) {
                $healthLabel = 'Good';
                $healthClass = 'health-good';
            } elseif ($health >= 40) {
                $healthLabel = 'Risky';
                $healthClass = 'health-risky';
            } else {
                $healthLabel = 'Critical';
                $healthClass = 'health-critical';
            }
            
            if (!$smtp->is_active) {
                $healthLabel = 'Disabled';
                $healthClass = 'health-disabled';
            }

            // Get action logs
            $logs = DB::table('smtp_action_logs')
                ->where('smtp_id', $id)
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->map(function($log) {
                    return [
                        'action' => $log->action,
                        'reason' => $log->reason,
                        'triggered_by' => $log->triggered_by,
                        'created_at_formatted' => Carbon::parse($log->created_at)->format('M d, H:i'),
                    ];
                });

            // Get bounce stats
            $softBounces = $smtp->soft_bounces_24h ?? 0;
            $hardBounces = $smtp->hard_bounces_24h ?? 0;
            $spamComplaints = $smtp->spam_complaints_24h ?? 0;
            $authErrors = $smtp->auth_errors_24h ?? 0;

            // Determine if next eligible
            $nextEligible = true;
            if ($smtp->emails_this_hour >= $smtp->hourly_limit) {
                $nextEligible = false;
            } elseif (!$smtp->is_active) {
                $nextEligible = false;
            } elseif (($smtp->reputation_score ?? 100) < 30) {
                $nextEligible = false;
            }

            // Provider class
            $providerClasses = [
                'gmail' => 'bg-red-500/20 text-red-400',
                'outlook' => 'bg-blue-500/20 text-blue-400',
                'yahoo' => 'bg-purple-500/20 text-purple-400',
                'custom' => 'bg-gray-500/20 text-gray-400'
            ];
            $providerClass = $providerClasses[$smtp->provider ?? 'custom'] ?? 'bg-gray-500/20 text-gray-400';

            // Warmup class
            $warmupClasses = [
                'new' => 'bg-blue-500/20 text-blue-400',
                'warming' => 'bg-yellow-500/20 text-yellow-400',
                'stable' => 'bg-green-500/20 text-green-400',
                'paused' => 'bg-gray-500/20 text-gray-400'
            ];
            $warmupClass = $warmupClasses[$smtp->warmup_stage ?? 'stable'] ?? 'bg-gray-500/20 text-gray-400';

            // Mask username
            $maskedUsername = $this->maskEmail($smtp->username);

            return response()->json([
                'success' => true,
                'smtp' => [
                    'id' => $smtp->id,
                    'user_id' => $smtp->user_id,
                    'host' => $smtp->host,
                    'port' => $smtp->port,
                    'username' => $smtp->username,
                    'masked_username' => $maskedUsername,
                    'provider' => $smtp->provider ?? 'custom',
                    'provider_class' => $providerClass,
                    'is_active' => (bool) $smtp->is_active,
                    'auto_disabled' => (bool) ($smtp->auto_disabled ?? false),
                    'reputation' => $smtp->reputation_score ?? 0,
                    'health_score' => $health,
                    'health_label' => $healthLabel,
                    'health_class' => $healthClass,
                    'emails_today' => $smtp->emails_today ?? 0,
                    'emails_this_hour' => $smtp->emails_this_hour ?? 0,
                    'hourly_limit' => $smtp->hourly_limit ?? 50,
                    'total_emails_sent' => $smtp->total_emails_sent ?? 0,
                    'last_used_at' => $smtp->last_used_at,
                    'last_used_formatted' => $smtp->last_used_at ? Carbon::parse($smtp->last_used_at)->format('M d, H:i') : 'Never',
                    'warmup_stage' => $smtp->warmup_stage ?? 'stable',
                    'warmup_class' => $warmupClass,
                    'rotation_score' => $smtp->rotation_score ?? 0,
                    'last_skipped_reason' => $smtp->last_skipped_reason,
                    'next_eligible' => $nextEligible,
                    'soft_bounces' => $softBounces,
                    'hard_bounces' => $hardBounces,
                    'spam_complaints' => $spamComplaints,
                    'auth_errors' => $authErrors,
                    'created_at' => $smtp->created_at,
                ],
                'logs' => $logs
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching SMTP details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enable SMTP
     */
    public function enable($id)
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            DB::table('smtp_servers')
                ->where('id', $id)
                ->update([
                    'is_active' => true,
                    'auto_disabled' => false,
                    'updated_at' => now()
                ]);

            // Log the action
            DB::table('smtp_action_logs')->insert([
                'smtp_id' => $id,
                'action' => 'SMTP Enabled',
                'reason' => 'Manually enabled by admin',
                'triggered_by' => 'admin',
                'details' => json_encode(['admin_id' => $user->id]),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'SMTP enabled successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error enabling SMTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Disable SMTP
     */
    public function disable($id)
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            DB::table('smtp_servers')
                ->where('id', $id)
                ->update([
                    'is_active' => false,
                    'updated_at' => now()
                ]);

            // Log the action
            DB::table('smtp_action_logs')->insert([
                'smtp_id' => $id,
                'action' => 'SMTP Disabled',
                'reason' => 'Manually disabled by admin',
                'triggered_by' => 'admin',
                'details' => json_encode(['admin_id' => $user->id]),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'SMTP disabled successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error disabling SMTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset reputation
     */
    public function resetReputation($id)
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            DB::table('smtp_servers')
                ->where('id', $id)
                ->update([
                    'reputation_score' => 100,
                    'soft_bounces_24h' => 0,
                    'hard_bounces_24h' => 0,
                    'spam_complaints_24h' => 0,
                    'auth_errors_24h' => 0,
                    'updated_at' => now()
                ]);

            // Log the action
            DB::table('smtp_action_logs')->insert([
                'smtp_id' => $id,
                'action' => 'Reputation Reset',
                'reason' => 'Manually reset to 100% by admin',
                'triggered_by' => 'admin',
                'details' => json_encode(['admin_id' => $user->id]),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reputation reset to 100%'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error resetting reputation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset warmup
     */
    public function resetWarmup($id)
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            DB::table('smtp_servers')
                ->where('id', $id)
                ->update([
                    'warmup_stage' => 'new',
                    'emails_today' => 0,
                    'emails_this_hour' => 0,
                    'updated_at' => now()
                ]);

            // Log the action
            DB::table('smtp_action_logs')->insert([
                'smtp_id' => $id,
                'action' => 'Warmup Reset',
                'reason' => 'Manually reset to "new" stage by admin',
                'triggered_by' => 'admin',
                'details' => json_encode(['admin_id' => $user->id]),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Warmup reset to new stage'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error resetting warmup',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear counters
     */
    public function clearCounters($id)
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            DB::table('smtp_servers')
                ->where('id', $id)
                ->update([
                    'emails_today' => 0,
                    'emails_this_hour' => 0,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Daily counters cleared'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing counters',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pause warmup
     */
    public function pauseWarmup($id)
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            DB::table('smtp_servers')
                ->where('id', $id)
                ->update([
                    'warmup_stage' => 'paused',
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Warmup paused'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error pausing warmup',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test connection
     */
    public function testConnection($id)
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $smtp = DB::table('smtp_servers')->find($id);
            if (!$smtp) {
                return response()->json(['success' => false, 'message' => 'SMTP not found'], 404);
            }

            // Here you would implement actual SMTP connection test
            // This is a simplified version
            $host = $smtp->host;
            $port = $smtp->port;
            
            // Simulate connection test
            $connected = @fsockopen($host, $port, $errno, $errstr, 5);
            
            if ($connected) {
                fclose($connected);
                
                // Log successful test
                DB::table('smtp_action_logs')->insert([
                    'smtp_id' => $id,
                    'action' => 'Connection Test',
                    'reason' => 'Test initiated by admin',
                    'triggered_by' => 'admin',
                    'details' => json_encode(['result' => 'success', 'admin_id' => $user->id]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Connection test successful'
                ]);
            } else {
                // Log failed test
                DB::table('smtp_action_logs')->insert([
                    'smtp_id' => $id,
                    'action' => 'Connection Test',
                    'reason' => 'Test initiated by admin',
                    'triggered_by' => 'admin',
                    'details' => json_encode(['result' => 'failed', 'error' => $errstr, 'admin_id' => $user->id]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Connection failed: ' . $errstr
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error testing connection: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete SMTP
     */
    public function destroy($id)
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Check if SMTP exists
            $smtp = DB::table('smtp_servers')->find($id);
            if (!$smtp) {
                return response()->json(['success' => false, 'message' => 'SMTP not found'], 404);
            }

            // First delete related logs
            DB::table('smtp_action_logs')->where('smtp_id', $id)->delete();

            // Then delete the SMTP
            DB::table('smtp_servers')->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'SMTP deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting SMTP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store new SMTP
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'host' => 'required|string|max:255',
                'port' => 'required|integer',
                'username' => 'required|string|max:255',
                'password' => 'required|string',
                'provider' => 'required|in:gmail,outlook,yahoo,custom',
                'hourly_limit' => 'integer|min:1|max:1000',
                'warmup_stage' => 'in:new,warming,stable,paused'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Encrypt password
            $encryptedPassword = Crypt::encryptString($request->password);

            // Insert new SMTP
            $smtpId = DB::table('smtp_servers')->insertGetId([
                'user_id' => $request->user_id,
                'host' => $request->host,
                'port' => $request->port,
                'username' => $request->username,
                'password' => $encryptedPassword,
                'provider' => $request->provider,
                'is_active' => true,
                'reputation_score' => 100,
                'auto_disabled' => false,
                'emails_today' => 0,
                'emails_this_hour' => 0,
                'total_emails_sent' => 0,
                'hourly_limit' => $request->hourly_limit ?? 50,
                'warmup_stage' => $request->warmup_stage ?? 'stable',
                'rotation_score' => 80,
                'soft_bounces_24h' => 0,
                'hard_bounces_24h' => 0,
                'spam_complaints_24h' => 0,
                'auth_errors_24h' => 0,
                'last_used_at' => null,
                'last_skipped_reason' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log the action
            DB::table('smtp_action_logs')->insert([
                'smtp_id' => $smtpId,
                'action' => 'SMTP Created',
                'reason' => 'Manually added by admin',
                'triggered_by' => 'admin',
                'details' => json_encode(['admin_id' => $user->id]),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'SMTP added successfully',
                'smtp_id' => $smtpId
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding SMTP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Emergency stop all SMTPs
     */
    public function emergencyStopAll()
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $affected = DB::table('smtp_servers')
                ->where('is_active', true)
                ->update([
                    'is_active' => false,
                    'auto_disabled' => true,
                    'updated_at' => now()
                ]);

            // Log the emergency action
            DB::table('smtp_action_logs')->insert([
                'smtp_id' => 0, // 0 means system-wide
                'action' => 'EMERGENCY STOP ALL',
                'reason' => 'Emergency stop initiated by admin',
                'triggered_by' => 'admin',
                'details' => json_encode([
                    'admin_id' => $user->id,
                    'affected_smtps' => $affected,
                    'timestamp' => now()->toISOString()
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Emergency stop activated. {$affected} SMTPs disabled."
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error in emergency stop: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset all reputations
     */
    public function resetAllReputations()
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $affected = DB::table('smtp_servers')
                ->update([
                    'reputation_score' => 100,
                    'soft_bounces_24h' => 0,
                    'hard_bounces_24h' => 0,
                    'spam_complaints_24h' => 0,
                    'auth_errors_24h' => 0,
                    'updated_at' => now()
                ]);

            // Log the action
            DB::table('smtp_action_logs')->insert([
                'smtp_id' => 0,
                'action' => 'RESET ALL REPUTATIONS',
                'reason' => 'All reputations reset by admin',
                'triggered_by' => 'admin',
                'details' => json_encode([
                    'admin_id' => $user->id,
                    'affected_smtps' => $affected
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => "All {$affected} SMTP reputations reset to 100%"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error resetting reputations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all counters
     */
    public function clearAllCounters()
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $affected = DB::table('smtp_servers')
                ->update([
                    'emails_today' => 0,
                    'emails_this_hour' => 0,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => "Counters cleared for {$affected} SMTPs"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing counters: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pause all warmup
     */
    public function pauseAllWarmup()
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $affected = DB::table('smtp_servers')
                ->whereIn('warmup_stage', ['new', 'warming'])
                ->update([
                    'warmup_stage' => 'paused',
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => "Warmup paused for {$affected} SMTPs"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error pausing warmup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper: Calculate health score
     */
    private function calculateHealthScore($smtp)
    {
        $score = 0;
        
        // Reputation contributes 40%
        $score += ($smtp->reputation_score ?? 0) * 0.4;
        
        // Recent failures reduce score
        $failures = ($smtp->soft_bounces_24h ?? 0) + ($smtp->hard_bounces_24h ?? 0) * 2;
        $score -= min($failures * 5, 30);
        
        // Active status adds 20
        if ($smtp->is_active) $score += 20;
        
        // Recent usage bonus (used in last 24h)
        if ($smtp->last_used_at && now()->diffInHours($smtp->last_used_at) < 24) {
            $score += 10;
        }
        
        // Warmup stage bonus
        if (($smtp->warmup_stage ?? 'new') === 'stable') {
            $score += 10;
        }
        
        return max(0, min(100, $score));
    }

    /**
     * Helper: Mask email
     */
    private function maskEmail($email)
    {
        if (!str_contains($email, '@')) return $email;
        [$local, $domain] = explode('@', $email);
        if (strlen($local) <= 2) return '**@' . $domain;
        return substr($local, 0, 2) . '****' . substr($local, -1) . '@' . $domain;
    }
}
