<x-layouts.frasear :title="config('app.name', 'Fraseario') . ' — a collection of phrases worth keeping'">
    <div
            x-data="phraseSlider({{ $phrases->map(fn ($phrase) => [
            'body' => $phrase->body,
            'author' => $phrase->author?->name,
            'saved_by' => $phrase->savedBy->username,
            'saved_by_url' => route('frasear.show', $phrase->savedBy->username),
        ])->toJson() }})"
            x-on:keydown.window.left="prev()"
            x-on:keydown.window.right="next()"
            class="h-dvh w-dvw flex flex-col touch-pan-y"
    >
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

        {{-- Slider frame: supports pointer drag for mouse & touch, no vertical scroll --}}
        <main
                class="relative flex-1 select-none overflow-hidden"
                x-on:pointerdown="onPointerDown($event)"
                x-on:pointermove="onPointerMove($event)"
                x-on:pointerup="onPointerUp($event)"
                x-on:pointercancel="onPointerCancel($event)"
        >
            @if ($phrases->isEmpty())
                <div class="flex h-full items-center justify-center px-8 text-center">
                    <p class="font-serif text-xl italic text-muted">
                        No phrases saved yet. Be the first to add one.
                    </p>
                </div>
            @else
                <template x-for="(phrase, i) in phrases" :key="i">
                    <div
                            x-show="index === i"
                            x-transition:enter="transition ease-out duration-300 motion-reduce:duration-0"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            class="absolute inset-0 flex flex-col items-center justify-center px-6 sm:px-16"
                    >
                        <blockquote class="max-w-[62ch] text-center">
                            <p class="font-serif text-[1.75rem] italic leading-snug text-ink sm:text-[2.5rem]"
                               x-text="'“' + phrase.body + '”'"></p>
                        </blockquote>

                        <div class="pointer-events-none absolute inset-x-6 bottom-6 flex items-end justify-between sm:inset-x-10 sm:bottom-10">
                            <p class="pointer-events-auto font-serif text-sm text-muted" x-show="phrase.author"
                               x-text="'— ' + phrase.author"></p>
                            <p class="pointer-events-none w-0"></p>
                            <a
                                    :href="phrase.saved_by_url"
                                    class="pointer-events-auto font-sans text-sm text-muted transition-colors hover:text-accent"
                                    x-text="'saved by ' + phrase.saved_by"
                            ></a>
                        </div>
                    </div>
                </template>

                {{-- Position dots --}}
                <div class="pointer-events-none absolute inset-x-0 bottom-2 flex justify-center gap-1.5 sm:bottom-4">
                    <template x-for="(phrase, i) in phrases" :key="'dot-' + i">
                        <span
                                class="h-1.5 w-1.5 rounded-full transition-colors"
                                :class="index === i ? 'bg-accent' : 'bg-line'"
                        ></span>
                    </template>
                </div>
            @endif
        </main>
    </div>

    <script>
        function phraseSlider(phrases) {
            return {
                phrases,
                index: 0,
                pointerStartX: null,
                isPointerDown: false,

                next() {
                    if (this.phrases.length === 0) return;
                    this.index = (this.index + 1) % this.phrases.length;
                },

                prev() {
                    if (this.phrases.length === 0) return;
                    this.index = (this.index - 1 + this.phrases.length) % this.phrases.length;
                },

                onPointerDown(e) {
                    // start drag (works for mouse & touch via Pointer Events)
                    this.isPointerDown = true;
                    this.pointerStartX = e.clientX ?? (e.touches && e.touches[0] && e.touches[0].clientX) ?? null;
                    if (e.target && e.pointerId && e.target.setPointerCapture) {
                        try { e.target.setPointerCapture(e.pointerId); } catch (err) { /* ignore */ }
                    }
                },

                onPointerMove(e) {
                    // optional: visual feedback could be added here
                    if (!this.isPointerDown) return;
                },

                onPointerUp(e) {
                    if (!this.isPointerDown || this.pointerStartX === null) {
                        this.isPointerDown = false;
                        this.pointerStartX = null;
                        return;
                    }

                    const clientX = e.clientX ?? (e.changedTouches && e.changedTouches[0] && e.changedTouches[0].clientX) ?? null;
                    if (clientX === null) {
                        this.isPointerDown = false;
                        this.pointerStartX = null;
                        return;
                    }

                    const deltaX = clientX - this.pointerStartX;
                    const SWIPE_THRESHOLD = 40;

                    if (deltaX > SWIPE_THRESHOLD) this.prev();
                    if (deltaX < -SWIPE_THRESHOLD) this.next();

                    this.isPointerDown = false;
                    this.pointerStartX = null;
                    if (e.target && e.pointerId && e.target.releasePointerCapture) {
                        try { e.target.releasePointerCapture(e.pointerId); } catch (err) { /* ignore */ }
                    }
                },

                onPointerCancel() {
                    this.isPointerDown = false;
                    this.pointerStartX = null;
                },
            };
        }
    </script>
</x-layouts.frasear>