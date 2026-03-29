@extends('layouts.saas')
@section('title', 'Billing · InfiMal')
@section('content')
<div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">InfiMal Pro</p>
            <h1 class="mt-2 text-4xl font-bold">$299 one-time payment</h1>
            <p class="mt-3 max-w-2xl text-slate-600 dark:text-slate-300">No subscription, no trial, lifetime access after verified payment and license issuance.</p>
            <ul class="mt-6 space-y-2 text-sm text-slate-600 dark:text-slate-300">@foreach($features as $feature)<li>• {{ $feature }}</li>@endforeach</ul>
        </div>
        <div class="w-full max-w-md rounded-2xl bg-slate-50 p-6 dark:bg-slate-800">
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span>Status</span><strong>{{ $user->hasPaid() ? 'Paid' : 'Unpaid' }}</strong></div>
                <div class="flex justify-between"><span>Plan</span><strong>{{ $planName }}</strong></div>
                <div class="flex justify-between"><span>License</span><strong>{{ $license?->license_key ?? 'Pending payment' }}</strong></div>
            </div>
            @if(!$user->hasPaid())
                <form method="POST" action="{{ route('billing.checkout') }}" class="mt-6">@csrf<button class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white">Pay $299</button></form>
                <p class="mt-3 text-xs text-slate-500">You will be redirected to PayPal for approval, then InfiMal verifies the approved order server-side before activating your account.</p>
                <button id="pay-btn" class="mt-6 w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white">Pay $299</button>
                <p class="mt-3 text-xs text-slate-500">Payment is verified on the backend with PayPal before any access is granted.</p>
            @else
                <a href="{{ route('dashboard') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-4 py-3 font-semibold text-white">Open dashboard</a>
            @endif
        </div>
    </div>
</div>
<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <h2 class="text-lg font-semibold">Payment history</h2>
    <div class="mt-4 overflow-x-auto"><table class="min-w-full text-sm"><thead><tr class="text-left text-slate-500"><th class="py-2">Payment ID</th><th>Status</th><th>Amount</th><th>Date</th></tr></thead><tbody>@forelse($payments as $payment)<tr class="border-t border-slate-200 dark:border-slate-800"><td class="py-3">{{ $payment->payment_id }}</td><td>{{ $payment->status }}</td><td>${{ number_format($payment->amount, 2) }}</td><td>{{ $payment->created_at }}</td></tr>@empty<tr><td colspan="4" class="py-4 text-slate-500">No payments yet.</td></tr>@endforelse</tbody></table></div>
</div>
@if(!$user->hasPaid())
<script>
document.getElementById('pay-btn')?.addEventListener('click', async () => {
    const create = await fetch('{{ route('billing.checkout') }}', {method:'POST', headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Accept':'application/json'}});
    const order = await create.json();
    if (order.approval_url) window.location.href = order.approval_url;
});
</script>
@endif
@endsection
