@extends('layouts.saas')
@section('title', 'Profile · InfiMal')
@section('content')
<div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <h1 class="text-2xl font-bold">Profile</h1>
        <form method="POST" action="{{ route('profile.update') }}" class="mt-6 grid gap-4 md:grid-cols-2">@csrf
            <input class="rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="name" value="{{ $user->name }}" required>
            <input class="rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="email" value="{{ $user->email }}" required>
            <input class="rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="phone" value="{{ $user->phone }}" placeholder="Phone">
            <input class="rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="timezone" value="{{ $user->timezone }}" placeholder="Timezone">
            <textarea class="md:col-span-2 min-h-32 rounded-xl border border-slate-300 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" name="bio" placeholder="Bio">{{ $user->bio }}</textarea>
            <div class="md:col-span-2"><button class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white">Save profile</button></div>
        </form>
    </section>
    <section class="space-y-4">
        @foreach(['Campaigns'=>$stats['total_campaigns'],'Subscribers'=>$stats['total_subscribers'],'Emails sent'=>$stats['total_sent'],'Account age'=>$stats['account_age'],'Payment'=>$paymentStatus] as $label=>$value)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900"><p class="text-sm text-slate-500">{{ $label }}</p><p class="mt-2 text-2xl font-bold">{{ $value }}</p></div>
        @endforeach
    </section>
</div>
@endsection
