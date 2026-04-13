@extends('marketing.layout')

@section('content')
<main class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold mb-4">INFIMAL Pricing for Email Campaign Platform Growth</h1>
    <p class="text-gray-700">Simple pricing for teams that need an email marketing tool with bulk email sender and SMTP email sending support.</p>

    <section class="mt-10 border rounded p-6">
        <h2 class="text-2xl font-semibold">Pro Plan</h2>
        <p class="text-3xl font-bold mt-2">$79 <span class="text-base font-normal">/ month</span></p>
        <ul class="list-disc pl-5 mt-4 space-y-1">
            <li>Email automation software workflows</li>
            <li>Unlimited campaigns from your SMTP setup</li>
            <li>Deliverability monitoring and analytics</li>
        </ul>
        <a href="{{ route('register') }}" class="inline-block mt-5 px-4 py-2 bg-blue-600 text-white rounded">Start Free Trial</a>
    </section>

    <p class="mt-8 text-sm">Need feature details? Visit the <a class="text-blue-600 underline" href="{{ route('features') }}">features page</a>.</p>
</main>
@endsection
