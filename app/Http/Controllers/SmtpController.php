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
        $smtpSettings = SMTPAccount::ownedBy($userId)->latest()->get();

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
        ]);
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
        $smtp = SMTPAccount::ownedBy($userId)->orderByDesc('is_default')->latest('id')->first();

        if (!$smtp) {
            return 'Not Connected';
        }

        if (!$smtp->is_active) {
            return 'Failed';
        }

        $connection = @fsockopen($smtp->host, (int) $smtp->port, $errno, $errstr, 3);
        if (!$connection) {
            return 'Failed';
        }

        fclose($connection);

        return 'Active';
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
        $smtp = SMTPAccount::ownedBy(Auth::id())->findOrFail($id);
        return response()->json($smtp);
    }

    public function edit(string $id)
    {
        return $this->show($id);
    }

    public function update(Request $request, string $id)
    {
        $smtp = SMTPAccount::ownedBy(Auth::id())->findOrFail($id);

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
        $smtpModel = SMTPAccount::ownedBy(Auth::id())->findOrFail($smtp);
        $data = $request->validate(['email' => 'nullable|email']);
        $target = $data['email'] ?? Auth::user()->email;

        $result = $this->smtpService->testConnection($smtpModel, $target);

        if (!$result['success']) {
            $smtpModel->update(['is_active' => false]);
        }

        return response()->json($result, $result['success'] ? 200 : 422);
        $smtpModel->update(['is_active' => (bool) $result['success']]);

        return response()->json($result + ['status' => $result['success'] ? 'Active' : 'Failed'], $result['success'] ? 200 : 422);
    }

    public function toggle(string $smtp)
    {
        $smtpModel = SMTPAccount::ownedBy(Auth::id())->findOrFail($smtp);
        $smtpModel->update(['is_active' => !$smtpModel->is_active]);

        return response()->json(['success' => true, 'status' => $smtpModel->is_active ? 'Active' : 'Failed']);
    }

    public function toggle(string $smtp)
    {
        $smtpModel = SMTPAccount::ownedBy(Auth::id())->findOrFail($smtp);
        $smtpModel->update(['is_active' => !$smtpModel->is_active]);

        return response()->json(['success' => true, 'status' => $smtpModel->is_active ? 'Active' : 'Failed']);
    }

    public function setDefault(string $smtp)
    {
        $smtpModel = SMTPAccount::ownedBy(Auth::id())->findOrFail($smtp);
        $this->smtpService->setDefault($smtpModel);

        return back()->with('success', 'Default SMTP updated.');
    }

    public function verify(string $smtp)
    {
        $smtpModel = SMTPAccount::ownedBy(Auth::id())->findOrFail($smtp);
        $result = $this->smtpService->testConnection($smtpModel, Auth::user()->email);

        return response()->json([
            'verified' => $result['success'],
            'message' => $result['message'],
        ], $result['success'] ? 200 : 422);
    }

    public function getCredentials()
    {
        $smtp = SMTPAccount::ownedBy(Auth::id())
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
        SMTPAccount::ownedBy(Auth::id())->findOrFail($id)->delete();

        return back()->with('success', 'SMTP deleted.');
    }
}
