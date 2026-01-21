<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-white">{{ __('Criar conta') }}</h2>
        <p class="mt-2 text-sm text-gray-400">{{ __('Preencha os dados para se cadastrar') }}</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <x-spire::input
            name="name"
            type="text"
            label="Nome"
            :value="old('name')"
            placeholder="Seu nome completo"
            required
            autofocus
            autocomplete="name"
            :error="$errors->first('name')"
        />

        <!-- Email Address -->
        <div class="mt-4">
            <x-spire::input
                name="email"
                type="email"
                label="E-mail"
                :value="old('email')"
                placeholder="seu@email.com"
                required
                autocomplete="username"
                :error="$errors->first('email')"
            />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-spire::input
                name="password"
                type="password"
                label="Senha"
                placeholder="••••••••"
                required
                autocomplete="new-password"
                :error="$errors->first('password')"
                password
            />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-spire::input
                name="password_confirmation"
                type="password"
                label="Confirmar Senha"
                placeholder="••••••••"
                required
                autocomplete="new-password"
                password
            />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-indigo-400 hover:text-indigo-300 transition"
                href="{{ route('login') }}">
                {{ __('Já possui cadastro?') }}
            </a>

            <x-spire::button type="submit">
                {{ __('Cadastrar') }}
            </x-spire::button>
        </div>
    </form>
</x-guest-layout>
