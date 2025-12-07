<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-white">{{ __('Redefinir senha') }}</h2>
        <p class="mt-2 text-sm text-gray-400">{{ __('Digite sua nova senha abaixo') }}</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <x-spire::input
            name="email"
            type="email"
            label="E-mail"
            :value="old('email', $request->email)"
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
                label="Nova Senha"
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
                label="Confirmar Nova Senha"
                placeholder="••••••••"
                required
                autocomplete="new-password"
                password
            />
        </div>

        <div class="mt-6">
            <x-spire::button type="submit" class="w-full justify-center">
                {{ __('Redefinir Senha') }}
            </x-spire::button>
        </div>
    </form>
</x-guest-layout>
