<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Campaign - InfiMal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        'display': ['Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/dracula.min.css">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .rainbow-text {
            background: linear-gradient(90deg, #FF6B6B, #4ECDC4, #45B7D1, #96CEB4, #FFEAA7, #FF6B6B);
            background-size: 400% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: rainbow 8s ease infinite;
        }
        @keyframes rainbow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .dark .glass-card {
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .hover-glow:hover {
            box-shadow: 0 0 30px rgba(79, 70, 229, 0.2);
            transform: translateY(-2px);
        }
        .dark .hover-glow:hover {
            box-shadow: 0 0 30px rgba(165, 180, 252, 0.2);
        }
        .nav-link {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.15), rgba(147, 51, 234, 0.15));
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: -1;
        }
        .nav-link:hover::before {
            width: 100%;
        }
        .nav-link:hover {
            transform: translateX(4px);
        }
        .nav-link:hover .material-symbols-outlined {
            transform: scale(1.1) rotate(5deg);
        }
        .nav-link .material-symbols-outlined {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-link.active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.15), rgba(147, 51, 234, 0.15));
            border-left: 3px solid #3B82F6;
            transform: translateX(4px);
        }
        .dark .nav-link.active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.25), rgba(147, 51, 234, 0.25));
            border-left: 3px solid #60a5fa;
        }
        .theme-toggle-container {
            position: relative;
            width: 60px;
            height: 32px;
            border-radius: 16px;
            cursor: pointer;
            background: #e2e8f0;
        }
        .dark .theme-toggle-container {
            background: #475569;
        }
        .theme-toggle-handle {
            position: absolute;
            width: 24px;
            height: 24px;
            background: white;
            border-radius: 50%;
            top: 4px;
            left: 4px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .dark .theme-toggle-handle {
            transform: translateX(28px);
            background: #fbbf24;
        }
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }
        .modal-hidden {
            display: none;
        }
        .loading-spinner {
            border: 3px solid rgba(59, 130, 246, 0.1);
            border-radius: 50%;
            border-top: 3px solid #3b82f6;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .CodeMirror {
            height: 400px;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            font-family: 'Fira Code', monospace;
            font-size: 14px;
        }
        .dark .CodeMirror {
            border-color: #1e293b;
            background: #0f172a;
            color: #e2e8f0;
        }
        .editor-tabs {
            display: flex;
            gap: 4px;
            margin-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
        }
        .dark .editor-tabs {
            border-bottom-color: #334155;
        }
        .editor-tab {
            padding: 8px 16px;
            border-radius: 8px 8px 0 0;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            color: #6b7280;
            background: transparent;
            transition: all 0.2s;
        }
        .editor-tab.active {
            color: #3b82f6;
            background: rgba(59, 130, 246, 0.1);
            border-bottom: 2px solid #3b82f6;
        }
        .dark .editor-tab.active {
            color: #60a5fa;
            background: rgba(96, 165, 250, 0.1);
            border-bottom-color: #60a5fa;
        }
        .toast {
            animation: slideInRight 0.3s ease, fadeOut 0.3s ease 2.7s;
        }
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
    </style>
