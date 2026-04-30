<x-layouts.cardif>
    <x-slot:header>Dashboard</x-slot:header>

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Dashboard Cardif</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Visão geral das operações de pós-venda Cardif.
            </p>
        </div>

        {{-- KPI cards --}}
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <x-spire::stats-card title="Ordens de Serviço" value="0" icon="briefcase" iconColor="primary" />
            <x-spire::stats-card title="Em Andamento" value="0" icon="clock" iconColor="info" />
            <x-spire::stats-card title="Concluídas" value="0" icon="check" iconColor="success" />
            <x-spire::stats-card title="Pendentes" value="0" icon="alert" iconColor="warning" />
        </div>

        <x-spire::card>
            <x-spire::empty-state title="Sem dados ainda"
                description="Quando ordens de serviço forem registradas para a Cardif, elas aparecerão aqui."
                icon="inbox" />
        </x-spire::card>
    </div>
</x-layouts.cardif>
