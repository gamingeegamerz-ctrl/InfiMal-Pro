<?php

namespace App\Http\Controllers;

use App\Models\SMTPAccount;
use App\Services\SmtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'usageStats' => [
                'sent_today' => 0,
                'sent_this_month' => 0,
                'total_sent' => 0,
                'success_rate' => 0,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:1000',
            'encryption' => 'required|in:tls,ssl,none',
            'from_email' => 'nullable|email|max:255',
            'from_address' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'daily_limit' => 'nullable|integer|min:1|max:100000',
            'per_minute_limit' => 'nullable|integer|min:1|max:10000',
            'warmup_enabled' => 'nullable|boolean',
        ]);

        $data['from_email'] = $data['from_email'] ?? $data['from_address'] ?? null;
        $this->smtpService->saveForUser(Auth::id(), $data);

        return back()->with('success', 'SMTP added successfully.');
    }

    public function update(Request $request, string $id)
    {
        $smtp = SMTPAccount::ownedBy(Auth::id())->findOrFail($id);

        $data = $request->validate([
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:1000',
            'encryption' => 'required|in:tls,ssl,none',
            'from_email' => 'nullable|email|max:255',
            'from_address' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'daily_limit' => 'nullable|integer|min:1|max:100000',
            'per_minute_limit' => 'nullable|integer|min:1|max:10000',
            'warmup_enabled' => 'nullable|boolean',
        ]);

        $data['from_email'] = $data['from_email'] ?? $data['from_address'] ?? $smtp->from_email;
        $this->smtpService->saveForUser(Auth::id(), $data, $smtp);

        return back()->with('success', 'SMTP updated successfully.');
    }

    public function test(Request $request, string $smtp)
    {
        $smtpModel = SMTPAccount::ownedBy(Auth::id())->findOrFail($smtp);
        $data = $request->validate(['email' => 'nullable|email']);
        $target = $data['email'] ?? Auth::user()->email;

        $result = $this->smtpService->testConnection($smtpModel, $target);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function setDefault(string $smtp)
    {
        $smtpModel = SMTPAccount::ownedBy(Auth::id())->findOrFail($smtp);
        $this->smtpService->setDefault($smtpModel);

        return back()->with('success', 'Default SMTP updated.');
    }

    public function destroy(string $id)
    {
        SMTPAccount::ownedBy(Auth::id())->findOrFail($id)->delete();

        return back()->with('success', 'SMTP deleted.');
    }
}
