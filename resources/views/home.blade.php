<x-layouts.frasear :title="config('app.name', 'Frasario') . ' — a collection of phrases worth keeping'">
    <div class="flex h-dvh w-dvw flex-col">
        {{-- Top nav: wordmark + tag search, hairline border, no shadow --}}
        <header class="shrink-0 border-b border-line px-5 py-4 sm:px-8">
            <div class="mx-auto flex max-w-5xl items-center justify-between gap-6">
                <a href="{{ route('home') }}" class="font-sans text-[15px] font-medium tracking-tight text-ink">
                    frasear.io
                </a>

                <form action="{{ route('phrases.search') }}" method="GET" class="w-full max-w-xs">
                    <label for="tag-search" class="sr-only">Buscar por etiqueta</label>
                    <input
                            id="tag-search"
                            type="search"
                            name="tag"
                            placeholder="buscar por etiqueta"
                            class="w-full rounded-none border-0 border-b border-line bg-transparent px-0 py-1 font-sans text-sm text-ink placeholder:text-muted focus:border-accent focus:ring-0"
                    >
                </form>
            </div>
        </header>

        <main class="relative flex-1 overflow-hidden">
            @if ($phrases->isEmpty())
                <div class="flex h-full items-center justify-center px-8 text-center">
                    <p class="font-serif text-xl italic text-muted">
                        No phrases saved yet. Be the first to add one.
                    </p>
                </div>
            @else
                <div
                        data-phrase-slider
                        class="swiper h-full"
                        style="
                            --swiper-pagination-color: #3B5249;
                            --swiper-pagination-bullet-inactive-color: #C9BFA9;
                            --swiper-pagination-bullet-inactive-opacity: 1;
                            --swiper-pagination-bullet-size: 6px;
                            --swiper-pagination-bullet-horizontal-gap: 5px;
                            --swiper-navigation-color: #8C8577;
                            --swiper-navigation-size: 22px;
                        "
                >
                    <div class="swiper-wrapper">
                        @foreach ($phrases as $phrase)
                            <div class="swiper-slide !flex !items-center !justify-center px-6 sm:px-16">
                                <blockquote class="max-w-[62ch] text-center">
                                    <p class="font-serif text-[1.75rem] italic leading-snug text-ink sm:text-[2.5rem]">
                                        &ldquo;{{ $phrase->body }}&rdquo;
                                    </p>
                                </blockquote>

                                <div class="pointer-events-none absolute inset-x-6 bottom-10 flex items-end justify-between sm:inset-x-10 sm:bottom-14">
                                    @if ($phrase->author)
                                        <p class="pointer-events-auto font-serif text-sm text-muted">— {{ $phrase->author->name }}</p>
                                    @else
                                        <span></span>
                                    @endif

                                    <a
                                            href="{{ route('frasear.show', $phrase->savedBy->username) }}"
                                            class="pointer-events-auto font-sans text-sm text-muted transition-colors hover:text-accent"
                                    >
                                        frasear.io de <span class="font-semibold">{{ $phrase->savedBy->username }}</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-prev !text-muted hover:!text-accent transition-colors"></div>
                    <div class="swiper-button-next !text-muted hover:!text-accent transition-colors"></div>
                    <div class="swiper-pagination !bottom-3 sm:!bottom-5"></div>
                </div>
            @endif
        </main>
    </div>
</x-layouts.frasear>