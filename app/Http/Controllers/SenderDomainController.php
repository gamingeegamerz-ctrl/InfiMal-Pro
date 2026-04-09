<?php

namespace App\Http\Controllers;

use App\Models\SenderDomain;
use App\Services\DomainVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SenderDomainController extends Controller
{
    public function __construct(private readonly DomainVerificationService $verification)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $domains = SenderDomain::where('user_id', $request->user()->id)
            ->orderByDesc('id')
            ->get();

        return response()->json(['data' => $domains]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'domain' => ['required', 'string', 'max:255', 'regex:/^([a-z0-9-]+\.)+[a-z]{2,}$/i'],
        ]);

        $payload = $this->verification->createDomainPayload(strtolower($validated['domain']));

        $domain = SenderDomain::updateOrCreate(
            ['user_id' => $request->user()->id, 'domain' => strtolower($validated['domain'])],
            [
                'verification_token' => $payload['verification_token'],
                'dns_records' => $payload['dns_records'],
                'is_verified' => false,
                'verified_at' => null,
            ]
        );

        return response()->json([
            'message' => 'Domain created. Add DNS records and verify.',
            'data' => $domain,
        ]);
    }

    public function verify(Request $request, SenderDomain $domain): JsonResponse
    {
        abort_unless($domain->user_id === $request->user()->id, 403);

        $verified = $this->verification->verify($domain);

        return response()->json([
            'verified' => $verified,
            'data' => $domain->fresh(),
            'message' => $verified
                ? 'Domain verified successfully.'
                : 'Verification TXT record not found yet. Try again after DNS propagation.',
        ], $verified ? 200 : 422);
    }

    public function destroy(Request $request, SenderDomain $domain): JsonResponse
    {
        abort_unless($domain->user_id === $request->user()->id, 403);
        $domain->delete();

        return response()->json(['message' => 'Domain removed.']);
    }
}
