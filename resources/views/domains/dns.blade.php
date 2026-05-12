<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-900 dark:text-white">Domain DNS Records</h2></x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4">
        @if (! $user->domain)
            <div class="rounded bg-yellow-100 px-4 py-3 text-yellow-800">No domain has been submitted yet. <a class="underline" href="{{ route('domains.verify.form') }}">Submit a domain</a>.</div>
        @else
            <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold dark:text-white">{{ $user->domain }}</h3>
                        <p class="text-sm text-gray-500">Status: <span id="domain-status">{{ $user->domain_verified ? 'Verified' : 'Pending' }}</span></p>
                    </div>
                    <button id="check-status" class="rounded bg-blue-600 px-4 py-2 text-white">Check Status</button>
                </div>

                <p class="mb-3 text-gray-600 dark:text-gray-300">Create this TXT record at your DNS provider:</p>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead><tr class="border-b"><th class="py-2">Type</th><th>Name / Host</th><th>Value</th></tr></thead>
                        <tbody><tr class="border-b"><td class="py-2">TXT</td><td><code>{{ $txtName }}</code></td><td><code class="break-all">{{ $txtValue }}</code></td></tr></tbody>
                    </table>
                </div>
            </div>

            <script>
                document.getElementById('check-status').addEventListener('click', async () => {
                    const response = await fetch('{{ route('domains.verify.check') }}', {headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}});
                    const data = await response.json();
                    document.getElementById('domain-status').textContent = data.verified ? 'Verified' : data.status;
                });
            </script>
        @endif
    </div>
</x-app-layout>
