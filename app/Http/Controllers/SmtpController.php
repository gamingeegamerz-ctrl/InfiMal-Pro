<?php

namespace App\Http\Controllers;

use App\Models\SMTPAccount;
use App\Services\SmtpService;
use Illuminate\Http\Request;
use App\Models\EmailLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SmtpController extends Controller
{
    public function __construct(private readonly SmtpService $smtpService)
    {
    }

    public function index()
    {
        $userId = Auth::id();
        $smtpSettings = SMTPAccount::ownedBy($userId)->userOwned()->latest()->get();

        return view('smtp.index', [
            'smtpSettings' => $smtpSettings,
            'totalSmtp' => $smtpSettings->count(),
            'activeSmtp' => $smtpSettings->where('is_active', true)->count(),
            'smtpStatus' => $this->resolveSmtpStatus($userId),
            'usageStats' => [
                'sent_today' => DB::table('email_logs')->where('user_id', $userId)->whereDate('created_at', today())->count(),
                'sent_this_month' => DB::table('email_logs')->where('user_id', $userId)->whereMonth('created_at', now()->month)->count(),
                'total_sent' => DB::table('email_logs')->where('user_id', $userId)->count(),
                'success_rate' => $this->successRate($userId),
            ],
            'domainHealth' => $this->domainHealthSnapshot($smtpSettings->first()),
        ]);
    }

    private function domainHealthSnapshot(?SMTPAccount $smtp): array
    {
        if (! $smtp || ! $smtp->from_address) {
            return [
                'domain' => null,
                'spf' => false,
                'dkim' => false,
                'dmarc' => false,
                'reputation_score' => null,
                'warmup_stage' => 'not_started',
            ];
        }

        $domain = substr(strrchr($smtp->from_address, '@') ?: '', 1);
        if (! $domain) {
            return [
                'domain' => null,
                'spf' => false,
                'dkim' => false,
                'dmarc' => false,
                'reputation_score' => null,
                'warmup_stage' => 'not_started',
            ];
        }

        $txt = @dns_get_record($domain, DNS_TXT) ?: [];
        $dmarc = @dns_get_record('_dmarc.' . $domain, DNS_TXT) ?: [];
        $dkim = @dns_get_record('default._domainkey.' . $domain, DNS_TXT) ?: [];

        $hasSpf = collect($txt)->contains(fn ($record) => str_contains(($record['txt'] ?? ''), 'v=spf1'));
        $hasDmarc = collect($dmarc)->contains(fn ($record) => str_contains(($record['txt'] ?? ''), 'v=DMARC1'));
        $hasDkim = ! empty($dkim);

        $score = max(0, min(100, (int) round((float) ($smtp->reputation_score ?? 100))));

        return [
            'domain' => $domain,
            'spf' => $hasSpf,
            'dkim' => $hasDkim,
            'dmarc' => $hasDmarc,
            'reputation_score' => $score,
            'warmup_stage' => $smtp->warmup_enabled ? ($score >= 80 ? 'stage_3_stable' : ($score >= 60 ? 'stage_2_ramping' : 'stage_1_new')) : 'disabled',
        ];
    }


    private function successRate(int $userId): float
    {
        $total = DB::table('email_logs')->where('user_id', $userId)->count();
        if ($total === 0) {
            return 0;
        }

        $success = DB::table('email_logs')->where('user_id', $userId)->whereIn('status', ['sent', 'delivered'])->count();

        return round(($success / $total) * 100, 2);
    }

    private function resolveSmtpStatus(int $userId): string
    {
        $smtp = SMTPAccount::ownedBy($userId)->userOwned()->orderByDesc('is_default')->latest('id')->first();

        if (!$smtp) {
            return 'Not Connected';
        }

        if (!$smtp->is_active) {
            return 'Failed';
        }

        if (($smtp->validation_status ?? 'invalid') === 'invalid') {
            return 'Failed';
        }

        return ($smtp->validation_status ?? 'invalid') === 'risky' ? 'Risky' : 'Active';
    }

    public function health()
    {
        return response()->json(['status' => $this->resolveSmtpStatus(Auth::id())]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:1000',
            'encryption' => 'required|in:tls,ssl,none',
            'from_address' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'daily_limit' => 'nullable|integer|min:1|max:100000',
            'per_minute_limit' => 'nullable|integer|min:1|max:10000',
            'warmup_enabled' => 'nullable|boolean',
        ]);

        $this->smtpService->saveForUser(Auth::id(), $data);

        return back()->with('success', 'SMTP added successfully.');
    }

    public function show(string $id)
    {
        $smtp = SMTPAccount::ownedBy(Auth::id())->userOwned()->findOrFail($id);
        return response()->json($smtp);
    }

    public function edit(string $id)
    {
        return $this->show($id);
    }

    public function update(Request $request, string $id)
    {
        $smtp = SMTPAccount::ownedBy(Auth::id())->userOwned()->findOrFail($id);

        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:1000',
            'encryption' => 'required|in:tls,ssl,none',
            'from_address' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'daily_limit' => 'nullable|integer|min:1|max:100000',
            'per_minute_limit' => 'nullable|integer|min:1|max:10000',
            'warmup_enabled' => 'nullable|boolean',
        ]);

        $this->smtpService->saveForUser(Auth::id(), $data, $smtp);

        return back()->with('success', 'SMTP updated successfully.');
    }

    public function test(Request $request, string $smtp)
    {
        $smtpModel = SMTPAccount::ownedBy(Auth::id())->userOwned()->findOrFail($smtp);
        $data = $request->validate(['email' => 'nullable|email']);
        $target = $data['email'] ?? Auth::user()->email;

        $result = $this->smtpService->testConnection($smtpModel, $target);

        $smtpModel->update(['is_active' => (bool) $result['success']]);

        return response()->json($result + ['status' => $result['success'] ? 'Active' : 'Failed'], $result['success'] ? 200 : 422);
    }

    public function toggle(string $smtp)
    {
        $smtpModel = SMTPAccount::ownedBy(Auth::id())->userOwned()->findOrFail($smtp);
        $smtpModel->update(['is_active' => !$smtpModel->is_active]);

        return response()->json(['success' => true, 'status' => $smtpModel->is_active ? 'Active' : 'Failed']);
    }

    public function setDefault(string $smtp)
    {
        $smtpModel = SMTPAccount::ownedBy(Auth::id())->userOwned()->findOrFail($smtp);
        $this->smtpService->setDefault($smtpModel);

        return back()->with('success', 'Default SMTP updated.');
    }

    public function verify(string $smtp)
    {
        $smtpModel = SMTPAccount::ownedBy(Auth::id())->userOwned()->findOrFail($smtp);
        $result = $this->smtpService->testConnection($smtpModel, Auth::user()->email);
        $smtpModel->update(['is_active' => $result['success']]);

        return response()->json([
            'verified' => $result['success'],
            'message' => $result['message'],
        ], $result['success'] ? 200 : 422);
    }

    public function getCredentials()
    {
        $smtp = SMTPAccount::ownedBy(Auth::id())
            ->userOwned()
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->latest('id')
            ->first();

        if (!$smtp) {
            return response()->json(['error' => 'SMTP not configured', 'status' => 'Not Connected'], 404);
        }

        return response()->json([
            'host' => $smtp->host,
            'port' => $smtp->port,
            'username' => $smtp->username,
            'from_address' => $smtp->from_address,
            'from_name' => $smtp->from_name,
            'encryption' => $smtp->encryption,
            'status' => $this->resolveSmtpStatus(Auth::id()),
        ]);
    }

    public function destroy(string $id)
    {
        SMTPAccount::ownedBy(Auth::id())->userOwned()->findOrFail($id)->delete();

        return back()->with('success', 'SMTP deleted.');
    }
}
