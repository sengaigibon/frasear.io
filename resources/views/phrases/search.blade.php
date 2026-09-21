<x-layouts.frasear :title="$term ? __('Search: :term — frasear.io', ['term' => $term]) : __('Search — frasear.io')">
    <div class="flex min-h-dvh flex-col">
        <x-nav />

        <main class="mx-auto w-full max-w-2xl flex-1 px-6 py-12 sm:px-8">
            @if (is_null($phrases))
                <p class="font-serif text-xl italic text-muted">
                    {{ __('Type a word, author, or tag above to search saved phrases.') }}
                </p>
            @else
                <h1 class="mb-8 font-serif text-2xl italic text-ink">
                    {{ __('Results for ":term"', ['term' => $term]) }}
                </h1>

                @if ($phrases->isEmpty())
                    <p class="font-sans text-sm text-muted">
                        @if (($mode ?? 'word') === 'author')
                            {{ __('No phrases found for that author.') }}
                        @elseif (($mode ?? 'word') === 'tag')
                            {{ __('No phrases found for that tag.') }}
                        @else
                            {{ __('No phrases found for that word.') }}
                        @endif
                    </p>
                @else
                    <ul class="space-y-8">
                        @foreach ($phrases as $phrase)
                            <li class="border-b border-line pb-8">
                                <blockquote>
                                    <p class="font-serif text-lg italic leading-snug text-ink">
                                        &ldquo;{{ $phrase->body }}&rdquo;
                                    </p>
                                </blockquote>

                                <div class="mt-3 flex flex-wrap items-center justify-between gap-x-4 gap-y-1">
                                    <p class="font-serif text-sm text-muted">
                                        @if ($phrase->author)
                                            — {{ $phrase->author->name }}
                                        @endif
                                    </p>

                                    <a
                                        href="{{ route('frasear.show', $phrase->savedBy->username) }}"
                                        class="font-sans text-sm text-muted transition-colors hover:text-accent"
                                    >
                                        {{ __('saved by :username', ['username' => $phrase->savedBy->username]) }}
                                    </a>
                                </div>

                                @if ($phrase->tags->isNotEmpty())
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach ($phrase->tags as $tag)
                                            <a
                                                href="{{ route('phrases.search', ['username' => $username ?? null, 'mode' => 'tag', 'q' => $tag->name]) }}"
                                                class="font-sans text-xs text-muted transition-colors hover:text-accent"
                                            >#{{ $tag->name }}</a>
                                        @endforeach
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    {{-- Minimal prev/next pager — matches the site's quiet hairline
                         style rather than Laravel's default numbered Tailwind pager,
                         which would clash the same way Breeze's raw components did. --}}
                    @if ($phrases->hasPages())
                        <div class="mt-10 flex items-center justify-between font-sans text-sm">
                            @if ($phrases->onFirstPage())
                                <span class="text-line">{{ __('Previous') }}</span>
                            @else
                                <a href="{{ $phrases->previousPageUrl() }}" class="text-muted transition-colors hover:text-accent">{{ __('Previous') }}</a>
                            @endif

                            <span class="text-muted">
                                {{ __('Page :current of :last', ['current' => $phrases->currentPage(), 'last' => $phrases->lastPage()]) }}
                            </span>

                            @if ($phrases->hasMorePages())
                                <a href="{{ $phrases->nextPageUrl() }}" class="text-muted transition-colors hover:text-accent">{{ __('Next') }}</a>
                            @else
                                <span class="text-line">{{ __('Next') }}</span>
                            @endif
                        </div>
                    @endif
                @endif
            @endif
        </main>
    </div>
</x-layouts.frasear>
