<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\MailingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalCampaigns = $user->campaigns()->count();

        $totalSubscribers = MailingList::where('user_id', $user->id)
            ->withCount('subscribers')
            ->get()
            ->sum('subscribers_count');

        $totalSent = Campaign::where('user_id', $user->id)
            ->selectRaw('COALESCE(SUM(sent_count), SUM(total_sent), 0) as sent_total')
            ->value('sent_total') ?? 0;

        $stats = [
            'total_campaigns' => $totalCampaigns,
            'total_subscribers' => $totalSubscribers,
            'total_sent' => (int) $totalSent,
            'account_age' => optional($user->created_at)->diffForHumans() ?? 'Just now',
        ];

        $paymentStatus = $user->hasPaid() ? 'paid' : 'free';

        return view('profile.index', [
            'user' => $user,
            'stats' => $stats,
            'paymentStatus' => $paymentStatus,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'timezone' => ['nullable', 'string', 'timezone'],
            'bio' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'timezone' => $request->timezone,
            'bio' => $request->bio,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }

    public function setPassword(Request $request)
    {
        $user = Auth::user();

        if ($user->password) {
            return response()->json([
                'success' => false,
                'message' => 'Password already set'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password set successfully'
        ]);
    }

    public function updatePreferences(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'email_notifications' => ['nullable', 'boolean'],
            'campaign_notifications' => ['nullable', 'boolean'],
            'weekly_reports' => ['nullable', 'boolean'],
            'theme' => ['nullable', 'in:system,light,dark'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $preferences = $user->preferences ?? [];

        $preferences['email_notifications'] = $request->boolean('email_notifications', true);
        $preferences['campaign_notifications'] = $request->boolean('campaign_notifications', true);
        $preferences['weekly_reports'] = $request->boolean('weekly_reports', true);
        $preferences['theme'] = $request->get('theme', 'system');

        $user->update([
            'preferences' => $preferences,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Preferences updated successfully',
            'preferences' => $preferences
        ]);
    }

    public function getStats()
    {
        $user = Auth::user();

        $stats = [
            'total_campaigns' => $user->campaigns()->count(),
            'total_subscribers' => MailingList::where('user_id', $user->id)->withCount('subscribers')->get()->sum('subscribers_count'),
            'total_sent' => (int) (Campaign::where('user_id', $user->id)->selectRaw('COALESCE(SUM(sent_count), SUM(total_sent), 0) as sent_total')->value('sent_total') ?? 0),
            'account_age' => optional($user->created_at)->diffForHumans() ?? 'Just now',
        ];

        return response()->json($stats);
    }

    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
