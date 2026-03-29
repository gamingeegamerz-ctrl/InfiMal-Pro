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
        $userId = Auth::id();

        if (!Schema::hasTable('messages')) {
            return view('messages.index', [
                'messages' => collect([]),
                'totalMessages' => 0,
                'unreadMessages' => 0,
                'emailsSent' => DB::table('email_logs')->where('user_id', $userId)->count(),
                'queueStatus' => 'Queue table not found',
            ]);
        }

        $messages = DB::table('messages')->where('user_id', $userId)->latest()->paginate(15);

        return view('messages.index', [
            'messages' => $messages,
            'totalMessages' => DB::table('messages')->where('user_id', $userId)->count(),
            'unreadMessages' => DB::table('messages')->where('user_id', $userId)->where('is_read', false)->count(),
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
            'is_template' => true,
        ]);

        return redirect()->route('messages.index')->with('success', 'Message template saved.');
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

        return redirect()->route('messages.index')->with('success', 'Message deleted.');
    }
}