@extends('layouts.saas')
@section('title', 'Create message · InfiMal')
@section('content')
<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <h1 class="text-2xl font-bold">{{ isset($message) ? 'View message template' : 'Create message template' }}</h1>
    <form method="POST" action="{{ isset($message) ? route('messages.destroy', $message) : route('messages.store') }}" class="mt-6 space-y-4">@csrf
        @if(isset($message)) @method('DELETE') @endif
        <input class="w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="name" placeholder="Template name" value="{{ $message->name ?? '' }}" {{ isset($message) ? 'readonly' : 'required' }}>
        <input class="w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="subject" placeholder="Subject" value="{{ $message->subject ?? '' }}" {{ isset($message) ? 'readonly' : 'required' }}>
        <textarea class="min-h-56 w-full rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="content" placeholder="HTML content" {{ isset($message) ? 'readonly' : 'required' }}>{{ $message->content ?? '' }}</textarea>
        <button class="rounded-xl {{ isset($message) ? 'bg-rose-600' : 'bg-blue-600' }} px-5 py-3 font-semibold text-white">{{ isset($message) ? 'Delete message' : 'Save message' }}</button>
    </form>
</div>
@endsection
