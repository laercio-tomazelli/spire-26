<x-layouts.cardif>
    <x-slot:header>Editar Vistoria #{{ $inspection->claim_number }}</x-slot:header>

    <div class="space-y-4 max-w-5xl">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
            Editar Vistoria
            <span class="text-gray-400 text-base font-mono">#{{ $inspection->claim_number }}</span>
        </h1>

        @include('cardif.inspections._form', [
            'action' => route('cardif.inspections.update', $inspection),
            'method' => 'PUT',
            'submitLabel' => 'Salvar Alterações',
        ])
    </div>
</x-layouts.cardif>
