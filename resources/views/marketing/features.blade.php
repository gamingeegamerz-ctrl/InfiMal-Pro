@extends('marketing.layout')

@section('content')
<main class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold mb-4">Email Automation Software and SMTP Email Platform Features</h1>
    <p class="text-gray-700">INFIMAL combines bulk email sender controls with automation and campaign analytics.</p>

    <section class="mt-10 grid md:grid-cols-2 gap-6">
        <article><h2 class="text-xl font-semibold">Bulk Email Sender Tools</h2><p>Create, segment, and send campaigns to large lists safely.</p></article>
        <article><h2 class="text-xl font-semibold">SMTP Email Sending Controls</h2><p>Configure SMTP credentials, throttling rules, and sender reputation settings.</p></article>
        <article><h2 class="text-xl font-semibold">Campaign Automation</h2><p>Set sequences, triggers, and scheduling windows.</p></article>
        <article><h2 class="text-xl font-semibold">Analytics and Deliverability</h2><p>Track opens, clicks, bounces, and complaints to keep performance healthy.</p></article>
    </section>

    <p class="mt-10">Start now from the <a href="{{ route('register') }}" class="text-blue-600 underline">INFIMAL signup page</a>, then compare plans on <a href="{{ route('pricing') }}" class="text-blue-600 underline">pricing</a>.</p>
</main>
@endsection
