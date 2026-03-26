<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MessageController extends Controller
{
    public function index()
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

    public function create()
    {
        return view('messages.create');
    }
}
