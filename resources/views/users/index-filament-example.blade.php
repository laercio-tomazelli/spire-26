{{--
    Exemplo de uso da tabela estilo Filament
    Este arquivo serve como referência para implementação
--}}

<x-layouts.module title="Usuários">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Usuários']]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Gerencie os usuários do sistema
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('users.create') }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Novo Usuário
        </x-spire::button>
    </x-slot:headerActions>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-spire::alert type="success" class="mb-6">
            {{ session('success') }}
        </x-spire::alert>
    @endif

    {{-- Alpine Container for Table --}}
    <div x-data="filamentTable()" 
         x-on:table-goto-page.window="gotoPage($event.detail.page)"
         x-on:table-previous-page.window="previousPage()"
         x-on:table-next-page.window="nextPage()"
         x-on:table-per-page.window="changePerPage($event.detail.value)"
         x-on:table-toggle-page-selection.window="togglePageSelection()"
         x-on:table-toggle-selection.window="toggleSelection($event.detail.key)"
         x-on:table-sort.window="sort($event.detail.field)"
         x-on:table-apply-filters.window="applyFilters()"
         x-on:table-filter-change.window="setFilter($event.detail.key, $event.detail.value)"
         x-on:table-toggle-column.window="toggleColumn($event.detail.name, $event.detail.visible)"
         x-on:table-reset-columns.window="resetColumns()"
         x-on:table-reset-filters.window="resetFilters()">
        {{-- Filament-style Table --}}
        <x-ui.table>
            {{-- Table Header with Search, Filters, etc --}}
            <x-slot:header>
                {{-- Status Tabs (como no Filament) --}}
                <x-ui.table.tabs>
                    <x-ui.table.tab :active="!request('status')" :count="$counts['all'] ?? $users->total()"
                        x-on:click="$dispatch('table-filter-change', { key: 'status', value: '' })">
                        Todos
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('status') === 'active'" :count="$counts['active'] ?? null"
                        x-on:click="$dispatch('table-filter-change', { key: 'status', value: 'active' })">
                        Ativos
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('status') === 'inactive'" :count="$counts['inactive'] ?? null"
                        x-on:click="$dispatch('table-filter-change', { key: 'status', value: 'inactive' })">
                        Inativos
                    </x-ui.table.tab>
                </x-ui.table.tabs>

                {{-- Toolbar --}}
                <x-ui.table.header :search="true" searchPlaceholder="Buscar usuários...">
                    {{-- Bulk Actions (aparecem quando há seleção) --}}
                    <x-slot:bulkActions>
                        <x-spire::button size="sm" variant="danger" x-on:click="bulkDelete()">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Excluir selecionados
                        </x-spire::button>
                    </x-slot:bulkActions>

                    {{-- Filters Dropdown --}}
                    <x-slot:filters>
                        <x-ui.table.filters :activeCount="$activeFiltersCount ?? 0">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de
                                    Usuário</label>
                                <x-spire::select name="filter_user_type" placeholder="Todos" :options="$userTypes"
                                    :value="request('user_type', '')"
                                    x-on:select-change="$dispatch('table-filter-change', { key: 'user_type', value: $event.detail.value })" />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <x-spire::select name="filter_is_active" placeholder="Todos" :options="\App\Enums\Status::selectOptions()"
                                    :value="request('is_active', '')"
                                    x-on:select-change="$dispatch('table-filter-change', { key: 'is_active', value: $event.detail.value })" />
                            </div>

                            <x-slot:footer>
                                <x-spire::button class="w-full" x-on:click="$dispatch('table-apply-filters')">
                                    Aplicar filtros
                                </x-spire::button>
                            </x-slot:footer>
                        </x-ui.table.filters>
                    </x-slot:filters>

                    {{-- Column Manager --}}
                    <x-slot:columnManager>
                        <x-ui.table.column-manager>
                            <x-ui.table.column-toggle name="user" label="Usuário" :checked="true"
                                :disabled="true" />
                            <x-ui.table.column-toggle name="type" label="Tipo" :checked="true" />
                            <x-ui.table.column-toggle name="link" label="Vínculo" :checked="true" />
                            <x-ui.table.column-toggle name="status" label="Status" :checked="true" />
                            <x-ui.table.column-toggle name="last_login" label="Último acesso" :checked="true" />
                        </x-ui.table.column-manager>
                    </x-slot:columnManager>
                </x-ui.table.header>
            </x-slot:header>

            {{-- Table Columns (thead) --}}
            <x-ui.table.columns :selectable="true">
                <x-ui.table.column label="Usuário" sortable sortField="name" data-column="user" />
                <x-ui.table.column label="Tipo" sortable sortField="user_type" data-column="type" />
                <x-ui.table.column label="Vínculo" data-column="link" />
                <x-ui.table.column label="Status" data-column="status" />
                <x-ui.table.column label="Último acesso" sortable sortField="last_login_at" data-column="last_login" />
                <th class="fi-ta-actions-header-cell"></th>
            </x-ui.table.columns>

            {{-- Table Body --}}
            <x-ui.table.body>
                @forelse ($users as $user)
                    <x-ui.table.row :record="$user" :selectable="true" :clickable="true">
                        {{-- User Info --}}
                        <x-ui.table.cell data-column="user">
                            <div class="flex items-center gap-3">
                                <x-spire::avatar size="sm" :name="$user->name" />
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </x-ui.table.cell>

                        {{-- User Type --}}
                        <x-ui.table.cell data-column="type">
                            <div class="flex flex-wrap items-center gap-1">
                                <x-spire::badge :variant="$user->user_type->badgeVariant()" :icon="$user->user_type->icon()">
                                    {{ $user->user_type->label() }}
                                </x-spire::badge>
                                @if ($user->is_partner_admin)
                                    <x-spire::badge variant="danger">Admin</x-spire::badge>
                                @endif
                            </div>
                        </x-ui.table.cell>

                        {{-- Link/Vínculo --}}
                        <x-ui.table.cell data-column="link">
                            @if ($user->partner)
                                {{ $user->partner->trade_name }}
                            @elseif ($user->manufacturer)
                                {{ $user->manufacturer->name }}
                            @elseif ($user->tenant)
                                {{ $user->tenant->name }}
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </x-ui.table.cell>

                        {{-- Status --}}
                        <x-ui.table.cell data-column="status">
                            @php $status = \App\Enums\Status::fromBool($user->is_active) @endphp
                            <x-spire::badge :variant="$status->badgeVariant()" :icon="$status->icon()">
                                {{ $status->label() }}
                            </x-spire::badge>
                        </x-ui.table.cell>

                        {{-- Last Login --}}
                        <x-ui.table.cell data-column="last_login">
                            @if ($user->last_login_at)
                                {{ $user->last_login_at->diffForHumans() }}
                            @else
                                <span class="text-gray-400">Nunca</span>
                            @endif
                        </x-ui.table.cell>

                        {{-- Actions --}}
                        <x-ui.table.actions>
                            <x-ui.table.action :href="route('users.edit', $user)"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z"/><path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"/></svg>'>
                                Editar
                            </x-ui.table.action>
                        </x-ui.table.actions>
                    </x-ui.table.row>
                @empty
                    <x-ui.table.empty-state title="Nenhum usuário encontrado"
                        description="Não há usuários cadastrados ou que correspondam aos filtros.">
                        <x-slot:action>
                            <x-spire::button href="{{ route('users.create') }}">
                                Criar primeiro usuário
                            </x-spire::button>
                        </x-slot:action>
                    </x-ui.table.empty-state>
                @endforelse
            </x-ui.table.body>

            {{-- Pagination Footer --}}
            <x-slot:footer>
                <x-ui.table.pagination :paginator="$users" />
            </x-slot:footer>
        </x-ui.table>
    </div>

    {{-- Alpine Component --}}
    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('filamentTable', () => ({
                    // State
                    loading: false,
                    search: '{{ request('search', '') }}',
                    sortField: '{{ request('sort', '') }}',
                    sortDirection: '{{ request('direction', 'asc') }}',
                    perPage: {{ request('per_page', 10) }},
                    page: {{ request('page', 1) }},
                    selected: [],
                    filters: {
                        user_type: '{{ request('user_type', '') }}',
                        is_active: '{{ request('is_active', '') }}',
                        status: '{{ request('status', '') }}'
                    },
                    visibleColumns: {
                        user: true,
                        type: true,
                        link: true,
                        status: true,
                        last_login: true
                    },

                    // Computed
                    get selectedCount() {
                        return this.selected.length;
                    },

                    get totalRecords() {
                        return {{ $users->total() }};
                    },

                    // Init
                    init() {
                        // Watch search changes
                        this.$watch('search', () => this.applyFilters());
                    },

                    // Sorting
                    sort(field) {
                        if (this.sortField === field) {
                            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                        } else {
                            this.sortField = field;
                            this.sortDirection = 'asc';
                        }
                        this.applyFilters();
                    },

                    // Pagination
                    gotoPage(page) {
                        this.page = page;
                        this.applyFilters();
                    },

                    previousPage() {
                        if (this.page > 1) {
                            this.page--;
                            this.applyFilters();
                        }
                    },

                    nextPage() {
                        this.page++;
                        this.applyFilters();
                    },

                    changePerPage(value) {
                        this.perPage = value;
                        this.page = 1;
                        this.applyFilters();
                    },

                    // Selection
                    isSelected(key) {
                        return this.selected.includes(key);
                    },

                    toggleSelection(key) {
                        const index = this.selected.indexOf(key);
                        const isNowSelected = index === -1;

                        if (isNowSelected) {
                            this.selected.push(key);
                        } else {
                            this.selected.splice(index, 1);
                        }

                        // Update row visual state
                        this.updateRowSelectionState(key, isNowSelected);
                        this.updateSelectAllCheckbox();
                    },

                    updateRowSelectionState(key, isSelected) {
                        const row = this.$el.querySelector(`tr[data-record-key="${key}"]`);
                        const checkbox = this.$el.querySelector(`.fi-ta-record-checkbox[value="${key}"]`);

                        if (row) {
                            if (isSelected) {
                                row.classList.add('bg-primary-50', 'dark:bg-primary-500/10');
                            } else {
                                row.classList.remove('bg-primary-50', 'dark:bg-primary-500/10');
                            }
                        }

                        if (checkbox) {
                            checkbox.checked = isSelected;
                        }
                    },

                    togglePageSelection() {
                        const pageKeys = this.getPageKeys();
                        const allSelected = pageKeys.every(key => this.selected.includes(key));
                        const newState = !allSelected;

                        if (allSelected) {
                            this.selected = this.selected.filter(key => !pageKeys.includes(key));
                        } else {
                            pageKeys.forEach(key => {
                                if (!this.selected.includes(key)) {
                                    this.selected.push(key);
                                }
                            });
                        }

                        // Update all row checkboxes
                        pageKeys.forEach(key => this.updateRowSelectionState(key, newState));
                        this.updateSelectAllCheckbox();
                    },

                    updateSelectAllCheckbox() {
                        const checkbox = this.$el.querySelector('.fi-ta-select-all');
                        if (checkbox) {
                            checkbox.checked = this.isPageSelected();
                            checkbox.indeterminate = this.isPagePartiallySelected();
                        }
                    },

                    isPageSelected() {
                        const pageKeys = this.getPageKeys();
                        return pageKeys.length > 0 && pageKeys.every(key => this.selected.includes(key));
                    },

                    isPagePartiallySelected() {
                        const pageKeys = this.getPageKeys();
                        const selectedOnPage = pageKeys.filter(key => this.selected.includes(key));
                        return selectedOnPage.length > 0 && selectedOnPage.length < pageKeys.length;
                    },

                    getPageKeys() {
                        return Array.from(document.querySelectorAll('.fi-ta-record-checkbox')).map(el => el
                            .value);
                    },

                    selectAll() {
                        // This would need a backend call to get all IDs
                        console.log('Select all records');
                    },

                    deselectAll() {
                        this.selected = [];
                        this.updateSelectAllCheckbox();
                    },

                    // Column visibility
                    toggleColumn(name, visible) {
                        this.visibleColumns[name] = visible;
                        // Toggle column visibility using data-column attribute
                        this.$el.querySelectorAll(`[data-column="${name}"]`).forEach(cell => {
                            cell.style.display = visible ? '' : 'none';
                        });
                    },

                    resetColumns() {
                        Object.keys(this.visibleColumns).forEach(key => {
                            this.visibleColumns[key] = true;
                            this.$el.querySelectorAll(`[data-column="${key}"]`).forEach(cell => {
                                cell.style.display = '';
                            });
                        });
                        // Reset checkboxes in column manager
                        this.$el.querySelectorAll('.fi-ta-col-manager-item input[type="checkbox"]').forEach(
                            cb => {
                                if (!cb.disabled) cb.checked = true;
                            });
                    },

                    // Filters
                    resetFilters() {
                        this.filters = {
                            user_type: '',
                            is_active: '',
                            status: ''
                        };
                        this.page = 1;
                        // Reset select elements
                        this.$el.querySelectorAll('.fi-ta-filters select').forEach(select => {
                            select.value = '';
                        });
                        this.applyFilters();
                    },

                    setFilter(key, value) {
                        this.filters[key] = value;
                        this.page = 1;
                        this.applyFilters();
                    },

                    async applyFilters() {
                        this.loading = true;

                        try {
                            const params = new URLSearchParams();

                            // Add search
                            if (this.search) params.append('search', this.search);

                            // Add filters
                            Object.entries(this.filters).forEach(([key, value]) => {
                                if (value !== '') params.append(key, value);
                            });

                            // Add sorting
                            if (this.sortField) {
                                params.append('sort', this.sortField);
                                params.append('direction', this.sortDirection);
                            }

                            // Add pagination
                            params.append('page', this.page);
                            params.append('per_page', this.perPage);

                            const url = '{{ route('users.filament') }}' + (params.toString() ? '?' +
                                params.toString() : '');

                            // Update browser URL
                            window.history.replaceState({}, '', url);

                            // Fetch HTML content
                            const response = await fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'text/html'
                                }
                            });

                            if (response.ok) {
                                const html = await response.text();
                                // Update table content
                                this.$el.querySelector('.fi-ta-content').innerHTML = html;
                            }
                        } catch (error) {
                            console.error('Filter error:', error);
                        } finally {
                            this.loading = false;
                        }
                    },

                    // Bulk Actions
                    async bulkDelete() {
                        if (!confirm(
                                `Tem certeza que deseja excluir ${this.selectedCount} usuário(s)?`)) {
                            return;
                        }

                        // TODO: Implementar rota users.bulk-delete no backend
                        console.log('Bulk delete IDs:', this.selected);
                        alert('Funcionalidade de exclusão em massa será implementada em breve.');

                        /* Exemplo de implementação quando a rota existir:
                        try {
                            const response = await fetch('/users/bulk-delete', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    ids: this.selected
                                })
                            });

                            if (response.ok) {
                                this.selected = [];
                                this.applyFilters();
                            }
                        } catch (error) {
                            console.error('Bulk delete error:', error);
                        }
                        */
                    }
                }));
            });
        </script>
    @endpush
</x-layouts.module>
