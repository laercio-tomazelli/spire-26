<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <x-spire::alert type="success" class="mb-4">
            {{ session('status') }}
        </x-spire::alert>
    @endif

    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-white">{{ __('Bem-vindo de volta') }}</h2>
        <p class="mt-2 text-sm text-gray-400">{{ __('Entre com suas credenciais para acessar o sistema') }}</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <x-spire::input
            name="email"
            type="email"
            label="E-mail"
            :value="old('email')"
            placeholder="seu@email.com"
            required
            autofocus
            autocomplete="username"
            :error="$errors->first('email')"
        />

        <!-- Password -->
        <div class="mt-4">
            <x-spire::input
                name="password"
                type="password"
                label="Senha"
                placeholder="••••••••"
                required
                autocomplete="current-password"
                :error="$errors->first('password')"
                password
            />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded bg-gray-700 border-gray-600 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-400">{{ __('Lembrar de mim') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-400 hover:text-indigo-300 transition"
                    href="{{ route('password.request') }}">
                    {{ __('Esqueceu a senha?') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-spire::button type="submit" class="w-full justify-center">
                {{ __('Entrar') }}
            </x-spire::button>
        </div>

        @if (Route::has('register'))
            <div class="mt-4 text-center">
                <span class="text-sm text-gray-400">{{ __('Não tem uma conta?') }}</span>
                <a class="text-sm text-indigo-400 hover:text-indigo-300 transition ml-1"
                    href="{{ route('register') }}">
                    {{ __('Registre-se') }}
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>
