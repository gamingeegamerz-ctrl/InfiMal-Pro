<?php

namespace App\Http\Controllers;

use App\Models\SMTPAccount;
use App\Services\SmtpService;
use Illuminate\Http\Request;
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

        $activeSmtp = $smtpSettings->where('is_active', true)->count();
        $failedSmtp = $smtpSettings->where('is_active', false)->count();
        $smtpStatus = $smtpSettings->isEmpty() ? 'Not Connected' : ($activeSmtp > 0 ? 'Active' : 'Failed');

        $sentToday = DB::table('email_logs')
            ->where('user_id', $userId)
            ->whereDate('created_at', today())
            ->count();

        $sentThisMonth = DB::table('email_logs')
            ->where('user_id', $userId)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $totalSent = DB::table('email_logs')
            ->where('user_id', $userId)
            ->count();

        $failedCount = DB::table('email_logs')
            ->where('user_id', $userId)
            ->where('status', 'failed')
            ->count();

        $successRate = $totalSent > 0 ? round((($totalSent - $failedCount) / $totalSent) * 100, 2) : 0;

        return view('smtp.index', [
            'smtpSettings' => $smtpSettings,
            'totalSmtp' => $smtpSettings->count(),
            'activeSmtp' => $activeSmtp,
            'failedSmtp' => $failedSmtp,
            'smtpStatus' => $smtpStatus,
            'usageStats' => [
                'sent_today' => $sentToday,
                'sent_this_month' => $sentThisMonth,
                'total_sent' => $totalSent,
                'success_rate' => $successRate,
            ],
        ]);
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
        } else {
            $smtpModel->update(['is_active' => true]);
        }

        return response()->json($result, $result['success'] ? 200 : 422);
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

        if (!$result['success']) {
            $smtpModel->update(['is_active' => false]);
        } else {
            $smtpModel->update(['is_active' => true]);
        }

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
            return response()->json(['error' => 'SMTP not configured'], 404);
        }

        return response()->json([
            'host' => $smtp->host,
            'port' => $smtp->port,
            'username' => $smtp->username,
            'from_address' => $smtp->from_address,
            'from_name' => $smtp->from_name,
            'encryption' => $smtp->encryption,
        ]);
    }

    public function destroy(string $id)
    {
        SMTPAccount::ownedBy(Auth::id())->findOrFail($id)->delete();

        return back()->with('success', 'SMTP deleted.');
    }
}
