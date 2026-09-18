<x-layouts.frasear :title="__('Edit phrase — frasear.io')">
    <div class="flex min-h-dvh flex-col items-center justify-center px-5 py-8 sm:px-8">
        <a href="{{ route('phrases.manage') }}" class="mb-8 font-sans text-[15px] font-medium tracking-tight text-ink transition-colors hover:text-accent">
            {{ __('Manage phrases') }}
        </a>

        <div class="w-full max-w-lg">
            <h1 class="mb-8 text-center font-serif text-2xl italic text-ink">{{ __('Edit phrase') }}</h1>

            <form action="{{ route('phrases.update', $phrase) }}" method="POST" class="flex flex-col gap-6">
                @csrf
                @method('PATCH')

                <div>
                    <label for="body" class="sr-only">{{ __('Phrase') }}</label>
                    <textarea id="body" name="body" rows="4" required class="w-full resize-none rounded-none border-0 border-b border-line bg-transparent px-0 py-2 font-serif text-xl italic text-ink placeholder:text-muted focus:border-accent focus:ring-0">{{ old('body', $phrase->body) }}</textarea>
                    <x-input-error :messages="$errors->get('body')" class="mt-2 text-sm text-red-600" />
                </div>

                <div>
                    <label for="author" class="sr-only">{{ __('Author (optional)') }}</label>
                    <input id="author" type="text" name="author" value="{{ old('author', $phrase->author?->name) }}" placeholder="{{ __('Author (optional)') }}" class="w-full rounded-none border-0 border-b border-line bg-transparent px-0 py-2 font-sans text-base text-ink placeholder:text-muted focus:border-accent focus:ring-0" />
                    <x-input-error :messages="$errors->get('author')" class="mt-2 text-sm text-red-600" />
                </div>

                <div>
                    <label for="tags" class="sr-only">{{ __('Tags (optional)') }}</label>
                    <input id="tags" type="text" name="tags" value="{{ old('tags', $phrase->tags->map(fn ($tag) => $tag->name)->join(', ')) }}" placeholder="{{ __('Comma-separated tags') }}" class="w-full rounded-none border-0 border-b border-line bg-transparent px-0 py-2 font-sans text-base text-ink placeholder:text-muted focus:border-accent focus:ring-0" />
                    <x-input-error :messages="$errors->get('tags')" class="mt-2 text-sm text-red-600" />
                    <x-input-error :messages="$errors->get('tags.*')" class="mt-2 text-sm text-red-600" />
                </div>

                <div class="flex flex-col gap-3 sm:flex-row-reverse">
                    <button type="submit" class="w-full border border-ink bg-ink py-3 font-sans text-sm font-medium tracking-widest text-paper transition-colors hover:bg-transparent hover:text-ink focus:outline-none focus:ring-2 focus:ring-ink focus:ring-offset-2">
                        {{ __('Save changes') }}
                    </button>
                    <a href="{{ route('phrases.manage') }}" class="w-full border border-line py-3 text-center font-sans text-sm font-medium tracking-widest text-muted transition-colors hover:border-ink hover:text-ink">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.frasear>