</head>
<body class="bg-white dark:bg-slate-900 text-gray-900 dark:text-slate-100 transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700 flex-shrink-0">
            <div class="flex flex-col h-full p-4">
                <!-- Logo -->
                <div class="sidebar-logo flex items-center gap-3 p-3 mb-8">
                    <div class="p-2 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 text-white">
                        <span class="material-symbols-outlined">all_inbox</span>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-xl font-bold rainbow-text">InfiMal</h1>
                        <p class="text-gray-500 dark:text-slate-400 text-xs font-medium">Email Management</p>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex flex-col gap-1 flex-1">
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/dashboard') }}">
                        <span class="material-symbols-outlined text-xl">dashboard</span> Dashboard
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/subscribers') }}">
                        <span class="material-symbols-outlined text-xl">group</span> Subscribers
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/lists') }}">
                        <span class="material-symbols-outlined text-xl">list_alt</span> Lists
                    </a>
                    <a class="nav-link active flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 dark:text-white font-medium text-sm" href="{{ url('/campaigns') }}">
                        <span class="material-symbols-outlined text-xl">campaign</span> Campaigns
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/messages') }}">
                        <span class="material-symbols-outlined text-xl">chat</span> Messages
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/smtp') }}">
                        <span class="material-symbols-outlined text-xl">dns</span> SMTP Settings
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/billing') }}">
                        <span class="material-symbols-outlined text-xl">receipt_long</span> Billing
                    </a>
                    <a class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium text-sm" href="{{ url('/profile') }}">
                        <span class="material-symbols-outlined text-xl">person</span> Profile
                    </a>
                </nav>
                
                <!-- Dark Mode Toggle -->
                <div class="pt-4 border-t border-gray-200 dark:border-slate-700 flex items-center justify-between">
                    <div class="flex items-center gap-3 px-3 py-2.5">
                        <span class="material-symbols-outlined text-xl text-gray-600 dark:text-slate-400">dark_mode</span>
                        <span class="text-gray-600 dark:text-slate-400 font-medium text-sm">Theme</span>
                    </div>
                    <div class="theme-toggle-container" id="themeToggle">
                        <div class="flex justify-center mb-6">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('logo.png') }}" alt="INFIMAL" class="h-12 w-auto">
                            </a>
                        </div>
                        <div class="theme-toggle-sun">☀️</div>
                        <div class="theme-toggle-moon">🌙</div>
                        <div class="theme-toggle-handle"></div>
                    </div>
                </div>
                
                <!-- Logout -->
                <div class="pt-4 border-t border-gray-200 dark:border-slate-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-500 font-medium text-sm">
                            <span class="material-symbols-outlined text-xl">logout</span> Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-slate-900">
            <!-- Top Bar -->
            <header class="bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700 sticky top-0 z-10">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Campaign</h2>
                            <p class="text-gray-600 dark:text-slate-400 text-sm mt-1">Create and send email campaigns to your subscribers</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="previewCampaign()" class="border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 px-6 py-2 rounded-lg font-semibold text-sm hover:bg-gray-50 dark:hover:bg-slate-800">
                                <span class="material-symbols-outlined align-middle mr-2 text-sm">visibility</span> Preview
                            </button>
                            <a href="{{ url('/campaigns') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg font-semibold text-sm hover-glow">
                                <span class="material-symbols-outlined align-middle mr-2 text-sm">arrow_back</span> Back to Campaigns
                            </a>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold cursor-pointer">
                                {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6">
                <!-- Campaign Banner -->
                <div class="glass-card rounded-2xl p-8 shadow-lg border-2 border-blue-100 dark:border-slate-700 hover-glow mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <span class="bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 px-4 py-2 rounded-lg text-sm font-semibold">
                                    <span class="material-symbols-outlined align-middle">campaign</span> New Campaign
                                </span>
                                <span class="bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400 px-4 py-2 rounded-lg text-sm font-semibold">
                                    <span class="material-symbols-outlined align-middle">rocket_launch</span> Ready to Send
                                </span>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Create Email Campaign 🚀</h1>
                            <p class="text-gray-600 dark:text-slate-300 mb-4">Fill in the details below to create and schedule your email campaign</p>
                        </div>
                    </div>
                </div>

                <!-- Campaign Form -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Form -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Information -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-6 flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-500">info</span> Basic Information
                            </h3>
                            
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Campaign Name *</label>
                                        <input type="text" id="campaignName" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-4 py-3 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-blue-500" placeholder="e.g., Weekly Newsletter" required onkeyup="updateProgress()">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Email Subject *</label>
                                        <input type="text" id="emailSubject" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-4 py-3 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-blue-500" placeholder="e.g., This Week's Updates" required onkeyup="updateProgress()">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">From Name *</label>
                                        <input type="text" id="fromName" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-4 py-3 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-blue-500" value="{{ $user->name ?? 'Your Name' }}" required onkeyup="updateProgress()">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">From Email *</label>
                                        <input type="email" id="fromEmail" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-4 py-3 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-blue-500" value="{{ $user->email ?? 'your@email.com' }}" required onkeyup="updateProgress()">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Reply To Email</label>
                                        <input type="email" id="replyTo" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-4 py-3 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-blue-500" value="{{ $user->email ?? 'support@yourdomain.com' }}">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Select List *</label>
                                        <select id="listSelect" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-4 py-3 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-blue-500" required onchange="updateProgress()">
                                            <option value="">Choose a subscriber list</option>
                                            @php
                                                // DIRECT DB QUERY WITHOUT USE STATEMENT
                                                try {
                                                    $mailingLists = \Illuminate\Support\Facades\DB::table('mailing_lists')
                                                        ->where('user_id', auth()->id())
                                                        ->orderBy('name')
                                                        ->get();
                                                } catch (\Exception $e) {
                                                    $mailingLists = [];
                                                }
                                            @endphp
                                            @forelse($mailingLists as $list)
                                                <option value="{{ $list->id }}">{{ $list->name }} ({{ number_format($list->subscriber_count ?? 0) }} contacts)</option>
                                            @empty
                                                <option value="" disabled>No lists found. Create a list first.</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Preview Text</label>
                                    <input type="text" id="previewText" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-4 py-3 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-blue-500" placeholder="Text shown in email preview...">
                                </div>
                            </div>
                        </div>

                        <!-- Email Content with Visual/HTML/CSS/JS Tabs -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-6 flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-500">mail</span> Email Content
                            </h3>
                            
                            <!-- Editor Tabs -->
                            <div class="editor-tabs">
                                <div class="editor-tab active" onclick="switchEditorTab('visual')">Visual Editor</div>
                                <div class="editor-tab" onclick="switchEditorTab('html')">HTML Editor</div>
                                <div class="editor-tab" onclick="switchEditorTab('css')">CSS Editor</div>
                                <div class="editor-tab" onclick="switchEditorTab('js')">JavaScript</div>
                            </div>
                            
                            <!-- Visual Editor Toolbar -->
                            <div id="visualToolbar" class="editor-toolbar mb-4">
                                <button type="button" onclick="formatText('bold')" class="editor-btn">
                                    <span class="material-symbols-outlined align-middle">format_bold</span> Bold
                                </button>
                                <button type="button" onclick="formatText('italic')" class="editor-btn">
                                    <span class="material-symbols-outlined align-middle">format_italic</span> Italic
                                </button>
                                <button type="button" onclick="insertVariable('[[first_name]]')" class="editor-btn">
                                    <span class="material-symbols-outlined align-middle">person</span> First Name
                                </button>
                                <button type="button" onclick="insertVariable('[[email]]')" class="editor-btn">
                                    <span class="material-symbols-outlined align-middle">mail</span> Email
                                </button>
                                <button type="button" onclick="insertVariable('[[unsubscribe_url]]')" class="editor-btn">
                                    <span class="material-symbols-outlined align-middle">link_off</span> Unsubscribe
                                </button>
                                <button type="button" onclick="openTemplateLibrary()" class="editor-btn">
                                    <span class="material-symbols-outlined align-middle">template</span> Templates
                                </button>
                            </div>
                            
                            <!-- Visual Editor -->
                            <div id="visualEditor">
                                <textarea id="emailContent" rows="12" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-4 py-3 bg-white dark:bg-slate-800 font-mono" onkeyup="updatePreview()" required>
Hello [[first_name]],

Welcome to our newsletter! We're excited to share the latest updates with you.

**This Week's Highlights:**
• New feature announcement
• Upcoming webinar schedule
• Latest blog posts

[Click here](https://yourwebsite.com) to learn more.

Best regards,  
{{ $user->name ?? 'Your Team' }}

---
[Unsubscribe]([[unsubscribe_url]])
                                </textarea>
                            </div>
                            
                            <!-- HTML Editor -->
                            <div id="htmlEditor" style="display: none;">
                                <textarea id="htmlCode">
&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;style&gt;
        body { font-family: Arial; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container"&gt;
        &lt;h1&gt;Hello [[first_name]]!&lt;/h1&gt;
        &lt;p&gt;Welcome to our newsletter!&lt;/p&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;
                                </textarea>
                            </div>
                            
                            <!-- CSS Editor -->
                            <div id="cssEditor" style="display: none;">
                                <textarea id="cssCode">
/* Email Styles */
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    color: #333;
}
.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
}
h1 {
    color: #2563eb;
}
                                </textarea>
                            </div>
                            
                            <!-- JS Editor -->
                            <div id="jsEditor" style="display: none;">
                                <textarea id="jsCode">
// Email JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Email loaded');
    // Personalization
    const firstName = '[[first_name]]';
    console.log('Hello ' + firstName);
});
                                </textarea>
                            </div>
                            
                            <p class="text-gray-500 dark:text-slate-500 text-xs mt-2">
                                Use [[variable_name]] for personalization. Available variables: [[first_name]], [[last_name]], [[email]], [[unsubscribe_url]]
                            </p>
                            
                            <!-- Template Library Modal -->
                            <div id="templateModal" class="modal-backdrop modal-hidden">
                                <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                                    <div class="flex justify-between items-center mb-6">
                                        <h3 class="text-gray-900 dark:text-white text-2xl font-bold">Email Templates</h3>
                                        <button onclick="closeTemplateModal()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
                                            <span class="material-symbols-outlined text-gray-600">close</span>
                                        </button>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="border rounded-lg p-4 cursor-pointer hover:shadow-lg" onclick="selectTemplate('newsletter')">
                                            <div class="h-32 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg mb-3 flex items-center justify-center text-white">
                                                <span class="material-symbols-outlined text-4xl">newspaper</span>
                                            </div>
                                            <h4 class="font-semibold">Newsletter Template</h4>
                                            <p class="text-sm text-gray-500">Weekly updates</p>
                                        </div>
                                        <div class="border rounded-lg p-4 cursor-pointer hover:shadow-lg" onclick="selectTemplate('welcome')">
                                            <div class="h-32 bg-gradient-to-br from-green-500 to-teal-600 rounded-lg mb-3 flex items-center justify-center text-white">
                                                <span class="material-symbols-outlined text-4xl">handshake</span>
                                            </div>
                                            <h4 class="font-semibold">Welcome Email</h4>
                                            <p class="text-sm text-gray-500">New subscribers</p>
                                        </div>
                                        <div class="border rounded-lg p-4 cursor-pointer hover:shadow-lg" onclick="selectTemplate('promo')">
                                            <div class="h-32 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg mb-3 flex items-center justify-center text-white">
                                                <span class="material-symbols-outlined text-4xl">local_offer</span>
                                            </div>
                                            <h4 class="font-semibold">Promotional</h4>
                                            <p class="text-sm text-gray-500">Offers & discounts</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Scheduling -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-6 flex items-center gap-2">
                                <span class="material-symbols-outlined text-purple-500">schedule</span> Scheduling & Settings
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Schedule Date & Time</label>
                                    <input type="datetime-local" id="scheduleDate" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-4 py-3 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-gray-700 dark:text-slate-300 text-sm font-medium mb-2">Campaign Status</label>
                                    <select id="campaignStatus" class="w-full border border-gray-300 dark:border-slate-700 rounded-lg px-4 py-3 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-blue-500">
                                        <option value="draft">Save as Draft</option>
                                        <option value="scheduled">Schedule for Later</option>
                                        <option value="immediate">Send Immediately</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">
                                <h4 class="text-gray-900 dark:text-white font-bold text-sm mb-3">Additional Options</h4>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" id="trackOpens" checked class="rounded border-gray-300">
                                        <span class="text-gray-700 dark:text-slate-300 text-sm">Track email opens</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" id="trackClicks" checked class="rounded border-gray-300">
                                        <span class="text-gray-700 dark:text-slate-300 text-sm">Track link clicks</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Sidebar -->
                    <div class="space-y-6">
                        <!-- Campaign Stats - FIXED: No USE statement -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">Campaign Stats</h3>
                            <div class="space-y-4">
                                @php
                                    // DIRECT DB QUERY WITHOUT USE STATEMENT
                                    try {
                                        $campaignsTotal = \Illuminate\Support\Facades\DB::table('campaigns')->where('user_id', auth()->id())->count();
                                        $campaignsActive = \Illuminate\Support\Facades\DB::table('campaigns')->where('user_id', auth()->id())->whereIn('status', ['active', 'scheduled'])->count();
                                        $campaignsSent = \Illuminate\Support\Facades\DB::table('campaigns')->where('user_id', auth()->id())->sum('sent_count');
                                        $campaignsOpens = \Illuminate\Support\Facades\DB::table('campaigns')->where('user_id', auth()->id())->sum('open_count');
                                        $campaignsOpenRate = $campaignsSent > 0 ? round(($campaignsOpens / $campaignsSent) * 100, 1) : 0;
                                    } catch (\Exception $e) {
                                        $campaignsTotal = 0;
                                        $campaignsActive = 0;
                                        $campaignsSent = 0;
                                        $campaignsOpenRate = 0;
                                    }
                                @endphp
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 dark:text-slate-400">Total Campaigns</span>
                                    <span class="text-gray-900 dark:text-white font-semibold">{{ number_format($campaignsTotal) }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 dark:text-slate-400">Active Campaigns</span>
                                    <span class="text-gray-900 dark:text-white font-semibold">{{ number_format($campaignsActive) }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 dark:text-slate-400">Total Sent</span>
                                    <span class="text-gray-900 dark:text-white font-semibold">{{ number_format($campaignsSent) }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 dark:text-slate-400">Avg Open Rate</span>
                                    <span class="text-green-600 dark:text-green-400 font-semibold">{{ $campaignsOpenRate }}%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">Actions</h3>
                            
                            <div class="space-y-3">
                                <button onclick="previewCampaign()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100">
                                    <span class="material-symbols-outlined text-blue-600">visibility</span>
                                    <span class="text-gray-900 dark:text-white text-sm">Preview Email</span>
                                </button>
                                <button onclick="saveDraft()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 hover:bg-yellow-100">
                                    <span class="material-symbols-outlined text-yellow-600">save</span>
                                    <span class="text-gray-900 dark:text-white text-sm">Save as Draft</span>
                                </button>
                                <button onclick="testEmail()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 hover:bg-green-100">
                                    <span class="material-symbols-outlined text-green-600">send</span>
                                    <span class="text-gray-900 dark:text-white text-sm">Send Test Email</span>
                                </button>
                                <button onclick="createCampaign()" class="w-full mt-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover-glow">
                                    <span class="material-symbols-outlined align-middle">rocket_launch</span> Launch Campaign
                                </button>
                            </div>
                            
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">
                                <h4 class="text-gray-900 dark:text-white font-bold text-sm mb-3">Progress</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-slate-400">Setup Complete</span>
                                        <span class="text-gray-900 dark:text-white font-semibold" id="progressPercent">0%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all" id="progressBar" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Campaigns - FIXED: No USE statement -->
                        <div class="glass-card rounded-2xl p-6 shadow-lg">
                            <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-4">Recent Campaigns</h3>
                            <div class="space-y-3">
                                @php
                                    try {
                                        $recentCampaignsList = \Illuminate\Support\Facades\DB::table('campaigns')
                                            ->where('user_id', auth()->id())
                                            ->orderBy('created_at', 'desc')
                                            ->limit(3)
                                            ->get();
                                    } catch (\Exception $e) {
                                        $recentCampaignsList = [];
                                    }
                                @endphp
                                
                                @forelse($recentCampaignsList as $campaign)
                                    @php
                                        $openRateCalc = ($campaign->sent_count ?? 0) > 0 ? round((($campaign->open_count ?? 0) / $campaign->sent_count) * 100) : 0;
                                        $statusColorClass = ($campaign->status ?? 'draft') == 'sent' ? 'green' : (($campaign->status ?? 'draft') == 'scheduled' ? 'blue' : 'yellow');
                                        $statusTextDisplay = ($campaign->status ?? 'draft') == 'sent' ? $openRateCalc . '% Open' : ucfirst($campaign->status ?? 'Draft');
                                    @endphp
                                    <div class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800/30">
                                        <div>
                                            <p class="text-gray-900 dark:text-white text-sm font-medium">{{ $campaign->name ?? 'Untitled' }}</p>
                                            <p class="text-gray-500 dark:text-slate-500 text-xs">{{ isset($campaign->updated_at) ? \Carbon\Carbon::parse($campaign->updated_at)->diffForHumans() : 'Recently' }}</p>
                                        </div>
                                        <span class="bg-{{ $statusColorClass }}-100 dark:bg-{{ $statusColorClass }}-900/50 text-{{ $statusColorClass }}-600 dark:text-{{ $statusColorClass }}-400 text-xs px-2 py-1 rounded">
                                            {{ $statusTextDisplay }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-gray-500 dark:text-slate-400 text-sm text-center py-4">No campaigns yet</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="modal-backdrop modal-hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-gray-900 dark:text-white text-2xl font-bold">Email Preview</h3>
                <button onclick="closePreviewModal()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
                    <span class="material-symbols-outlined text-gray-600">close</span>
                </button>
            </div>
            <div class="bg-white rounded-lg p-6 shadow-lg">
                <div id="previewContent" class="w-full min-h-[500px] overflow-y-auto border rounded-lg p-6 bg-white text-gray-900"></div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal-backdrop modal-hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 max-w-md text-center">
            <div class="w-20 h-20 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-green-600 text-4xl">check_circle</span>
            </div>
            <h3 class="text-gray-900 dark:text-white text-2xl font-bold mb-3">Success!</h3>
            <p class="text-gray-600 dark:text-slate-300 mb-6" id="successMessage">Campaign created successfully!</p>
            <button onclick="goToCampaigns()" class="w-full py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold">
                Go to Campaigns
            </button>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="loading-spinner"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js"></script>

    <script>
        // CodeMirror Editors
        let htmlEditor, cssEditor, jsEditor;
        
        function initEditors() {
            htmlEditor = CodeMirror.fromTextArea(document.getElementById('htmlCode'), {
                mode: 'htmlmixed',
                theme: document.documentElement.classList.contains('dark') ? 'dracula' : 'default',
                lineNumbers: true,
                lineWrapping: true
            });
            
            cssEditor = CodeMirror.fromTextArea(document.getElementById('cssCode'), {
                mode: 'css',
                theme: document.documentElement.classList.contains('dark') ? 'dracula' : 'default',
                lineNumbers: true,
                lineWrapping: true
            });
            
            jsEditor = CodeMirror.fromTextArea(document.getElementById('jsCode'), {
                mode: 'javascript',
                theme: document.documentElement.classList.contains('dark') ? 'dracula' : 'default',
                lineNumbers: true,
                lineWrapping: true
            });
        }

        // Switch Editor Tabs
        function switchEditorTab(tab) {
            document.querySelectorAll('.editor-tab').forEach(t => t.classList.remove('active'));
            event.target.classList.add('active');
            
            document.getElementById('visualEditor').style.display = 'none';
            document.getElementById('htmlEditor').style.display = 'none';
            document.getElementById('cssEditor').style.display = 'none';
            document.getElementById('jsEditor').style.display = 'none';
            document.getElementById('visualToolbar').style.display = 'none';
            
            if(tab === 'visual') {
                document.getElementById('visualEditor').style.display = 'block';
                document.getElementById('visualToolbar').style.display = 'block';
            } else if(tab === 'html') {
                document.getElementById('htmlEditor').style.display = 'block';
                if(htmlEditor) setTimeout(() => htmlEditor.refresh(), 100);
            } else if(tab === 'css') {
                document.getElementById('cssEditor').style.display = 'block';
                if(cssEditor) setTimeout(() => cssEditor.refresh(), 100);
            } else if(tab === 'js') {
                document.getElementById('jsEditor').style.display = 'block';
                if(jsEditor) setTimeout(() => jsEditor.refresh(), 100);
            }
        }

        // Template Library
        function openTemplateLibrary() {
            document.getElementById('templateModal').classList.remove('modal-hidden');
        }
        
        function closeTemplateModal() {
            document.getElementById('templateModal').classList.add('modal-hidden');
        }
        
        function selectTemplate(type) {
            const templates = {
                newsletter: `Hello [[first_name]],

Welcome to our weekly newsletter! Here's what's new this week:

## Latest Updates
- New features released
- Upcoming events
- Community highlights

Best regards,
The Team`,
                
                welcome: `Welcome to our community, [[first_name]]!

We're excited to have you on board. Here's what you can expect:

## Getting Started
1. Complete your profile
2. Explore our features
3. Join our community

Best regards,
The Team`,
                
                promo: `Hi [[first_name]],

Special offer just for you! 

## Limited Time Offer
Get 20% off on all plans
Use code: WELCOME20

[Claim Your Discount Now](https://yourwebsite.com/offer)

Best regards,
The Team`
            };
            
            document.getElementById('emailContent').value = templates[type] || templates.newsletter;
            closeTemplateModal();
            showToast('Template applied successfully', 'success');
            updatePreview();
            updateProgress();
        }

        // Update Progress
        function updateProgress() {
            const fields = [
                document.getElementById('campaignName').value,
                document.getElementById('emailSubject').value,
                document.getElementById('fromName').value,
                document.getElementById('fromEmail').value,
                document.getElementById('listSelect').value,
                document.getElementById('emailContent').value
            ];
            
            const filled = fields.filter(f => f && f.trim() !== '').length;
            const progress = Math.round((filled / 6) * 100);
            
            document.getElementById('progressPercent').textContent = progress + '%';
            document.getElementById('progressBar').style.width = progress + '%';
        }

        // Format Text
        function formatText(format) {
            const textarea = document.getElementById('emailContent');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value.substring(start, end);
            
            if(format === 'bold') {
                textarea.value = textarea.value.substring(0, start) + '**' + text + '**' + textarea.value.substring(end);
            } else if(format === 'italic') {
                textarea.value = textarea.value.substring(0, start) + '*' + text + '*' + textarea.value.substring(end);
            }
            updatePreview();
        }

        // Insert Variable
        function insertVariable(variable) {
            const textarea = document.getElementById('emailContent');
            const start = textarea.selectionStart;
            textarea.value = textarea.value.substring(0, start) + variable + textarea.value.substring(start);
            updatePreview();
        }

        // Update Preview
        function updatePreview() {
            const content = document.getElementById('emailContent').value;
            const subject = document.getElementById('emailSubject').value || 'Email Preview';
            const fromName = document.getElementById('fromName').value || 'Your Company';
            
            let html = content
                .replace(/\[\[first_name\]\]/g, '<strong>John</strong>')
                .replace(/\[\[email\]\]/g, 'john@example.com')
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" style="color:#2563eb;">$1</a>')
                .replace(/\n/g, '<br>');
            
            document.getElementById('previewContent').innerHTML = `
                <div style="font-family:Arial;max-width:600px;margin:0 auto;">
                    <div style="background:#f3f4f6;padding:15px;border-radius:8px;margin-bottom:20px;">
                        <strong>From:</strong> ${fromName}<br>
                        <strong>Subject:</strong> ${subject}
                    </div>
                    <div>${html}</div>
                </div>
            `;
        }

        // Preview Campaign
        function previewCampaign() {
            updatePreview();
            document.getElementById('previewModal').classList.remove('modal-hidden');
        }

        function closePreviewModal() {
            document.getElementById('previewModal').classList.add('modal-hidden');
        }

        // Save Draft
        function saveDraft() {
            const name = document.getElementById('campaignName').value;
            if(!name) {
                showToast('Please enter campaign name', 'error');
                return;
            }
            showLoading(true);
            setTimeout(() => {
                showLoading(false);
                showToast(`"${name}" saved as draft`, 'success');
            }, 1000);
        }

        // Test Email
        function testEmail() {
            const email = prompt('Enter test email:', '{{ $user->email ?? "test@example.com" }}');
            if(email) {
                showLoading(true);
                setTimeout(() => {
                    showLoading(false);
                    showToast(`Test email sent to ${email}`, 'success');
                }, 1500);
            }
        }

        // Create Campaign
        function createCampaign() {
            const name = document.getElementById('campaignName').value;
            const subject = document.getElementById('emailSubject').value;
            const listId = document.getElementById('listSelect').value;
            
            if(!name || !subject || !listId) {
                showToast('Please fill all required fields', 'error');
                return;
            }
            
            showLoading(true);
            setTimeout(() => {
                showLoading(false);
                document.getElementById('successMessage').textContent = `"${name}" created successfully!`;
                document.getElementById('successModal').classList.remove('modal-hidden');
            }, 2000);
        }

        function goToCampaigns() {
            window.location.href = '/campaigns';
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.add('modal-hidden');
        }

        function showLoading(show) {
            document.getElementById('loadingSpinner').classList.toggle('hidden', !show);
        }

        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = `toast fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
            toast.innerHTML = `<span class="material-symbols-outlined align-middle mr-2">${type === 'success' ? 'check_circle' : 'error'}</span>${message}`;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initEditors();
            updateProgress();
            updatePreview();
            
            // Set tomorrow's date
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('scheduleDate').value = tomorrow.toISOString().slice(0, 16);
            
            // Theme toggle
            const themeToggle = document.getElementById('themeToggle');
            themeToggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
                const isDark = document.documentElement.classList.contains('dark');
                localStorage.setItem('infimal_theme', isDark ? 'dark' : 'light');
                
                [htmlEditor, cssEditor, jsEditor].forEach(editor => {
                    if(editor) editor.setOption('theme', isDark ? 'dracula' : 'default');
                });
            });
            
            // Close modals on outside click
            document.addEventListener('click', function(e) {
                if(e.target === document.getElementById('previewModal')) closePreviewModal();
                if(e.target === document.getElementById('successModal')) closeSuccessModal();
                if(e.target === document.getElementById('templateModal')) closeTemplateModal();
            });
        });
    </script>
</body>
</html>