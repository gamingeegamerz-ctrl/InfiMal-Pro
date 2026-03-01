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
                'sent_today' => SMTPAccount::ownedBy($userId)->sum('sent_today'),
                'sent_this_month' => 0,
                'total_sent' => 0,
                'success_rate' => 0,
            ],
        ]);
    }


    public function create()
    {
        return redirect()->route('smtp.index');
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
            'smtp_host' => $smtp->smtp_host,
            'smtp_port' => $smtp->smtp_port,
            'smtp_username' => $smtp->smtp_username,
            'smtp_password' => $smtp->smtp_password,
            'from_email' => $smtp->from_email,
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
