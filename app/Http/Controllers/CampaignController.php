<?php

namespace App\Http\Controllers;

use App\Jobs\SendCampaignEmailJob;
use App\Models\Campaign;
use App\Models\EmailJob;
use App\Models\MailingList;
use App\Models\Subscriber;
use App\Models\SMTPAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(): View
    {
        $campaigns = Campaign::where('user_id', Auth::id())->with('mailingList')->latest()->paginate(12);

        return view('campaigns.index', [
            'campaigns' => $campaigns,
            'totalCampaigns' => Campaign::where('user_id', Auth::id())->count(),
            'sentCampaigns' => Campaign::where('user_id', Auth::id())->where('status', 'sent')->count(),
            'draftCampaigns' => Campaign::where('user_id', Auth::id())->where('status', 'draft')->count(),
            'scheduledCampaigns' => Campaign::where('user_id', Auth::id())->where('status', 'scheduled')->count(),
        ]);
    }

    public function create(): View
    {
        return view('campaigns.create', [
            'lists' => MailingList::where('user_id', Auth::id())->withCount('subscribers')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        if (! auth()->user()->is_paid) {
            abort(403, 'Access denied. Payment required.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'from_name' => ['required', 'string', 'max:255'],
            'from_email' => ['required', 'email', 'max:255'],
            'reply_to' => ['nullable', 'email', 'max:255'],
            'list_id' => ['required', 'integer'],
            'preview_text' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'html_content' => ['nullable', 'string'],
            'schedule_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:draft,scheduled'],
        ]);

        $list = MailingList::where('user_id', Auth::id())->findOrFail($validated['list_id']);

        $campaign = Campaign::create([
            ...$validated,
            'user_id' => Auth::id(),
            'list_id' => $list->id,
            'html_content' => $validated['html_content'] ?? $validated['content'],
            'plain_text' => strip_tags($validated['content']),
            'status' => $validated['status'] ?? 'draft',
            'total_recipients' => Subscriber::where('user_id', Auth::id())->where('list_id', $list->id)->active()->count(),
        ]);

        return redirect()->route('campaigns.show', $campaign)->with('success', 'Campaign created successfully.');
    }

    public function show(Campaign $campaign): View
    {
        $campaign = Campaign::where('user_id', Auth::id())->with('mailingList')->findOrFail($campaign->id);

        return view('campaigns.show', [
            'campaign' => $campaign,
            'subscriberCount' => Subscriber::where('user_id', Auth::id())->where('list_id', $campaign->list_id)->active()->count(),
        ]);
    }

    public function edit(Campaign $campaign): View
    {
        $campaign = Campaign::where('user_id', Auth::id())->findOrFail($campaign->id);

        return view('campaigns.create', [
            'campaign' => $campaign,
            'lists' => MailingList::where('user_id', Auth::id())->withCount('subscribers')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $campaign = Campaign::where('user_id', Auth::id())->findOrFail($campaign->id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'from_name' => ['required', 'string', 'max:255'],
            'from_email' => ['required', 'email', 'max:255'],
            'reply_to' => ['nullable', 'email', 'max:255'],
            'list_id' => ['required', 'integer'],
            'preview_text' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'html_content' => ['nullable', 'string'],
            'schedule_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:draft,scheduled'],
        ]);

        MailingList::where('user_id', Auth::id())->findOrFail($validated['list_id']);
        $campaign->update([
            ...$validated,
            'html_content' => $validated['html_content'] ?? $validated['content'],
            'plain_text' => strip_tags($validated['content']),
        ]);

        return redirect()->route('campaigns.show', $campaign)->with('success', 'Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        Campaign::where('user_id', Auth::id())->findOrFail($campaign->id)->delete();

        return redirect()->route('campaigns.index')->with('success', 'Campaign deleted.');
    }

    public function send(Campaign $campaign): RedirectResponse
    {
        $campaign = Campaign::where('user_id', Auth::id())->findOrFail($campaign->id);
        if (! auth()->user()->is_paid) {
            abort(403, 'Access denied. Payment required.');
        }

        if (! SMTPAccount::ownedBy(Auth::id())->where('is_active', true)->exists()) {
            return redirect()->route('smtp.index')->with('error', 'Configure an active SMTP account before sending a campaign.');
        }

        $subscribers = Subscriber::where('user_id', Auth::id())
            ->where('list_id', $campaign->list_id)
            ->active()
            ->get();

        foreach ($subscribers as $subscriber) {
            $job = EmailJob::create([
                'user_id' => Auth::id(),
                'campaign_id' => $campaign->id,
                'subscriber_id' => $subscriber->id,
                'to_email' => $subscriber->email,
                'to_name' => $subscriber->full_name ?: $subscriber->email,
                'subject' => $campaign->subject,
                'body' => $campaign->content,
                'html' => $campaign->html_content ?: $campaign->content,
                'from_email' => $campaign->from_email,
                'from_name' => $campaign->from_name,
                'reply_to' => $campaign->reply_to,
                'status' => 'queued',
            ]);

            SendCampaignEmailJob::dispatch($job->id)->onQueue('emails');
        }

        $campaign->update([
            'status' => 'sending',
            'started_at' => now(),
            'total_recipients' => $subscribers->count(),
        ]);

        return redirect()->route('campaigns.show', $campaign)->with('success', 'Campaign queued for delivery. Start a queue worker to process email jobs.');
    }

    public function preview(Campaign $campaign): View
    {
        $campaign = Campaign::where('user_id', Auth::id())->findOrFail($campaign->id);
        return view('campaigns.preview', compact('campaign'));
    }

    public function analytics(Campaign $campaign): JsonResponse
    {
        $campaign = Campaign::where('user_id', Auth::id())->findOrFail($campaign->id);

        return response()->json([
            'campaign' => $campaign,
            'open_rate' => $campaign->open_rate,
            'click_rate' => $campaign->click_rate,
            'bounce_rate' => $campaign->bounce_rate,
        ]);
    }
}
