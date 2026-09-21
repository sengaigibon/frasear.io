{{-- Shared top nav: wordmark + mode-aware search + hamburger menu. --}}
<header class="shrink-0 border-b border-line px-5 py-4 sm:px-8">
    <div class="mx-auto flex max-w-5xl items-center justify-between gap-6">
        <a href="{{ route('home') }}" class="font-sans text-[15px] font-medium tracking-tight text-ink">
            frasear.io
        </a>

        <div class="flex w-full max-w-md items-center justify-end gap-3">
            <div
                x-data="{
                    mode: '{{ request('mode', request('tag') !== null ? 'tag' : 'word') }}',
                    applyLimit() {
                        const input = this.$refs.searchInput;
                        if (!input) return;

                        const words = input.value.trim().split(/\s+/).filter(Boolean);
                        const maxWords = this.mode === 'word' ? 1 : 2;
                        input.value = words.slice(0, maxWords).join(' ');
                    }
                }"
                class="w-full"
            >
                <form action="{{ route('phrases.search') }}" method="GET" class="flex w-full items-end gap-2">
                    @php($currentUsername = request()->route()?->parameter('username'))
                    @if ($currentUsername)
                        <input type="hidden" name="username" value="{{ $currentUsername }}">
                    @endif

                    <label for="search-q" class="sr-only">{{ __('Search phrases') }}</label>
                    <input
                        id="search-q"
                        x-ref="searchInput"
                        x-on:input="applyLimit()"
                        type="search"
                        name="q"
                        value="{{ request('q', request('tag')) }}"
                        :placeholder="mode === 'word' ? '{{ __('Search by word') }}' : (mode === 'author' ? '{{ __('Search by author') }}' : '{{ __('Search by tag') }}')"
                        :pattern="mode === 'word' ? '[^\\s]+' : '[^\\n]+'"
                        class="w-full rounded-none border-0 border-b border-line bg-transparent px-0 py-0 font-sans text-sm text-ink placeholder:text-muted focus:border-accent focus:ring-0"
                    >

                    <label for="search-mode" class="sr-only">{{ __('Search mode') }}</label>
                    <select
                        id="search-mode"
                        name="mode"
                        x-model="mode"
                        class="min-w-[88px] appearance-none rounded-none border-0 border-b border-line bg-transparent px-1 py-0 font-sans text-[11px] uppercase tracking-[0.12em] text-muted focus:border-accent focus:ring-0"
                    >
                        <option value="tag">{{ __('tags') }}</option>
                        <option value="word">{{ __('word') }}</option>
                        <option value="author">{{ __('author') }}</option>
                    </select>
                </form>
            </div>

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
                        <a href="{{ route('phrases.manage') }}" class="block px-4 py-2 font-sans text-sm text-ink hover:bg-slate-50 hover:text-accent">{{ __('Manage phrases') }}</a>
                        <a href="{{ route('frasear.show', auth()->user()->username) }}" class="block px-4 py-2 font-sans text-sm text-ink hover:bg-slate-50 hover:text-accent">{{ __('My frasear.io') }}</a>
                        <form method="POST" action="{{ route('logout') }}" class="m-0 block w-full">
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
