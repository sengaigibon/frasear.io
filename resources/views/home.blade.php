<x-layouts.frasear :title="config('app.name', 'Frasario') . ' — a collection of phrases worth keeping'">
    <div class="flex h-dvh w-dvw flex-col">
        {{-- Top nav: wordmark + tag search, hairline border, no shadow --}}
        <header class="shrink-0 border-b border-line px-5 py-4 sm:px-8">
            <div class="mx-auto flex max-w-5xl items-center justify-between gap-6">
                <a href="{{ route('home') }}" class="font-sans text-[15px] font-medium tracking-tight text-ink">
                    frasear.io
                </a>

                <div class="flex items-center gap-4 w-full max-w-xs justify-end">
                    <form action="{{ route('phrases.search') }}" method="GET" class="w-full">
                        <label for="tag-search" class="sr-only">{{ __('Search by tag') }}</label>
                        <input
                                id="tag-search"
                                type="search"
                                name="tag"
                                placeholder="{{ __('Search by tag') }}"
                                class="w-full rounded-none border-0 border-b border-line bg-transparent px-0 py-1 font-sans text-sm text-ink placeholder:text-muted focus:border-accent focus:ring-0"
                        >
                    </form>
                    
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.outside="open = false" class="flex p-1 text-ink hover:text-accent focus:outline-none transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-transition.opacity style="display: none;" class="absolute right-0 top-full z-50 mt-2 w-48 rounded-md border border-line bg-white py-1 shadow-lg sm:w-56">
                            @guest
                                <a href="{{ route('home') }}" class="block px-4 py-2 font-sans text-sm text-ink hover:bg-slate-50 hover:text-accent">{{ __('Home') }}</a>
                                <a href="{{ route('register') }}" class="block px-4 py-2 font-sans text-sm text-ink hover:bg-slate-50 hover:text-accent">{{ __('Register') }}</a>
                                <a href="{{ route('login') }}" class="block px-4 py-2 font-sans text-sm text-ink hover:bg-slate-50 hover:text-accent">{{ __('Log in') }}</a>
                            @else
                                <a href="{{ route('home') }}" class="block px-4 py-2 font-sans text-sm text-ink hover:bg-slate-50 hover:text-accent">{{ __('Home') }}</a>
                                <a href="{{ route('write') }}" class="block px-4 py-2 font-sans text-sm text-ink hover:bg-slate-50 hover:text-accent">{{ __('Write a phrase') }}</a>
                                <a href="{{ route('frasear.show', auth()->user()->username) }}" class="block px-4 py-2 font-sans text-sm text-ink hover:bg-slate-50 hover:text-accent">{{ __('My frasear.io') }}</a>
                                <form method="POST" action="{{ route('logout') }}" class="block w-full m-0">
                                    @csrf
                                    <button type="submit" class="block w-full px-4 py-2 text-left font-sans text-sm text-ink hover:bg-slate-50 hover:text-accent">
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </header>

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