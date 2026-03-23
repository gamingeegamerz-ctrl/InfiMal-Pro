@extends('layouts.saas')
@section('title', 'Campaign details · InfiMal')
@section('content')
<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <div class="flex items-start justify-between gap-4">
        <div><h1 class="text-2xl font-bold">{{ $campaign->name }}</h1><p class="text-slate-500">{{ $campaign->subject }}</p></div>
        <div class="flex gap-3"><a href="{{ route('campaigns.edit', $campaign) }}" class="rounded-xl border px-4 py-2">Edit</a><form method="POST" action="{{ route('campaigns.send', $campaign) }}">@csrf<button class="rounded-xl bg-blue-600 px-4 py-2 font-semibold text-white">Queue send</button></form></div>
    </div>
    <div class="mt-6 grid gap-4 md:grid-cols-4">@foreach(['Subscribers'=>$subscriberCount,'Queued/Sent'=>$campaign->total_sent,'Open rate'=>$campaign->open_rate.'%','Click rate'=>$campaign->click_rate.'%'] as $label=>$value)<div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800"><p class="text-xs uppercase text-slate-500">{{ $label }}</p><p class="mt-2 text-2xl font-bold">{{ $value }}</p></div>@endforeach</div>
    <div class="mt-6 rounded-xl border border-slate-200 p-4 dark:border-slate-800"><h2 class="font-semibold">Email preview</h2><div class="prose mt-4 max-w-none dark:prose-invert">{!! $campaign->html_content ?: $campaign->content !!}</div></div>
</div>
@endsection
