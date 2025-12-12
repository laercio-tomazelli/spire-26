{{-- Seção: Evidências --}}
<x-spire::card>
    <x-slot:header>
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Evidências</h3>
            <x-spire::button size="sm" variant="secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Adicionar Evidência
            </x-spire::button>
        </div>
    </x-slot:header>

    @if ($serviceOrder->evidence->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach ($serviceOrder->evidence as $evidence)
                <div class="group relative bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden aspect-square">
                    @if ($evidence->isImage())
                        <img src="{{ Storage::url($evidence->file_path) }}" alt="{{ $evidence->description }}"
                            class="w-full h-full object-cover">
                    @elseif ($evidence->isVideo())
                        <div class="w-full h-full flex items-center justify-center bg-gray-900">
                            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    @elseif ($evidence->isPdf())
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif

                    {{-- Overlay --}}
                    <div
                        class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                        <a href="{{ Storage::url($evidence->file_path) }}" target="_blank"
                            class="p-2 bg-white rounded-full text-gray-900 hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                        <a href="{{ Storage::url($evidence->file_path) }}" download
                            class="p-2 bg-white rounded-full text-gray-900 hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                        </a>
                    </div>

                    {{-- Category Badge --}}
                    @if ($evidence->category)
                        <div class="absolute top-2 left-2">
                            <x-spire::badge variant="secondary" size="sm">
                                {{ $evidence->category }}
                            </x-spire::badge>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Evidence Details --}}
        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Detalhes dos Arquivos</h4>
            <div class="space-y-2">
                @foreach ($serviceOrder->evidence as $evidence)
                    <div class="flex items-center justify-between py-2 px-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $evidence->file_name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $evidence->formatted_file_size }} •
                                    {{ $evidence->created_at->format('d/m/Y H:i') }}
                                    @if ($evidence->uploadedByUser)
                                        • {{ $evidence->uploadedByUser->name }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        @if ($evidence->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">
                                {{ $evidence->description }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <x-spire::empty-state title="Nenhuma evidência cadastrada"
            description="Adicione fotos, vídeos ou documentos para documentar o serviço."
            icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' />
    @endif
</x-spire::card>
