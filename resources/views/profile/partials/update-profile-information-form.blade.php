<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Informações do Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Atualize as informações do seu perfil e endereço de e-mail.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <x-spire::input name="name" type="text" label="Nome" :value="old('name', $user->name)" required autofocus
            autocomplete="name" :error="$errors->first('name')" />

        <x-spire::input name="email" type="email" label="E-mail" :value="old('email', $user->email)" required autocomplete="username"
            :error="$errors->first('email')" />

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    {{ __('Seu endereço de e-mail não foi verificado.') }}

                    <button form="send-verification"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        {{ __('Clique aqui para reenviar o e-mail de verificação.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ __('Um novo link de verificação foi enviado para seu endereço de e-mail.') }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-spire::button type="submit">{{ __('Salvar') }}</x-spire::button>

            @if (session('status') === 'profile-updated')
                <p id="saved-message" class="text-sm text-gray-600 dark:text-gray-400 transition-opacity duration-300">
                    {{ __('Salvo.') }}</p>
                <script>
                    setTimeout(() => {
                        const el = document.getElementById('saved-message');
                        if (el) el.style.opacity = '0';
                    }, 2000);
                </script>
            @endif
        </div>
    </form>
</section>
