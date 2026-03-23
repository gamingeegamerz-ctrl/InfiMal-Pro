@extends('layouts.saas')
@section('title', 'Campaign builder · InfiMal')
@section('content')
<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <h1 class="text-2xl font-bold">{{ isset($campaign) ? 'Edit campaign' : 'Create campaign' }}</h1>
    <form method="POST" action="{{ isset($campaign) ? route('campaigns.update', $campaign) : route('campaigns.store') }}" class="mt-6 space-y-4">
        @csrf
        @if(isset($campaign)) @method('PUT') @endif
        <div class="grid gap-4 md:grid-cols-2">
            <input class="rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="name" placeholder="Campaign name" value="{{ old('name', $campaign->name ?? '') }}" required>
            <input class="rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="subject" placeholder="Subject" value="{{ old('subject', $campaign->subject ?? '') }}" required>
            <input class="rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="from_name" placeholder="From name" value="{{ old('from_name', $campaign->from_name ?? auth()->user()->name) }}" required>
            <input class="rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="from_email" placeholder="From email" value="{{ old('from_email', $campaign->from_email ?? auth()->user()->email) }}" required>
            <input class="rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="reply_to" placeholder="Reply-to" value="{{ old('reply_to', $campaign->reply_to ?? '') }}">
            <select class="rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="list_id" required>@foreach($lists as $list)<option value="{{ $list->id }}" @selected(old('list_id', $campaign->list_id ?? '')==$list->id)>{{ $list->name }} ({{ $list->subscribers_count ?? $list->subscribers()->count() }})</option>@endforeach</select>
        </div>
        <input class="w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="preview_text" placeholder="Preview text" value="{{ old('preview_text', $campaign->preview_text ?? '') }}">
        <textarea class="min-h-40 w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="content" placeholder="HTML email body" required>{{ old('content', $campaign->content ?? '') }}</textarea>
        <textarea class="min-h-40 w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="html_content" placeholder="Optional raw HTML / JS-supported markup">{{ old('html_content', $campaign->html_content ?? '') }}</textarea>
        <div class="flex items-center justify-between"><select class="rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="status"><option value="draft">Draft</option><option value="scheduled">Scheduled</option></select><button class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white">Save campaign</button></div>
    </form>
</div>
@endsection
