<?php

namespace App\Http\Controllers;

use App\Models\MailingList;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class SubscriberController extends Controller
{
    public function index(Request $request): View
    {
        abort_if(! auth()->user()->hasPaid() || ! auth()->user()->hasActiveLicense(), 403, 'Access denied. Payment required.');

        $user = Auth::user();
        $lists = MailingList::where('user_id', $user->id)->orderBy('name')->get();

        if ($lists->isEmpty()) {
            $this->createDefaultLists($user->id);
            $lists = MailingList::where('user_id', $user->id)->orderBy('name')->get();
        }

        $query = Subscriber::where('user_id', $user->id)->with('mailingList');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('list_id') && $request->list_id !== 'all') {
            $query->where('list_id', $request->integer('list_id'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($subQuery) use ($search): void {
                $subQuery->where('email', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $subscribers = $query->latest()->paginate(20)->withQueryString();

        return view('subscribers.index', [
            'subscribers' => $subscribers,
            'lists' => $lists,
            'totalSubscribers' => Subscriber::where('user_id', $user->id)->count(),
            'activeSubscribers' => Subscriber::where('user_id', $user->id)->where('status', 'active')->count(),
            'unsubscribedSubscribers' => Subscriber::where('user_id', $user->id)->where('status', 'unsubscribed')->count(),
            'bouncedSubscribers' => Subscriber::where('user_id', $user->id)->where('status', 'bounced')->count(),
            'growth30Days' => Subscriber::where('user_id', $user->id)->where('created_at', '>=', now()->subDays(30))->count(),
            'recentActivities' => [],
            'avgOpenRate' => 0,
            'avgClickRate' => 0,
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        abort_if(! auth()->user()->hasPaid() || ! auth()->user()->hasActiveLicense(), 403, 'Access denied. Payment required.');

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email,NULL,id,user_id,' . Auth::id(),
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'status' => 'required|in:active,unsubscribed,bounced',
            'list_id' => 'required|exists:mailing_lists,id',
        ]);

        if ($validator->fails()) {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'errors' => $validator->errors()], 422)
                : back()->withErrors($validator)->withInput();
        }

        $list = MailingList::where('id', $request->list_id)->where('user_id', Auth::id())->firstOrFail();

        Subscriber::create([
            'user_id' => Auth::id(),
            'list_id' => $list->id,
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'status' => $request->status,
            'source' => 'manual',
            'subscribed_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Subscriber added successfully.']);
        
        try {
            // Verify list belongs to user
            $list = MailingList::where('id', $request->list_id)
                ->where('user_id', Auth::id())
                ->first();
                
            if (!$list) {
                return response()->json([
                    'success' => false,
                    'message' => 'List not found or you don\'t have permission'
                ], 404);
            }
            
            $subscriber = Subscriber::create([
                'user_id' => Auth::id(),
                'list_id' => $request->list_id,
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'status' => $request->status,
                'source' => 'manual',
                'subscribed_at' => now()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subscriber added to ' . $list->name . ' successfully!'
                ]);
            }

            return redirect()->route('subscribers.index')->with('success', 'Subscriber added to ' . $list->name . ' successfully!');
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->route('subscribers.index')->with('success', 'Subscriber added successfully.');
    }

    public function destroy($id): RedirectResponse|JsonResponse
    {
        $subscriber = Subscriber::where('user_id', Auth::id())->findOrFail($id);
        $subscriber->delete();

        return request()->expectsJson()
            ? response()->json(['success' => true, 'message' => 'Subscriber deleted.'])
            : redirect()->route('subscribers.index')->with('success', 'Subscriber deleted.');
        try {
            $subscriber = Subscriber::where('user_id', Auth::id())
                ->where('id', $id)
                ->firstOrFail();
            
            $subscriber->delete();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subscriber deleted!'
                ]);
            }

            return redirect()->route('subscribers.index')->with('success', 'Subscriber deleted successfully.');
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subscriber'
            ], 500);
        }
    }

    public function edit($id): JsonResponse
    {
        $subscriber = Subscriber::where('user_id', Auth::id())->where('id', $id)->with('mailingList')->firstOrFail();
        return response()->json(['success' => true, 'subscriber' => $subscriber]);
    }

    public function update(Request $request, $id): RedirectResponse|JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email,' . $id . ',id,user_id,' . Auth::id(),
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'status' => 'required|in:active,unsubscribed,bounced',
            'list_id' => 'required|exists:mailing_lists,id',
        ]);

        if ($validator->fails()) {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'errors' => $validator->errors()], 422)
                : back()->withErrors($validator)->withInput();
        }

        $subscriber = Subscriber::where('user_id', Auth::id())->findOrFail($id);
        MailingList::where('user_id', Auth::id())->findOrFail($request->list_id);

        $subscriber->update($request->only('email', 'first_name', 'last_name', 'status', 'list_id'));

        return $request->expectsJson()
            ? response()->json(['success' => true, 'message' => 'Subscriber updated.'])
            : redirect()->route('subscribers.index')->with('success', 'Subscriber updated.');
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Subscriber::where('user_id', Auth::id())->with('mailingList');
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        
        try {
            $subscriber = Subscriber::where('user_id', Auth::id())
                ->where('id', $id)
                ->firstOrFail();
            
            // Verify list belongs to user
            $list = MailingList::where('id', $request->list_id)
                ->where('user_id', Auth::id())
                ->first();
                
            if (!$list) {
                return response()->json([
                    'success' => false,
                    'message' => 'List not found or you don\'t have permission'
                ], 404);
            }
            
            $subscriber->update([
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'status' => $request->status,
                'list_id' => $request->list_id
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subscriber updated!'
                ]);
            }

            return redirect()->route('subscribers.index')->with('success', 'Subscriber updated successfully.');
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
        if ($request->filled('list_id') && $request->list_id !== 'all') {
            $query->where('list_id', $request->integer('list_id'));
        }
        $subscribers = $query->get();

        return response()->stream(function () use ($subscribers): void {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Email', 'First Name', 'Last Name', 'Status', 'List', 'Subscribed Date']);
            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->email,
                    $subscriber->first_name,
                    $subscriber->last_name,
                    $subscriber->status,
                    $subscriber->mailingList?->name,
                    optional($subscriber->subscribed_at)->toDateTimeString(),
                ]);
            }
            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subscribers_'.now()->format('Y-m-d').'.csv"',
        ]);
    }

    public function import(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:csv,txt',
            'list_id' => 'required|exists:mailing_lists,id',
        ]);

        MailingList::where('id', $validated['list_id'])->where('user_id', Auth::id())->firstOrFail();

        $rows = array_map('str_getcsv', file($request->file('file')->getRealPath()));
        if (! empty($rows)) {
            array_shift($rows);
        }

        $imported = 0;
        $skipped = 0;
        foreach ($rows as $row) {
            $email = trim((string) ($row[0] ?? ''));
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped++;
                continue;
            }

            $exists = Subscriber::where('user_id', Auth::id())->where('email', $email)->exists();
            if ($exists) {
                $skipped++;
                continue;
            }

            Subscriber::create([
                'user_id' => Auth::id(),
                'list_id' => $validated['list_id'],
                'email' => $email,
                'first_name' => trim((string) ($row[1] ?? '')) ?: null,
                'last_name' => trim((string) ($row[2] ?? '')) ?: null,
                'status' => 'active',
                'source' => 'csv_import',
                'subscribed_at' => now(),
            ]);
            $imported++;
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'imported' => $imported, 'skipped' => $skipped, 'errors' => 0]);
        }

        return redirect()->route('subscribers.index')->with('success', "Imported {$imported} subscribers. {$skipped} skipped.");
    }

    private function createDefaultLists(int $userId): void
    {
        foreach ([
            ['name' => 'Default List', 'description' => 'Primary audience list', 'is_default' => true],
            ['name' => 'Newsletter', 'description' => 'Newsletter subscribers', 'is_default' => false],
            ['name' => 'Customers', 'description' => 'Customer audience', 'is_default' => false],
        ] as $listData) {
            MailingList::create([
                'user_id' => $userId,
                'name' => $listData['name'],
                'slug' => str($listData['name'])->slug() . '-' . now()->timestamp,
                'description' => $listData['description'],
                'is_public' => false,
                'is_default' => $listData['is_default'],
            ]);
        try {
            $request->validate([
                'file' => 'required|file|mimes:csv,txt',
                'list_id' => 'required|exists:mailing_lists,id'
            ]);
            
            $list = MailingList::where('id', $request->list_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            $file = $request->file('file');
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));
            
            // Remove header if exists
            array_shift($data);
            
            $imported = 0;
            $skipped = 0;
            
            foreach ($data as $row) {
                if (count($row) >= 1) {
                    $email = trim($row[0]);
                    $firstName = isset($row[1]) ? trim($row[1]) : null;
                    $lastName = isset($row[2]) ? trim($row[2]) : null;
                    
                    // Check if email already exists for this user
                    $exists = Subscriber::where('user_id', Auth::id())
                        ->where('email', $email)
                        ->exists();
                    
                    if (!$exists && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        Subscriber::create([
                            'user_id' => Auth::id(),
                            'list_id' => $request->list_id,
                            'email' => $email,
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'status' => 'active',
                            'source' => 'csv_import',
                            'subscribed_at' => now()
                        ]);
                        $imported++;
                    } else {
                        $skipped++;
                    }
                }
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Imported {$imported} subscribers successfully. {$skipped} skipped.",
                    'imported' => $imported,
                    'skipped' => $skipped,
                    'errors' => 0
                ]);
            }

            return redirect()->route('subscribers.index')->with('success', "Imported {$imported} subscribers successfully. {$skipped} skipped.");
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
