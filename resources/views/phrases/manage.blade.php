<x-layouts.frasear :title="__('Manage phrases — frasear.io')">
    <div class="flex min-h-dvh flex-col">
        <x-nav />

        <main class="mx-auto w-full max-w-3xl flex-1 px-5 py-10 sm:px-8">
            <div class="mb-8 flex items-end justify-between gap-4 border-b border-line pb-4">
                <h1 class="font-serif text-2xl italic text-ink">{{ __('Manage phrases') }}</h1>
                <a href="{{ route('write') }}" class="font-sans text-sm text-muted transition-colors hover:text-accent">
                    {{ __('Write a phrase') }}
                </a>
            </div>

            @if (session('status'))
                <p class="mb-6 font-sans text-sm text-muted">{{ session('status') }}</p>
            @endif

            @if ($phrases->isEmpty())
                <p class="font-sans text-sm text-muted">{{ __('You have no saved phrases yet.') }}</p>
            @else
                <ul class="divide-y divide-line border-y border-line">
                    @foreach ($phrases as $phrase)
                        <li class="flex flex-col gap-4 py-5 sm:flex-row sm:items-start sm:justify-between sm:gap-6">
                            <div class="min-w-0 flex-1">
                                <p class="font-serif text-lg italic leading-snug text-ink">&ldquo;{{ $phrase->body }}&rdquo;</p>
                                @if ($phrase->author)
                                    <p class="mt-2 font-serif text-sm text-muted">— {{ $phrase->author->name }}</p>
                                @endif
                                @if ($phrase->tags->isNotEmpty())
                                    <p class="mt-2 font-sans text-xs text-muted">{{ $phrase->tags->map(fn ($tag) => '#'.$tag->name)->join(' ') }}</p>
                                @endif
                            </div>

                            <div class="flex shrink-0 items-center gap-3 font-sans text-sm">
                                <a href="{{ route('phrases.edit', $phrase) }}" class="text-muted transition-colors hover:text-accent">{{ __('Edit') }}</a>
                                <form method="POST" action="{{ route('phrases.destroy', $phrase) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('{{ __('Are you sure you want to delete this phrase?') }}')" class="text-muted transition-colors hover:text-red-700">{{ __('Remove') }}</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>

                @if ($phrases->hasPages())
                    <div class="mt-8 flex items-center justify-between font-sans text-sm">
                        @if ($phrases->onFirstPage())
                            <span class="text-line">{{ __('Previous') }}</span>
                        @else
                            <a href="{{ $phrases->previousPageUrl() }}" class="text-muted transition-colors hover:text-accent">{{ __('Previous') }}</a>
                        @endif
                        <span class="text-muted">{{ __('Page :current of :last', ['current' => $phrases->currentPage(), 'last' => $phrases->lastPage()]) }}</span>
                        @if ($phrases->hasMorePages())
                            <a href="{{ $phrases->nextPageUrl() }}" class="text-muted transition-colors hover:text-accent">{{ __('Next') }}</a>
                        @else
                            <span class="text-line">{{ __('Next') }}</span>
                        @endif
                    </div>
                @endif
            @endif
        </main>
    </div>
</x-layouts.frasear>