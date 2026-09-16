<x-layouts.frasear :title="'Registrarse — ' . config('app.name', 'Fraseario')">
    <div class="flex min-h-dvh flex-col items-center justify-center px-5 py-8 sm:px-8">
        
        <a href="{{ route('home') }}" class="mb-8 font-sans text-[15px] font-medium tracking-tight text-ink hover:text-accent transition-colors">
            frasear.io
        </a>

        <div class="w-full max-w-sm">
            <h1 class="mb-8 text-center font-serif text-2xl italic text-ink">Registrarse</h1>

            <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-6">
                @csrf

                <!-- Usuario -->
                <div>
                    <label for="username" class="sr-only">Usuario</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username" placeholder="usuario"
                           class="w-full rounded-none border-0 border-b border-line bg-transparent px-0 py-2 font-sans text-base text-ink placeholder:text-muted focus:border-accent focus:ring-0" />
                    <x-input-error :messages="$errors->get('username')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Nombre -->
                <div>
                    <label for="name" class="sr-only">Nombre</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="nombre completo"
                           class="w-full rounded-none border-0 border-b border-line bg-transparent px-0 py-2 font-sans text-base text-ink placeholder:text-muted focus:border-accent focus:ring-0" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Correo electrónico -->
                <div>
                    <label for="email" class="sr-only">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="correo electrónico"
                           class="w-full rounded-none border-0 border-b border-line bg-transparent px-0 py-2 font-sans text-base text-ink placeholder:text-muted focus:border-accent focus:ring-0" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="sr-only">Contraseña</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="contraseña"
                           class="w-full rounded-none border-0 border-b border-line bg-transparent px-0 py-2 font-sans text-base text-ink placeholder:text-muted focus:border-accent focus:ring-0" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label for="password_confirmation" class="sr-only">Confirmar contraseña</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="confirmar contraseña"
                           class="w-full rounded-none border-0 border-b border-line bg-transparent px-0 py-2 font-sans text-base text-ink placeholder:text-muted focus:border-accent focus:ring-0" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
                </div>

                <div class="mt-4 flex flex-col items-center gap-4">
                    <button type="submit" class="w-full border border-ink bg-ink py-3 font-sans text-sm font-medium tracking-widest text-paper transition-colors hover:bg-transparent hover:text-ink focus:outline-none focus:ring-2 focus:ring-ink focus:ring-offset-2">
                        REGISTRARSE
                    </button>

                    <a href="{{ route('login') }}" class="font-sans text-sm text-muted transition-colors hover:text-accent">
                        ¿Ya tienes cuenta?
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.frasear>
