<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Alterar Senha') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Certifique-se de que sua conta esteja usando uma senha longa e aleatória para se manter segura.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <x-spire::input name="current_password" type="password" label="Senha Atual" placeholder="••••••••"
            autocomplete="current-password" :error="$errors->updatePassword->first('current_password')" password />

        <x-spire::input name="password" type="password" label="Nova Senha" placeholder="••••••••"
            autocomplete="new-password" :error="$errors->updatePassword->first('password')" password />

        <x-spire::input name="password_confirmation" type="password" label="Confirmar Senha" placeholder="••••••••"
            autocomplete="new-password" password />

        <div class="flex items-center gap-4">
            <x-spire::button type="submit">{{ __('Salvar') }}</x-spire::button>

            @if (session('status') === 'password-updated')
                <p class="text-sm text-gray-600 dark:text-gray-400 animate-fade-out">{{ __('Salvo.') }}</p>
            @endif
        </div>
    </form>
</section>
