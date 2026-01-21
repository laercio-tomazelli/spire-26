<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-white">{{ __('Verificar e-mail') }}</h2>
        <p class="mt-2 text-sm text-gray-400">
            {{ __('Obrigado por se cadastrar! Antes de começar, você poderia verificar seu endereço de e-mail clicando no link que acabamos de enviar? Se você não recebeu o e-mail, teremos prazer em enviar outro.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <x-spire::alert type="success" class="mb-4">
            {{ __('Um novo link de verificação foi enviado para o endereço de e-mail informado durante o cadastro.') }}
        </x-spire::alert>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-spire::button type="submit">
                {{ __('Reenviar E-mail') }}
            </x-spire::button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-indigo-400 hover:text-indigo-300 transition">
                {{ __('Sair') }}
            </button>
        </form>
    </div>
</x-guest-layout>
