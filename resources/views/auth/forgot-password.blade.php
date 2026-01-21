<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-white">{{ __('Recuperar senha') }}</h2>
        <p class="mt-2 text-sm text-gray-400">
            {{ __('Informe seu e-mail e enviaremos um link para redefinir sua senha.') }}
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <x-spire::alert type="success" class="mb-4">
            {{ session('status') }}
        </x-spire::alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
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
            :error="$errors->first('email')"
        />

        <div class="mt-6">
            <x-spire::button type="submit" class="w-full justify-center">
                {{ __('Enviar link de recuperação') }}
            </x-spire::button>
        </div>

        <div class="mt-4 text-center">
            <a class="text-sm text-indigo-400 hover:text-indigo-300 transition" href="{{ route('login') }}">
                ← {{ __('Voltar para o login') }}
            </a>
        </div>
    </form>
</x-guest-layout>
