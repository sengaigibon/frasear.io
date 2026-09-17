{{-- Shared top nav: wordmark + tag search + hamburger menu. --}}
<header class="shrink-0 border-b border-line px-5 py-4 sm:px-8">
    <div class="mx-auto flex max-w-5xl items-center justify-between gap-6">
        <a href="{{ route('home') }}" class="font-sans text-[15px] font-medium tracking-tight text-ink">
            frasear.io
        </a>

        <div class="flex w-full max-w-xs items-center justify-end gap-4">
            <form action="{{ route('phrases.search') }}" method="GET" class="w-full">
                <label for="tag-search" class="sr-only">{{ __('Search by tag') }}</label>
                <input
                        id="tag-search"
                        type="search"
                        name="tag"
                        value="{{ request('tag') }}"
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
