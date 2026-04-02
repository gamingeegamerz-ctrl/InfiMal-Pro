<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF TOKEN MUST BE HERE -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - InfiMal</title>
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Script for CSRF -->
    <script>
        // Set CSRF token for all AJAX requests
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Set default headers for all fetch requests
        window.defaultHeaders = {
            'X-CSRF-TOKEN': window.csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        };
    </script>
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <!-- Admin Navigation -->
    <nav class="bg-gray-900 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold">
                    <i class="fas fa-crown mr-2"></i> InfiMal Admin
                </a>
                <div class="space-x-2">
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded hover:bg-gray-800">
                        <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded hover:bg-gray-800">
                        <i class="fas fa-users mr-1"></i> Users
                    </a>
                    <a href="{{ route('admin.trust.index') }}" class="px-3 py-2 rounded hover:bg-gray-800">
                        <i class="fas fa-shield-alt mr-1"></i> Trust
                    </a>
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded bg-blue-600 hover:bg-blue-700 ml-4">
                        <i class="fas fa-exchange-alt mr-1"></i> User View
                    </a>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm">Logged in as: {{ Auth::user()->name }}</span>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-2 bg-red-600 rounded hover:bg-red-700">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto mt-6 p-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Handle all AJAX requests with CSRF
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': window.csrfToken
            }
        });

        // Handle logout form
        $('#logout-form').on('submit', function(e) {
            e.preventDefault();
            if(confirm('Are you sure you want to logout?')) {
                this.submit();
            }
        });

        // Global error handler
        $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
            if (jqxhr.status === 419) {
                alert('Session expired. Please refresh the page.');
                location.reload();
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
