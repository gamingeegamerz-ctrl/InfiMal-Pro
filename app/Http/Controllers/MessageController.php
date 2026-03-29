<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $messages = Message::where('user_id', Auth::id())->latest()->paginate(15);

        return view('messages.index', [
            'messages' => $messages,
            'totalMessages' => Message::where('user_id', Auth::id())->count(),
            'unreadMessages' => Message::where('user_id', Auth::id())->where('is_read', false)->count(),
        ]);
    }

    public function create(): View
    {
        return view('messages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Message::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'type' => ['nullable', 'in:email,sms,notification'],
        ]) + [
            'user_id' => Auth::id(),
            'type' => $request->input('type', 'email'),
        ]);

        return redirect()->route('messages.index')->with('success', 'Message template saved.');
        $userId = Auth::id();

        // MAIN SAFETY CHECK (KEPT)
        if (!Schema::hasTable('messages')) {
            return view('messages.index', [
                'messages' => collect([]),
                'totalMessages' => 0,
                'unreadMessages' => 0,
                'emailsSent' => DB::table('email_logs')->where('user_id', $userId)->count(),
                'queueStatus' => 'Queue table not found',
            ]);
        }

        // USE ELOQUENT (CODEX) BUT KEEP MAIN DATA
        $messages = Message::where('user_id', $userId)->latest()->paginate(15);

        return view('messages.index', [
            'messages' => $messages,
            'totalMessages' => Message::where('user_id', $userId)->count(),
            'unreadMessages' => Message::where('user_id', $userId)
                ->where('is_read', false)
                ->count(),

            // MAIN EXTRA DATA (KEPT)
            'emailsSent' => DB::table('email_logs')->where('user_id', $userId)->count(),
            'queueStatus' => Schema::hasTable('jobs') ? 'Active' : 'Not configured',
        ]);
    }

    public function create(): View
    {
        return view('messages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Message::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'type' => ['nullable', 'in:email,sms,notification'],
        ]) + [
            'user_id' => Auth::id(),
            'type' => $request->input('type', 'email'),
        ]);

        return redirect()->route('messages.index')->with('success', 'Message template saved.');
            'is_template' => true, // MAIN FEATURE KEPT
        ]);

        return redirect()->route('messages.index')
            ->with('success', 'Message template saved.');
    }

    public function show(Message $message): View
    {
        $message = Message::where('user_id', Auth::id())->findOrFail($message->id);

        $message->update(['is_read' => true]);

        return view('messages.create', compact('message'));
    }

    public function destroy(Message $message): RedirectResponse
    {
        Message::where('user_id', Auth::id())->findOrFail($message->id)->delete();

        return redirect()->route('messages.index')
            ->with('success', 'Message deleted.');
    }
}