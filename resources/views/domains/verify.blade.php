<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-900 dark:text-white">Verify Sending Domain</h2></x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4">
        @if (session('status'))
            <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-800">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 rounded bg-red-100 px-4 py-3 text-red-800">{{ $errors->first() }}</div>
        @endif

        <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
            <p class="mb-4 text-gray-600 dark:text-gray-300">Add a domain you own. Infimail will ask Amazon SES for a TXT verification token.</p>
            <form method="POST" action="{{ route('domains.verify.submit') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="domain" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Domain</label>
                    <input id="domain" name="domain" type="text" value="{{ old('domain', $user->domain) }}" placeholder="example.com" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-900 dark:text-white" required>
                </div>
                <button class="rounded bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">Start Verification</button>
            </form>
        </div>
    </div>
</x-app-layout>
