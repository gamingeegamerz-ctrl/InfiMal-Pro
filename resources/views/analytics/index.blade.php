@extends('layouts.saas')
@section('title', 'Analytics · InfiMal')
@section('content')
<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
    @foreach (['Campaigns' => $overview['total_campaigns'], 'Subscribers' => $overview['total_subscribers'], 'Emails sent' => $overview['emails_sent'], 'Open rate' => $overview['open_rate'].'%', 'Click rate' => $overview['click_rate'].'%', 'Bounce rate' => $overview['bounce_rate'].'%'] as $label => $value)
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900"><p class="text-sm text-slate-500">{{ $label }}</p><p class="mt-2 text-3xl font-bold">{{ $value }}</p></div>
    @endforeach
</div>
<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <div class="flex items-center justify-between"><h2 class="text-lg font-semibold">Engagement chart</h2><p class="text-sm text-slate-500">Last 7 days</p></div>
    <canvas id="analyticsChart" class="mt-4 h-32"></canvas>
</div>
<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <h2 class="text-lg font-semibold">Recent email activity</h2>
    <div class="mt-4 overflow-x-auto"><table class="min-w-full text-sm"><thead><tr class="text-left text-slate-500"><th class="py-2">Recipient</th><th>Status</th><th>Opened</th><th>Clicked</th></tr></thead><tbody>@forelse($recent_activity as $item)<tr class="border-t border-slate-200 dark:border-slate-800"><td class="py-3">{{ $item->recipient_email ?? $item->to_email }}</td><td>{{ $item->status }}</td><td>{{ $item->opened ? 'Yes' : 'No' }}</td><td>{{ $item->clicked ? 'Yes' : 'No' }}</td></tr>@empty<tr><td colspan="4" class="py-4 text-slate-500">No analytics yet.</td></tr>@endforelse</tbody></table></div>
</div>
<script>
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
