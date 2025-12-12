{{-- Seção: Suporte --}}
<div class="grid grid-cols-1 gap-6">
    {{-- Lista de Suportes --}}
    <x-spire::card>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Chamados de Suporte</h3>
                <x-spire::button size="sm" variant="secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Novo Suporte
                </x-spire::button>
            </div>
        </x-slot:header>

        @if ($serviceOrder->supports->count() > 0)
            <div class="space-y-4">
                @foreach ($serviceOrder->supports->sortByDesc('created_at') as $support)
                    <div
                        class="p-4 rounded-lg border border-gray-200 dark:border-gray-700 {{ $support->is_resolved ? 'bg-green-50/50 dark:bg-green-900/10' : 'bg-gray-50 dark:bg-gray-800/50' }}">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <x-spire::avatar size="sm" :name="$support->user?->name ?? 'Usuário'" />
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $support->user?->name ?? 'Usuário' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $support->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if ($support->type)
                                    <x-spire::badge variant="info" size="sm">{{ $support->type }}</x-spire::badge>
                                @endif
                                @if ($support->is_resolved)
                                    <x-spire::badge variant="success" size="sm">Resolvido</x-spire::badge>
                                @else
                                    <x-spire::badge variant="warning" size="sm">Pendente</x-spire::badge>
                                @endif
                            </div>
                        </div>

                        @if ($support->subject)
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                {{ $support->subject }}
                            </h4>
                        @endif

                        <div
                            class="text-sm text-gray-700 dark:text-gray-300 prose prose-sm dark:prose-invert max-w-none">
                            {!! nl2br(e($support->description ?? ($support->content ?? ''))) !!}
                        </div>

                        @if ($support->response)
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                    Resposta:
                                </p>
                                <div class="text-sm text-gray-700 dark:text-gray-300">
                                    {!! nl2br(e($support->response)) !!}
                                </div>
                                @if ($support->responded_at)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                        Respondido em
                                        {{ \Carbon\Carbon::parse($support->responded_at)->format('d/m/Y H:i') }}
                                        @if ($support->respondedBy)
                                            por {{ $support->respondedBy->name }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <x-spire::empty-state title="Nenhum chamado de suporte"
                description="Não há chamados de suporte abertos para esta OS."
                icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' />
        @endif
    </x-spire::card>

    {{-- Novo Chamado de Suporte Form --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Abrir Chamado de Suporte</h3>
        </x-slot:header>

        <form action="#" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="support_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tipo de Suporte
                    </label>
                    <x-spire::select id="support_type" name="type" :options="[
                        ['value' => 'technical', 'label' => 'Técnico'],
                        ['value' => 'parts', 'label' => 'Peças'],
                        ['value' => 'warranty', 'label' => 'Garantia'],
                        ['value' => 'other', 'label' => 'Outro'],
                    ]" placeholder="Selecione..." />
                </div>
                <div>
                    <label for="support_subject"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Assunto
                    </label>
                    <x-spire::input id="support_subject" name="subject" placeholder="Resumo do chamado" />
                </div>
            </div>
            <div>
                <label for="support_description"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Descrição
                </label>
                <x-spire::textarea id="support_description" name="description" rows="4"
                    placeholder="Descreva detalhadamente sua dúvida ou problema..." />
            </div>
            <div class="flex justify-end">
                <x-spire::button type="submit">
                    Enviar Chamado
                </x-spire::button>
            </div>
        </form>
    </x-spire::card>
</div>
