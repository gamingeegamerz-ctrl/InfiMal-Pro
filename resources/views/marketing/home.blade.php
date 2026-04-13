@extends('marketing.layout')

@section('content')
<main class="max-w-6xl mx-auto px-4 py-12">
    <section>
        <h1 class="text-4xl font-bold mb-4">INFIMAL Email Marketing Tool for Bulk Email Sender Workflows</h1>
        <p class="text-lg text-gray-700">INFIMAL is an email campaign platform built for SMTP email sending, email automation software workflows, and teams that want to send unlimited emails with strong deliverability controls.</p>
        <div class="mt-6 space-x-3">
            <a class="px-4 py-2 bg-blue-600 text-white rounded" href="{{ route('pricing') }}">View Pricing</a>
            <a class="px-4 py-2 border rounded" href="{{ route('features') }}">Explore Features</a>
        </div>
    </section>

    <section class="mt-12">
        <h2 class="text-2xl font-semibold mb-3">What is INFIMAL?</h2>
        <p>INFIMAL is an email marketing SaaS that helps businesses run newsletters, lifecycle campaigns, and transactional campaigns from one dashboard.</p>
    </section>

    <section class="mt-12 grid md:grid-cols-2 gap-6">
        <article>
            <h2 class="text-2xl font-semibold mb-2">Core Features</h2>
            <h3 class="font-semibold">SMTP support</h3>
            <p>Bring your own SMTP provider and manage domain setup with authentication best practices.</p>
            <h3 class="font-semibold mt-3">Smart scheduling</h3>
            <p>Queue campaigns to send at the right time and avoid deliverability spikes.</p>
            <h3 class="font-semibold mt-3">Email automation</h3>
            <p>Build automated campaign sequences for onboarding, re-engagement, and nurturing.</p>
            <h3 class="font-semibold mt-3">High deliverability</h3>
            <p>Use suppression logic, bounce handling, and reputation controls.</p>
        </article>
        <article>
            <h2 class="text-2xl font-semibold mb-2">Benefits & Use Cases</h2>
            <ul class="list-disc pl-5 space-y-1">
                <li>Agencies managing multiple client campaigns.</li>
                <li>SaaS teams sending product updates and onboarding drips.</li>
                <li>Ecommerce brands running promotional and retention campaigns.</li>
            </ul>
            <p class="mt-4">Read practical guides in our <a href="{{ route('blog.index') }}" class="text-blue-600 underline">email marketing blog</a>.</p>
        </article>
    </section>
</main>
@endsection
