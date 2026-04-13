@extends('marketing.layout')

@section('content')
<main class="max-w-3xl mx-auto px-4 py-12">
    <article>
        <h1 class="text-4xl font-bold mb-4">{{ $post['title'] }}</h1>
        <p class="text-sm text-gray-500 mb-8">Published {{ $post['date'] }}</p>

        @foreach($post['sections'] as $section)
            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-2">{{ $section['heading'] }}</h2>
                @foreach($section['paragraphs'] as $paragraph)
                    <p class="text-gray-700 mb-3">{{ $paragraph }}</p>
                @endforeach
            </section>
        @endforeach

        <p class="mt-8">Explore <a href="{{ route('features') }}" class="text-blue-700 underline">INFIMAL features</a> or compare <a href="{{ route('pricing') }}" class="text-blue-700 underline">pricing</a>.</p>
    </article>
</main>
@endsection
