<x-layouts.frasear :title="__('Log in') . ' — ' . config('app.name', 'Fraseario')">
    <div class="flex min-h-dvh flex-col items-center justify-center px-5 py-8 sm:px-8">
        
        <a href="{{ route('home') }}" class="mb-8 font-sans text-[15px] font-medium tracking-tight text-ink hover:text-accent transition-colors">
            frasear.io
        </a>

        <div class="w-full max-w-sm">
            <h1 class="mb-8 text-center font-serif text-2xl italic text-ink">{{ __('Log in') }}</h1>

            <x-auth-session-status class="mb-4 text-sm text-ink" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-6">
                @csrf

                <!-- Correo electrónico -->
                <div>
                    <label for="email" class="sr-only">{{ __('Email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="{{ __('Email') }}"
                           class="w-full rounded-none border-0 border-b border-line bg-transparent px-0 py-2 font-sans text-base text-ink placeholder:text-muted focus:border-accent focus:ring-0" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="sr-only">{{ __('Password') }}</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}"
                           class="w-full rounded-none border-0 border-b border-line bg-transparent px-0 py-2 font-sans text-base text-ink placeholder:text-muted focus:border-accent focus:ring-0" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Recordarme -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-line bg-transparent text-ink shadow-sm focus:ring-accent focus:ring-offset-paper" name="remember">
                        <span class="ms-2 font-sans text-sm text-ink">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="font-sans text-sm text-muted transition-colors hover:text-accent">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <div class="mt-4 flex flex-col items-center gap-4">
                    <button type="submit" class="w-full border border-ink bg-ink py-3 font-sans text-sm font-medium tracking-widest text-paper transition-colors hover:bg-transparent hover:text-ink focus:outline-none focus:ring-2 focus:ring-ink focus:ring-offset-2 focus:ring-offset-paper">
                        {{ __('Log in') }}
                    </button>

                    <a href="{{ route('home') }}" class="block w-full border border-ink bg-transparent py-3 text-center font-sans text-sm font-medium tracking-widest text-ink transition-colors hover:bg-ink hover:text-paper focus:outline-none focus:ring-2 focus:ring-ink focus:ring-offset-2 focus:ring-offset-paper">
                        {{ __('Back to home') }}
                    </a>

                    <a href="{{ route('register') }}" class="font-sans text-sm text-muted transition-colors hover:text-accent">
                        {{ __('Don\'t have an account? Register') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.frasear>
