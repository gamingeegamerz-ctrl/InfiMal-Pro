<?php

namespace App\Http\Controllers;

use App\Services\SesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DomainVerificationController extends Controller
{
    public function form(Request $request): View
    {
        return view('domains.verify', ['user' => $request->user()]);
    }

    public function submit(Request $request, SesService $sesService): RedirectResponse
    {
        $validated = $request->validate([
            'domain' => ['required', 'string', 'max:255', 'regex:/^(?!-)[A-Za-z0-9.-]+(?<!-)$/'],
        ]);

        try {
            $domain = strtolower($validated['domain']);
            $token = $sesService->verifyDomain($domain);

            $request->user()->forceFill([
                'domain' => $domain,
                'verification_token' => $token,
                'domain_verified' => false,
            ])->save();

            return redirect()->route('domains.dns')->with('status', 'Domain verification started. Add the DNS record below.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withErrors(['domain' => 'Could not start SES verification: '.$e->getMessage()])->withInput();
        }
    }

    public function dns(Request $request): View
    {
        $user = $request->user();

        return view('domains.dns', [
            'user' => $user,
            'txtName' => $user->domain ? '_amazonses.'.$user->domain : null,
            'txtValue' => $user->verification_token,
        ]);
    }

    public function check(Request $request, SesService $sesService): JsonResponse
    {
        $user = $request->user();

        if (! $user->domain) {
            return response()->json(['verified' => false, 'status' => 'NoDomain'], 422);
        }

        try {
            $status = $sesService->checkDomainVerification($user->domain);
            $verified = $status === 'Success';

            $user->forceFill(['domain_verified' => $verified])->save();

            return response()->json(['verified' => $verified, 'status' => $status]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['verified' => false, 'status' => 'Error', 'message' => $e->getMessage()], 500);
        }
    }
}
