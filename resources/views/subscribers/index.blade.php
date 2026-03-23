@extends('layouts.saas')
@section('title', 'Subscribers · InfiMal')
@section('content')
<div class="flex items-center justify-between"><div><h1 class="text-2xl font-bold">Subscribers</h1><p class="text-sm text-slate-500">Private subscribers isolated per user account.</p></div></div>
<div class="grid gap-4 md:grid-cols-4">@foreach(['Total'=>$totalSubscribers,'Active'=>$activeSubscribers,'Unsubscribed'=>$unsubscribedSubscribers,'Bounced'=>$bouncedSubscribers] as $label=>$value)<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900"><p class="text-sm text-slate-500">{{ $label }}</p><p class="mt-2 text-3xl font-bold">{{ $value }}</p></div>@endforeach</div>
<div class="grid gap-6 lg:grid-cols-[1fr_320px]">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900"><div class="overflow-x-auto"><table class="min-w-full text-sm"><thead><tr class="text-left text-slate-500"><th class="py-2">Email</th><th>Name</th><th>List</th><th>Status</th></tr></thead><tbody>@forelse($subscribers as $subscriber)<tr class="border-t border-slate-200 dark:border-slate-800"><td class="py-3">{{ $subscriber->email }}</td><td>{{ trim(($subscriber->first_name ?? '').' '.($subscriber->last_name ?? '')) ?: '—' }}</td><td>{{ $subscriber->mailingList?->name ?? '—' }}</td><td>{{ $subscriber->status }}</td></tr>@empty<tr><td colspan="4" class="py-4 text-slate-500">No subscribers found.</td></tr>@endforelse</tbody></table></div></div>
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <h2 class="font-semibold">Add subscriber</h2>
        <form method="POST" action="{{ route('subscribers.store') }}" class="mt-4 space-y-3">@csrf
            <input class="w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="email" placeholder="Email" required>
            <input class="w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="first_name" placeholder="First name">
            <input class="w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="last_name" placeholder="Last name">
            <select class="w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="list_id" required>@foreach($lists as $list)<option value="{{ $list->id }}">{{ $list->name }}</option>@endforeach</select>
            <input type="hidden" name="status" value="active">
            <button class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white">Save subscriber</button>
        </form>
    </div>
</div>
@endsection
