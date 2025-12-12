{{--
    Visualização detalhada da Ordem de Serviço com abas de seções
--}}

<x-layouts.module title="OS #{{ str_pad((string) $serviceOrder->order_number, 6, '0', STR_PAD_LEFT) }}">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Ordens de Serviço', 'href' => route('service-orders.index')],
            ['label' => 'OS #' . str_pad((string) $serviceOrder->order_number, 6, '0', STR_PAD_LEFT)],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        <div class="flex items-center gap-4">
            <span>OS #{{ str_pad((string) $serviceOrder->order_number, 6, '0', STR_PAD_LEFT) }}</span>
            @if ($serviceOrder->status)
                <x-spire::badge :variant="$serviceOrder->status->color ?? 'secondary'" size="lg">
                    {{ $serviceOrder->status->name }}
                </x-spire::badge>
            @endif
            @if ($serviceOrder->is_critical)
                <x-spire::badge variant="danger" size="lg">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Crítica
                </x-spire::badge>
            @endif
        </div>
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('service-orders.edit', $serviceOrder) }}" variant="secondary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Editar
        </x-spire::button>
        <x-spire::button href="{{ route('service-orders.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-spire::alert type="success" class="mb-6">
            {{ session('success') }}
        </x-spire::alert>
    @endif

    {{-- Section Navigation Tabs --}}
    <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
        <nav class="flex flex-wrap gap-x-1 -mb-px" aria-label="Seções da OS">
            <button type="button" data-section="dados"
                class="os-section-tab px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ $activeSection === 'dados' ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                Dados da OS
            </button>
            <button type="button" data-section="consumidor"
                class="os-section-tab px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ $activeSection === 'consumidor' ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                Consumidor
            </button>
            <button type="button" data-section="produto"
                class="os-section-tab px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ $activeSection === 'produto' ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                Produto
            </button>
            <button type="button" data-section="reparo"
                class="os-section-tab px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ $activeSection === 'reparo' ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                Reparo
            </button>
            <button type="button" data-section="evidencias"
                class="os-section-tab px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ $activeSection === 'evidencias' ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                Evidências
                @if ($serviceOrder->evidence->count() > 0)
                    <span
                        class="ml-1.5 px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        {{ $serviceOrder->evidence->count() }}
                    </span>
                @endif
            </button>
            <button type="button" data-section="acompanhamento"
                class="os-section-tab px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ $activeSection === 'acompanhamento' ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                Acompanhamento
                @if ($serviceOrder->comments->count() > 0)
                    <span
                        class="ml-1.5 px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        {{ $serviceOrder->comments->count() }}
                    </span>
                @endif
            </button>
            <button type="button" data-section="suporte"
                class="os-section-tab px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ $activeSection === 'suporte' ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                Suporte
                @if ($serviceOrder->supports->count() > 0)
                    <span
                        class="ml-1.5 px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        {{ $serviceOrder->supports->count() }}
                    </span>
                @endif
            </button>
            <button type="button" data-section="valores"
                class="os-section-tab px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ $activeSection === 'valores' ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                Valores
            </button>
            <button type="button" data-section="documentacao"
                class="os-section-tab px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ $activeSection === 'documentacao' ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                Doc. Técnica
            </button>
        </nav>
    </div>

    {{-- Section Panels --}}
    <div id="os-sections">
        {{-- Dados da OS --}}
        <div id="section-dados" class="os-section-panel {{ $activeSection === 'dados' ? '' : 'hidden' }}">
            @include('service-orders.partials.sections.dados', ['serviceOrder' => $serviceOrder])
        </div>

        {{-- Consumidor --}}
        <div id="section-consumidor" class="os-section-panel {{ $activeSection === 'consumidor' ? '' : 'hidden' }}">
            @include('service-orders.partials.sections.consumidor', ['serviceOrder' => $serviceOrder])
        </div>

        {{-- Produto --}}
        <div id="section-produto" class="os-section-panel {{ $activeSection === 'produto' ? '' : 'hidden' }}">
            @include('service-orders.partials.sections.produto', ['serviceOrder' => $serviceOrder])
        </div>

        {{-- Reparo --}}
        <div id="section-reparo" class="os-section-panel {{ $activeSection === 'reparo' ? '' : 'hidden' }}">
            @include('service-orders.partials.sections.reparo', ['serviceOrder' => $serviceOrder])
        </div>

        {{-- Evidências --}}
        <div id="section-evidencias" class="os-section-panel {{ $activeSection === 'evidencias' ? '' : 'hidden' }}">
            @include('service-orders.partials.sections.evidencias', ['serviceOrder' => $serviceOrder])
        </div>

        {{-- Acompanhamento --}}
        <div id="section-acompanhamento"
            class="os-section-panel {{ $activeSection === 'acompanhamento' ? '' : 'hidden' }}">
            @include('service-orders.partials.sections.acompanhamento', ['serviceOrder' => $serviceOrder])
        </div>

        {{-- Suporte --}}
        <div id="section-suporte" class="os-section-panel {{ $activeSection === 'suporte' ? '' : 'hidden' }}">
            @include('service-orders.partials.sections.suporte', ['serviceOrder' => $serviceOrder])
        </div>

        {{-- Valores --}}
        <div id="section-valores" class="os-section-panel {{ $activeSection === 'valores' ? '' : 'hidden' }}">
            @include('service-orders.partials.sections.valores', ['serviceOrder' => $serviceOrder])
        </div>

        {{-- Documentação Técnica --}}
        <div id="section-documentacao"
            class="os-section-panel {{ $activeSection === 'documentacao' ? '' : 'hidden' }}">
            @include('service-orders.partials.sections.documentacao', ['serviceOrder' => $serviceOrder])
        </div>
    </div>

    {{-- Section Navigation Script --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const tabs = document.querySelectorAll('.os-section-tab');
                const panels = document.querySelectorAll('.os-section-panel');

                tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        const section = tab.dataset.section;

                        // Update tabs
                        tabs.forEach(t => {
                            t.classList.remove('border-blue-600', 'text-blue-600',
                                'dark:border-blue-400', 'dark:text-blue-400');
                            t.classList.add('border-transparent', 'text-gray-500',
                                'dark:text-gray-400');
                        });
                        tab.classList.remove('border-transparent', 'text-gray-500',
                            'dark:text-gray-400');
                        tab.classList.add('border-blue-600', 'text-blue-600',
                            'dark:border-blue-400', 'dark:text-blue-400');

                        // Update panels
                        panels.forEach(p => p.classList.add('hidden'));
                        document.getElementById(`section-${section}`).classList.remove('hidden');

                        // Update URL
                        const url = new URL(window.location);
                        url.searchParams.set('section', section);
                        window.history.replaceState({}, '', url);
                    });
                });
            });
        </script>
    @endpush
</x-layouts.module>
