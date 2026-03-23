@extends('layouts.saas')
@section('title', 'SMTP Settings · InfiMal')
@section('content')
<div class="flex items-center justify-between"><div><h1 class="text-2xl font-bold">SMTP settings</h1><p class="text-sm text-slate-500">Credentials are encrypted and isolated per user.</p></div></div>
<div class="grid gap-4 md:grid-cols-4">@foreach(['Configured'=>$totalSmtp,'Active'=>$activeSmtp,'Sent today'=>$usageStats['sent_today'],'Success rate'=>$usageStats['success_rate'].'%'] as $label=>$value)<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900"><p class="text-sm text-slate-500">{{ $label }}</p><p class="mt-2 text-3xl font-bold">{{ $value }}</p></div>@endforeach</div>
<div class="grid gap-6 lg:grid-cols-[1fr_360px]">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900"><div class="space-y-3">@forelse($smtpSettings as $smtp)<div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800"><div class="flex items-center justify-between"><div><p class="font-semibold">{{ $smtp->name ?? $smtp->host }}</p><p class="text-sm text-slate-500">{{ $smtp->host }}:{{ $smtp->port }} · {{ $smtp->username }}</p></div><span class="text-xs {{ $smtp->is_default ? 'text-blue-600' : 'text-slate-500' }}">{{ $smtp->is_default ? 'Default' : 'Secondary' }}</span></div></div>@empty<p class="text-slate-500">No SMTP accounts configured.</p>@endforelse</div></div>
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <h2 class="font-semibold">Add SMTP account</h2>
        <form method="POST" action="{{ route('smtp.store') }}" class="mt-4 space-y-3">@csrf
            <input class="w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="name" placeholder="Name">
            <input class="w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="host" placeholder="Host" required>
            <input class="w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="port" placeholder="Port" value="587" required>
            <input class="w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="username" placeholder="Username" required>
            <input type="password" class="w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="password" placeholder="Password" required>
            <select class="w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="encryption"><option value="tls">TLS</option><option value="ssl">SSL</option><option value="none">None</option></select>
            <input class="w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="from_address" placeholder="From email">
            <input class="w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="from_name" placeholder="From name">
            <button class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white">Save SMTP</button>
        </form>
    </div>
</div>
@endsection
