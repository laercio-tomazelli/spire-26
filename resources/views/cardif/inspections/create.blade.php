<x-layouts.cardif>
    <x-slot:header>Nova Vistoria</x-slot:header>

    <div class="space-y-4 max-w-5xl">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Nova Vistoria</h1>

        @include('cardif.inspections._form', [
            'action' => route('cardif.inspections.store'),
            'method' => 'POST',
            'submitLabel' => 'Criar Vistoria',
        ])
    </div>
</x-layouts.cardif>
