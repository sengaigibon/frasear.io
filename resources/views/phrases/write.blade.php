<x-layouts.frasear :title="__('Write a phrase — frasear.io')">
    <div class="flex min-h-dvh flex-col items-center justify-center px-5 py-8 sm:px-8">
        
        <a href="{{ route('home') }}" class="mb-8 font-sans text-[15px] font-medium tracking-tight text-ink hover:text-accent transition-colors">
            frasear.io
        </a>

        <div class="w-full max-w-lg">
            <h1 class="mb-8 text-center font-serif text-2xl italic text-ink">{{ __('Write a phrase') }}</h1>

            @if(session('status'))
                <div class="mb-6 rounded-none border border-ink bg-transparent px-4 py-3 font-sans text-sm text-ink">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('write.store') }}" method="POST" class="flex flex-col gap-6">
                @csrf

                <!-- Body -->
                <div>
                    <label for="body" class="sr-only">{{ __('Phrase') }}</label>
                    <textarea id="body" name="body" rows="4" required placeholder="{{ __('Write your phrase here') }}"
                              class="w-full resize-none rounded-none border-0 border-b border-line bg-transparent px-0 py-2 font-serif text-xl italic text-ink placeholder:text-muted focus:border-accent focus:ring-0">{{ old('body') }}</textarea>
                    <x-input-error :messages="$errors->get('body')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Author -->
                <div>
                    <label for="author" class="sr-only">{{ __('Author (optional)') }}</label>
                    <input id="author" type="text" name="author" value="{{ old('author') }}" placeholder="{{ __('Author (optional)') }}"
                           class="w-full rounded-none border-0 border-b border-line bg-transparent px-0 py-2 font-sans text-base text-ink placeholder:text-muted focus:border-accent focus:ring-0" />
                    <x-input-error :messages="$errors->get('author')" class="mt-2 text-sm text-red-600" />
                </div>

                <div>
                    <label for="tags" class="sr-only">{{ __('Tags (optional)') }}</label>
                    <input id="tags" type="text" name="tags" value="{{ old('tags') }}" placeholder="{{ __('Comma-separated tags') }}"
                           class="w-full rounded-none border-0 border-b border-line bg-transparent px-0 py-2 font-sans text-base text-ink placeholder:text-muted focus:border-accent focus:ring-0" />
                    <x-input-error :messages="$errors->get('tags')" class="mt-2 text-sm text-red-600" />
                </div>

                <div class="mt-4 flex flex-col items-center gap-4">
                    <button type="submit" class="w-full border border-ink bg-ink py-3 font-sans text-sm font-medium tracking-widest text-paper transition-colors hover:bg-transparent hover:text-ink focus:outline-none focus:ring-2 focus:ring-ink focus:ring-offset-2">
                        {{ __('Save phrase') }}
                    </button>
                </div>
            </form>
            <div class="mt-4 flex flex-col items-center gap-4">
                <button type="submit" class="w-full border border-ink bg-ink py-3 font-sans text-sm font-medium tracking-widest text-paper transition-colors hover:bg-transparent hover:text-ink focus:outline-none focus:ring-2 focus:ring-ink focus:ring-offset-2">
                    <a href="{{ route('frasear.show', auth()->user()->username) }}">{{ __('Back to my frasear.io') }}</a>
                </button>
            </div>
        </div>
    </div>
</x-layouts.frasear>