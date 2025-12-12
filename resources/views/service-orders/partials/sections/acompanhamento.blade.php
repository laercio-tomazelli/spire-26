{{-- Seção: Acompanhamento (Comentários/Timeline) --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Timeline de Comentários --}}
    <x-spire::card class="lg:col-span-2">
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Histórico de Acompanhamento</h3>
        </x-slot:header>

        @if ($serviceOrder->comments->count() > 0)
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @foreach ($serviceOrder->comments->sortByDesc('created_at') as $index => $comment)
                        <li>
                            <div class="relative pb-8">
                                @if (!$loop->last)
                                    <span class="absolute left-5 top-5 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700"
                                        aria-hidden="true"></span>
                                @endif
                                <div class="relative flex items-start space-x-3">
                                    <x-spire::avatar size="sm" :name="$comment->user?->name ?? 'Sistema'" />
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $comment->user?->name ?? 'Sistema' }}
                                            </p>
                                            @if ($comment->is_internal)
                                                <x-spire::badge variant="warning"
                                                    size="sm">Interno</x-spire::badge>
                                            @endif
                                            @if ($comment->type)
                                                <x-spire::badge variant="secondary" size="sm">
                                                    {{ $comment->type }}
                                                </x-spire::badge>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $comment->created_at->format('d/m/Y H:i') }}
                                            ({{ $comment->created_at->diffForHumans() }})
                                        </p>
                                        <div
                                            class="mt-2 text-sm text-gray-700 dark:text-gray-300 prose prose-sm dark:prose-invert max-w-none">
                                            {!! nl2br(e($comment->content)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <x-spire::empty-state title="Nenhum comentário"
                description="Não há comentários ou atualizações registradas para esta OS."
                icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>' />
        @endif

        {{-- Add Comment Form --}}
        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="new_comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Adicionar Comentário
                    </label>
                    <x-spire::textarea id="new_comment" name="content" rows="3"
                        placeholder="Digite seu comentário..." />
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_internal"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Comentário interno</span>
                    </label>
                    <x-spire::button type="submit">
                        Enviar Comentário
                    </x-spire::button>
                </div>
            </form>
        </div>
    </x-spire::card>

    {{-- Agendamentos --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Agendamentos</h3>
        </x-slot:header>

        @if ($serviceOrder->schedules->count() > 0)
            <div class="space-y-4">
                @foreach ($serviceOrder->schedules->sortByDesc('scheduled_date') as $schedule)
                    <div
                        class="p-3 rounded-lg border {{ $schedule->is_confirmed ? 'border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/20' : 'border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800/50' }}">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d/m/Y') }}
                            </p>
                            @if ($schedule->is_confirmed)
                                <x-spire::badge variant="success" size="sm">Confirmado</x-spire::badge>
                            @else
                                <x-spire::badge variant="warning" size="sm">Pendente</x-spire::badge>
                            @endif
                        </div>
                        @if ($schedule->period)
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Período: {{ $schedule->period }}
                            </p>
                        @endif
                        @if ($schedule->notes)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $schedule->notes }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <x-spire::empty-state title="Nenhum agendamento" description="Não há agendamentos para esta OS."
                icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' />
        @endif
    </x-spire::card>
</div>
