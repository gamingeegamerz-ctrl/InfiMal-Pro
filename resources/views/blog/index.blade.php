@extends('marketing.layout')

@section('content')
<main class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold mb-4">INFIMAL Email Marketing Blog</h1>
    <p class="text-gray-700">Actionable SEO-friendly guides for email campaign platform teams.</p>

    <section class="mt-8 grid md:grid-cols-2 gap-6">
        @foreach($posts as $post)
            <article class="border rounded p-5">
                <h2 class="text-2xl font-semibold"><a class="text-blue-700" href="{{ route('blog.show', $post['slug']) }}">{{ $post['title'] }}</a></h2>
                <p class="mt-2 text-gray-600">{{ $post['excerpt'] }}</p>
                <a href="{{ route('blog.show', $post['slug']) }}" class="inline-block mt-3 text-sm text-blue-700 underline">Read article</a>
            </article>
        @endforeach
    </section>
</main>
@endsection
