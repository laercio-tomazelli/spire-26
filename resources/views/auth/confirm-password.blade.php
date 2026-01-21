<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-white">{{ __('Confirmar senha') }}</h2>
        <p class="mt-2 text-sm text-gray-400">
            {{ __('Esta é uma área segura. Por favor, confirme sua senha antes de continuar.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
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

        <div class="mt-6">
            <x-spire::button type="submit" class="w-full justify-center">
                {{ __('Confirmar') }}
            </x-spire::button>
        </div>
    </form>
</x-guest-layout>
