<x-layouts.frasear :title="config('app.name', 'Frasario') . ' — a collection of phrases worth keeping'">
    <div class="flex h-dvh w-dvw flex-col">
        <x-nav />

        <main class="relative flex-1 overflow-hidden">
            @if ($phrases->isEmpty())
                <div class="flex h-full items-center justify-center px-8 text-center">
                    <p class="font-serif text-xl italic text-muted">
                        {{ __('No phrases saved yet. Be the first to add one.') }}
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
                                        {{ __('frasear.io by :username', ['username' => $phrase->savedBy->username]) }}
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