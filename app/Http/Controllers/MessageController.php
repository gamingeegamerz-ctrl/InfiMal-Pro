<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        try {
            // Check if messages table exists
            $tableExists = DB::select("SHOW TABLES LIKE 'messages'");
            
            if (empty($tableExists)) {
                // Create messages table if not exists
                $this->createMessagesTable();
            }
            
            // Get real messages
            $messages = DB::table('messages')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            
            // Get counts
            $totalMessages = DB::table('messages')
                ->where('user_id', $userId)
                ->count();
            
            $unreadMessages = DB::table('messages')
                ->where('user_id', $userId)
                ->where('is_read', false)
                ->count();
            
            return view('messages.index', [
                'messages' => $messages,
                'totalMessages' => $totalMessages,
                'unreadMessages' => $unreadMessages
            ]);
            
        } catch (\Exception $e) {
            return view('messages.index', [
                'messages' => collect([]),
                'totalMessages' => 0,
                'unreadMessages' => 0
            ]);
        }
    }
    
    private function createMessagesTable()
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS messages (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NOT NULL,
                subject VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                type ENUM('system', 'notification', 'alert') DEFAULT 'notification',
                is_read BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                INDEX messages_user_id_index (user_id),
                INDEX messages_is_read_index (is_read)
            )
        ");
    }
    
    public function create()
    {
        return view('messages.create');
    }
}
