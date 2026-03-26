<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Subscriber;
use App\Models\MailingList;
use App\Models\EmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendCampaignEmailJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CampaignController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $campaigns = Campaign::where('user_id', $userId)
            ->with('mailingList')
            ->orderByDesc('created_at')
            ->get();

        $totalCampaigns = $campaigns->count();
        $draftCount = $campaigns->where('status', 'draft')->count();
        $scheduledCount = $campaigns->where('status', 'scheduled')->count();
        $sendingCount = $campaigns->where('status', 'sending')->count();
        $sentCampaigns = $campaigns->where('status', 'sent')->count();
        $activeCount = $scheduledCount + $sendingCount;

        $emailBase = DB::table('email_logs')->where('user_id', $userId);
        $emailsSent = (clone $emailBase)->count();
        $opened = (clone $emailBase)->where('opened', true)->count();
        $clicked = (clone $emailBase)->where('clicked', true)->count();
        $bounced = (clone $emailBase)->whereNotNull('bounced_at')->count();

        $avgOpenRate = $emailsSent > 0 ? round(($opened / $emailsSent) * 100, 2) : 0;
        $avgClickRate = $emailsSent > 0 ? round(($clicked / $emailsSent) * 100, 2) : 0;
        $bounceRate = $emailsSent > 0 ? round(($bounced / $emailsSent) * 100, 2) : 0;

        return view('campaigns.index', compact(
            'campaigns',
            'totalCampaigns',
            'sentCampaigns',
            'draftCount',
            'scheduledCount',
            'sendingCount',
            'activeCount',
            'avgOpenRate',
            'avgClickRate',
            'bounceRate'
        ));
    }

    public function create()
    {
        // FIXED: Get ALL user's mailing lists for dropdown with proper subscriber count
        $lists = MailingList::where('user_id', Auth::id())
            ->withCount(['subscribers as active_count' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('name', 'asc')
            ->get();
        
        // Debug log to check if lists are being fetched
        Log::info('Campaign Create - Lists fetched', [
            'user_id' => Auth::id(),
            'lists_count' => $lists->count(),
            'lists' => $lists->pluck('name', 'id')->toArray()
        ]);
        
        // Ensure $lists is always a collection, even if empty
        if (!$lists) {
            $lists = collect([]);
        }
        
        return view('campaigns.create', compact('lists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'from_name' => 'required|string|max:255',
            'from_email' => 'required|email|max:255',
            'reply_to' => 'nullable|email|max:255',
            'list_id' => 'required|exists:mailing_lists,id',
            'preview_text' => 'nullable|string|max:500',
            'content' => 'required|string',
            'schedule_date' => 'nullable|date|after:now',
            'status' => 'string|in:draft,scheduled'
        ]);

        // Verify user owns this list
        $list = MailingList::where('id', $validated['list_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated['user_id'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'draft';

        $campaign = Campaign::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Campaign created successfully!',
            'campaign' => $campaign->load('mailingList'),
            'redirect' => route('campaigns.index')
        ]);
    }

    public function show($id)
    {
        $campaign = Campaign::where('user_id', Auth::id())
            ->where('id', $id)
            ->with('mailingList')
            ->firstOrFail();
        
        // Get subscriber count for this list
        $subscriberCount = Subscriber::where('user_id', Auth::id())
            ->where('list_id', $campaign->list_id)
            ->where('status', 'active')
            ->count();
        
        return view('campaigns.show', compact('campaign', 'subscriberCount'));
    }
    
    public function edit($id)
    {
        $campaign = Campaign::where('user_id', Auth::id())
            ->where('id', $id)
            ->with('mailingList')
            ->firstOrFail();
        
        // FIXED: Get ALL user's mailing lists for dropdown with proper subscriber count
        $lists = MailingList::where('user_id', Auth::id())
            ->withCount(['subscribers as active_count' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('name', 'asc')
            ->get();
        
        // Debug log
        Log::info('Campaign Edit - Lists fetched', [
            'user_id' => Auth::id(),
            'lists_count' => $lists->count()
        ]);
        
        // Ensure $lists is always a collection, even if empty
        if (!$lists) {
            $lists = collect([]);
        }
        
        return view('campaigns.edit', compact('campaign', 'lists'));
    }
    
    public function update(Request $request, $id)
    {
        $campaign = Campaign::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        // Can only edit draft campaigns
        if ($campaign->status === 'sent') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot edit sent campaigns'
            ], 400);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'from_name' => 'required|string|max:255',
            'from_email' => 'required|email|max:255',
            'reply_to' => 'nullable|email|max:255',
            'list_id' => 'required|exists:mailing_lists,id',
            'preview_text' => 'nullable|string|max:500',
            'content' => 'required|string',
            'schedule_date' => 'nullable|date|after:now'
        ]);
        
        // Verify user owns the new list
        MailingList::where('id', $validated['list_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $campaign->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Campaign updated successfully!',
            'campaign' => $campaign->load('mailingList'),
            'redirect' => route('campaigns.index')
        ]);
    }

    /**
     * SEND CAMPAIGN (QUEUE BASED - FULLY CONNECTED)
     * 
     * Flow: Campaign → Fetch Active Subscribers → Create EmailJob → Queue Jobs
     */
    public function send($id)
    {
        $campaign = Campaign::where('user_id', Auth::id())
            ->where('id', $id)
            ->with('mailingList')
            ->firstOrFail();

        // Validation
        if ($campaign->status === 'sent') {
            return back()->with('error', 'Campaign already sent.');
        }

        if ($campaign->status === 'sending') {
            return back()->with('error', 'Campaign is already being sent.');
        }

        // Check if list has active subscribers
        $activeSubscribers = Subscriber::where('list_id', $campaign->list_id)
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();
        
        $activeCount = $activeSubscribers->count();
        
        if ($activeCount === 0) {
            return back()->with('error', 'No active subscribers in the selected list.');
        }

        // Mark campaign as sending
        $campaign->update([
            'status' => 'sending',
            'started_at' => now(),
            'total_recipients' => $activeCount
        ]);

        Log::info("Campaign {$campaign->id} started sending to list {$campaign->list_id} with {$activeCount} subscribers");

        // Use html_content if available, fallback to content
        $htmlContent = $campaign->html_content ?? $campaign->content;
        $plainText = $campaign->plain_text ?? strip_tags($htmlContent);

        // Fetch active subscribers and create EmailJob records
        $jobsCreated = 0;
        $chunks = $activeSubscribers->chunk(500);

        foreach ($chunks as $subscribers) {
            foreach ($subscribers as $subscriber) {
                // Create EmailJob record
                $emailJob = EmailJob::create([
                    'user_id'      => $campaign->user_id,
                    'campaign_id'  => $campaign->id,
                    'subscriber_id'=> $subscriber->id,
                    'to_email'     => $subscriber->email,
                    'to_name'      => trim($subscriber->first_name . ' ' . $subscriber->last_name),
                    'subject'      => $campaign->subject,
                    'body'         => $plainText,
                    'html'         => $htmlContent,
                    'from_email'   => $campaign->from_email,
                    'from_name'    => $campaign->from_name,
                    'reply_to'     => $campaign->reply_to,
                    'status'       => 'queued'
                ]);

                // Dispatch job with EmailJob ID
                SendCampaignEmailJob::dispatch($emailJob->id)
                    ->onQueue('emails');

                $jobsCreated++;
            }
        }

        Log::info("Created {$jobsCreated} email jobs for campaign {$campaign->id}");

        // Don't mark as 'sent' yet - let it remain 'sending' until all jobs complete
        // Status will be updated by scheduled command or when all jobs finish

        return back()->with('success', "Campaign queued for sending to {$activeCount} subscribers! {$jobsCreated} jobs created.");
    }


    public function analytics($id)
    {
        $campaign = Campaign::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $base = DB::table('email_logs')
            ->where('user_id', Auth::id())
            ->where('campaign_id', $campaign->id);

        $stats = [
            'total_sent' => (clone $base)->count(),
            'opens' => (clone $base)->where('opened', 1)->count(),
            'clicks' => (clone $base)->where('clicked', 1)->count(),
            'bounces' => (clone $base)->where('status', 'bounced')->count(),
        ];

        return response()->json([
            'campaign' => $campaign,
            'stats' => $stats,
        ]);
    }

    public function destroy($id)
    {
        $campaign = Campaign::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        // Cannot delete sent campaigns
        if ($campaign->status === 'sent') {
            return back()->with('error', 'Cannot delete sent campaigns');
        }

        $campaign->delete();

        return back()->with('success', 'Campaign deleted successfully!');
    }
    
    // Preview campaign
    public function preview($id)
    {
        $campaign = Campaign::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        return view('campaigns.preview', compact('campaign'));
    }
    
    // Duplicate campaign
    public function duplicate($id)
    {
        $campaign = Campaign::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        $newCampaign = $campaign->replicate();
        $newCampaign->name = $campaign->name . ' (Copy)';
        $newCampaign->status = 'draft';
        $newCampaign->sent_at = null;
        $newCampaign->started_at = null;
        $newCampaign->save();
        
        return redirect()->route('campaigns.edit', $newCampaign->id)
            ->with('success', 'Campaign duplicated successfully!');
    }
}