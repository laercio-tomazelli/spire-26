<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Excluir Conta') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Depois que sua conta for excluída, todos os seus recursos e dados serão permanentemente excluídos. Antes de excluir sua conta, por favor baixe quaisquer dados ou informações que você deseja manter.') }}
        </p>
    </header>

    <x-spire::button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="!bg-red-600 hover:!bg-red-500 !from-red-600 !to-red-600">
        {{ __('Excluir Conta') }}
    </x-spire::button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Tem certeza que deseja excluir sua conta?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Depois que sua conta for excluída, todos os seus recursos e dados serão permanentemente excluídos. Por favor, digite sua senha para confirmar que deseja excluir permanentemente sua conta.') }}
            </p>

            <div class="mt-6">
                <x-spire::input
                    name="password"
                    type="password"
                    label="Senha"
                    placeholder="Sua senha"
                    :error="$errors->userDeletion->first('password')"
                    password
                />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-spire::button type="button" x-on:click="$dispatch('close')"
                    class="!bg-gray-200 !text-gray-800 hover:!bg-gray-300 !from-gray-200 !to-gray-200 dark:!bg-gray-700 dark:!text-gray-200 dark:hover:!bg-gray-600 dark:!from-gray-700 dark:!to-gray-700">
                    {{ __('Cancelar') }}
                </x-spire::button>

                <x-spire::button type="submit" class="!bg-red-600 hover:!bg-red-500 !from-red-600 !to-red-600">
                    {{ __('Excluir Conta') }}
                </x-spire::button>
            </div>
        </form>
    </x-modal>
</section>
