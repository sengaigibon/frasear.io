<x-layouts.frasear title="Search — fraseario">
    <div class="mx-auto max-w-2xl px-6 py-16">
        <a href="{{ route('home') }}" class="font-sans text-sm text-muted hover:text-accent">&larr; back</a>

        <h1 class="mt-6 font-serif text-2xl italic text-ink">
            @if ($term)
                results for "{{ $term }}"
            @else
                search by tag
            @endif
        </h1>

        {{-- This page is intentionally minimal — the real search results
             design is still an open question (see requirements doc, Q1).
             Revisit once that's decided. --}}

        @if ($term && $phrases->isEmpty())
            <p class="mt-8 font-sans text-sm text-muted">No phrases found for that tag.</p>
        @endif

        <ul class="mt-8 space-y-6">
            @foreach ($phrases as $phrase)
                <li class="border-b border-line pb-6">
                    <p class="font-serif italic text-ink">&ldquo;{{ $phrase->body }}&rdquo;</p>
                    @if ($phrase->author)
                        <p class="mt-2 font-serif text-sm text-muted">— {{ $phrase->author->name }}</p>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</x-layouts.frasear>