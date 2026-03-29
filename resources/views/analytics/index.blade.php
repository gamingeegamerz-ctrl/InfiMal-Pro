@extends('layouts.saas')
@section('title', 'Analytics · InfiMal')
@section('content')
<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
    @foreach (['Campaigns' => $overview['total_campaigns'], 'Subscribers' => $overview['total_subscribers'], 'Emails sent' => $overview['emails_sent'], 'Open rate' => $overview['open_rate'].'%', 'Click rate' => $overview['click_rate'].'%', 'Bounce rate' => $overview['bounce_rate'].'%'] as $label => $value)
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold">{{ $value }}</p>
        </div>
    @endforeach
</div>

<div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <h2 class="text-lg font-semibold">Rate comparison</h2>
        <div class="mt-5 space-y-4">
            @foreach (['Open rate' => $overview['open_rate'], 'Click rate' => $overview['click_rate'], 'Bounce rate' => $overview['bounce_rate']] as $label => $value)
                <div>
                    <div class="mb-2 flex items-center justify-between text-sm"><span>{{ $label }}</span><span>{{ $value }}%</span></div>
                    <div class="h-3 rounded-full bg-slate-100 dark:bg-slate-800">
                        <div class="h-3 rounded-full {{ $label === 'Bounce rate' ? 'bg-rose-500' : 'bg-blue-600' }}" style="width: {{ min(100, max(0, (float) $value)) }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <h2 class="text-lg font-semibold">Recent email activity</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500"><th class="py-2">Recipient</th><th>Status</th><th>Opened</th><th>Clicked</th></tr>
                </thead>
                <tbody>
                    @forelse($recent_activity as $item)
                        <tr class="border-t border-slate-200 dark:border-slate-800">
                            <td class="py-3">{{ $item->recipient_email ?? $item->to_email }}</td>
                            <td>{{ $item->status }}</td>
                            <td>{{ $item->opened ? 'Yes' : 'No' }}</td>
                            <td>{{ $item->clicked ? 'Yes' : 'No' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-4 text-slate-500">No analytics yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
    @foreach (['Campaigns' => $overview['total_campaigns'], 'Subscribers' => $overview['total_subscribers'], 'Emails sent' => $overview['emails_sent'], 'Open rate' => $overview['open_rate'].'%', 'Click rate' => $overview['click_rate'].'%', 'Bounce rate' => $overview['bounce_rate'].'%'] as $label => $value)
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900"><p class="text-sm text-slate-500">{{ $label }}</p><p class="mt-2 text-3xl font-bold">{{ $value }}</p></div>
    @endforeach
</div>
<div class="grid gap-6 xl:grid-cols-[1fr_1fr]">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900"><h2 class="text-lg font-semibold">Engagement rates</h2><div class="mt-4 h-80"><canvas id="analyticsRateChart"></canvas></div></div>
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900"><h2 class="text-lg font-semibold">Audience volume</h2><div class="mt-4 h-80"><canvas id="analyticsVolumeChart"></canvas></div></div>
<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <h2 class="text-lg font-semibold">Recent email activity</h2>
    <div class="mt-4 overflow-x-auto"><table class="min-w-full text-sm"><thead><tr class="text-left text-slate-500"><th class="py-2">Recipient</th><th>Status</th><th>Opened</th><th>Clicked</th></tr></thead><tbody>@forelse($recent_activity as $item)<tr class="border-t border-slate-200 dark:border-slate-800"><td class="py-3">{{ $item->recipient_email ?? $item->to_email }}</td><td>{{ $item->status }}</td><td>{{ $item->opened ? 'Yes' : 'No' }}</td><td>{{ $item->clicked ? 'Yes' : 'No' }}</td></tr>@empty<tr><td colspan="4" class="py-4 text-slate-500">No analytics yet.</td></tr>@endforelse</tbody></table></div>
</div>
    <div class="flex items-center justify-between"><h2 class="text-lg font-semibold">Engagement chart</h2><p class="text-sm text-slate-500">Last 7 days</p></div>
    <canvas id="analyticsChart" class="mt-4 h-32"></canvas>
</div>
<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <h2 class="text-lg font-semibold">Recent email activity</h2>
    <div class="mt-4 overflow-x-auto"><table class="min-w-full text-sm"><thead><tr class="text-left text-slate-500"><th class="py-2">Recipient</th><th>Status</th><th>Opened</th><th>Clicked</th></tr></thead><tbody>@forelse($recent_activity as $item)<tr class="border-t border-slate-200 dark:border-slate-800"><td class="py-3">{{ $item->recipient_email ?? $item->to_email }}</td><td>{{ $item->status }}</td><td>{{ $item->opened ? 'Yes' : 'No' }}</td><td>{{ $item->clicked ? 'Yes' : 'No' }}</td></tr>@empty<tr><td colspan="4" class="py-4 text-slate-500">No analytics yet.</td></tr>@endforelse</tbody></table></div>
</div>
<script>
new Chart(document.getElementById('analyticsRateChart'), {
    type: 'line',
    data: {
        labels: ['Open rate', 'Click rate', 'Bounce rate'],
        datasets: [{label: 'Percent', data: [{{ $overview['open_rate'] }}, {{ $overview['click_rate'] }}, {{ $overview['bounce_rate'] }}], borderColor: '#2563eb', backgroundColor: '#93c5fd', tension: 0.35, fill: true}]
    },
    options: {responsive: true, maintainAspectRatio: false}
});
new Chart(document.getElementById('analyticsVolumeChart'), {
    type: 'bar',
    data: {
        labels: ['Campaigns', 'Subscribers', 'Emails sent'],
        datasets: [{label: 'Count', data: [{{ $overview['total_campaigns'] }}, {{ $overview['total_subscribers'] }}, {{ $overview['emails_sent'] }}], backgroundColor: ['#2563eb', '#60a5fa', '#93c5fd'], borderRadius: 12}]
    },
    options: {responsive: true, maintainAspectRatio: false, plugins: {legend: {display: false}}}
const dailySeries = @json($dailySeries);
new Chart(document.getElementById('analyticsChart'), {
    type: 'bar',
    data: {
        labels: dailySeries.map(item => item.label),
        datasets: [
            { label: 'Sent', data: dailySeries.map(item => item.sent), backgroundColor: '#2563eb' },
            { label: 'Opens', data: dailySeries.map(item => item.opens), backgroundColor: '#059669' },
            { label: 'Clicks', data: dailySeries.map(item => item.clicks), backgroundColor: '#7c3aed' },
            { label: 'Bounces', data: dailySeries.map(item => item.bounces), backgroundColor: '#dc2626' },
        ]
    },
    options: { responsive: true, maintainAspectRatio: false }
});
</script>
@endsection
