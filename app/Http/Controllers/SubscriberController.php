<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Models\MailingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get user's mailing lists
        $lists = MailingList::where('user_id', $user->id)
            ->orderBy('name', 'asc')
            ->get();
        
        // If no lists, create default ones
        if ($lists->count() === 0) {
            $this->createDefaultLists($user);
            $lists = MailingList::where('user_id', $user->id)
                ->orderBy('name', 'asc')
                ->get();
        }
        
        // Subscribers query with list relationship
        $query = Subscriber::where('user_id', $user->id)
            ->with('mailingList');
            
        // Apply filters
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('list_id') && $request->list_id != 'all') {
            $query->where('list_id', $request->list_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%$search%")
                  ->orWhere('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%");
            });
        }
        
        $subscribers = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Calculate statistics - handle zero case
        $totalSubscribers = Subscriber::where('user_id', $user->id)->count();
        $activeSubscribers = Subscriber::where('user_id', $user->id)
            ->where('status', 'active')->count();
        $unsubscribedSubscribers = Subscriber::where('user_id', $user->id)
            ->where('status', 'unsubscribed')->count();
        $bouncedSubscribers = Subscriber::where('user_id', $user->id)
            ->where('status', 'bounced')->count();
        $growth30Days = Subscriber::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
        
        // Recent activities
        $recentActivities = Subscriber::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($sub) {
                return [
                    'message' => "New subscriber: {$sub->email}",
                    'time' => $sub->created_at->diffForHumans()
                ];
            })
            ->toArray();
        
        // Default stats
        $avgOpenRate = 0;
        $avgClickRate = 0;
        
        return view('subscribers.index', compact(
            'subscribers',
            'lists',
            'totalSubscribers',
            'activeSubscribers',
            'unsubscribedSubscribers',
            'bouncedSubscribers',
            'growth30Days',
            'recentActivities',
            'avgOpenRate',
            'avgClickRate'
        ));
    }
    
    private function createDefaultLists($user)
    {
        $defaultLists = [
            ['name' => 'Default List', 'description' => 'Default mailing list'],
            ['name' => 'Newsletter', 'description' => 'Newsletter subscribers'],
            ['name' => 'Customers', 'description' => 'Customer list'],
        ];
        
        foreach($defaultLists as $listData) {
            MailingList::create([
                'user_id' => $user->id,
                'name' => $listData['name'],
                'slug' => strtolower(str_replace(' ', '-', $listData['name'])) . '-' . time(),
                'description' => $listData['description'],
                'is_public' => false,
                'is_default' => ($listData['name'] === 'Default List')
            ]);
        }
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email,NULL,id,user_id,' . Auth::id(),
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'status' => 'required|in:active,unsubscribed,bounced',
            'list_id' => 'required|exists:mailing_lists,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
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
    }
    
    public function destroy($id)
    {
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
    
    public function edit($id)
    {
        try {
            $subscriber = Subscriber::where('user_id', Auth::id())
                ->where('id', $id)
                ->with('mailingList')
                ->firstOrFail();
            
            return response()->json([
                'success' => true,
                'subscriber' => $subscriber
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Subscriber not found'
            ], 404);
        }
    }
    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email,' . $id . ',id,user_id,' . Auth::id(),
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'status' => 'required|in:active,unsubscribed,bounced',
            'list_id' => 'required|exists:mailing_lists,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
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
    }
    
    public function export(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Subscriber::where('user_id', $user->id)
                ->with('mailingList');
            
            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('list_id') && $request->list_id != 'all') {
                $query->where('list_id', $request->list_id);
            }
            
            $subscribers = $query->get();
            
            $filename = 'subscribers_' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($subscribers) {
                $file = fopen('php://output', 'w');
                
                // Header
                fputcsv($file, ['Email', 'First Name', 'Last Name', 'Status', 'List', 'Subscribed Date']);
                
                // Data
                foreach ($subscribers as $subscriber) {
                    fputcsv($file, [
                        $subscriber->email,
                        $subscriber->first_name ?? '',
                        $subscriber->last_name ?? '',
                        ucfirst($subscriber->status),
                        $subscriber->mailingList->name ?? 'No List',
                        $subscriber->created_at->format('Y-m-d H:i:s')
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }
    
    public function import(Request $request)
    {
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