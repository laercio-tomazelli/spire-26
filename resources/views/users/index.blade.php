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

    {{-- Filters Container --}}

<x-spire::card class="mb-6">
            <x-spire::breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Usuários']]" />
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                Usuários
                            </h1>
                                                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Gerencie os usuários do sistema
                                </div>
                                                    </div>

                                                    <div class="flex items-center gap-3">
                                <a href="https://spire-26.wks.dev/users/create" data-v="button" class="inline-flex items-center justify-center h-9 px-4 text-sm rounded-lg font-medium transition focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Novo Usuário
    </a>
                            </div>
                                            </div>
</x-spire::card>


    <div id="users-filter-container">
        {{-- Filters --}}
        <x-spire::card class="mb-6">
            <form id="users-filter-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Buscar</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="filter-search" name="search" value="{{ request('search', '') }}"
                            placeholder="Nome, e-mail ou usuário..."
                            class="w-full h-9 pl-10 pr-4 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" />
                    </div>
                </div>

                <div>
                    <x-spire::select name="user_type" label="Tipo de Usuário" placeholder="Todos os tipos"
                        :value="request('user_type')" :options="$userTypes" />
                </div>

                <div>
                    <x-spire::select name="is_active" label="Status" placeholder="Todos" :value="request('is_active')"
                        :options="\App\Enums\Status::selectOptions()" />
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" id="filter-submit-btn"
                        class="relative inline-flex items-center justify-center h-9 px-4 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <svg id="filter-loading-icon" class="w-4 h-4 mr-1.5 animate-spin hidden" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <svg id="filter-icon" class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filtrar
                        <span id="active-filters-badge"
                            class="absolute -top-2 -right-2 flex items-center justify-center min-w-5 h-5 px-1.5 text-xs font-bold text-white bg-red-500 rounded-full hidden"></span>
                    </button>
                    <button id="clear-filters-btn" type="button"
                        class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium text-gray-700 dark:text-gray-200 bg-transparent hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors hidden">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Limpar
                    </button>
                </div>
            </form>
        </x-spire::card>

        {{-- Users Table --}}
        <x-spire::card>
            <div id="users-table">
                @include('users.partials.table', ['users' => $users])
            </div>
        </x-spire::card>
    </div>

    {{-- JavaScript Filter Component --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('users-filter-form');
                const searchInput = document.getElementById('filter-search');
                const submitBtn = document.getElementById('filter-submit-btn');
                const loadingIcon = document.getElementById('filter-loading-icon');
                const filterIcon = document.getElementById('filter-icon');
                const badge = document.getElementById('active-filters-badge');
                const clearBtn = document.getElementById('clear-filters-btn');
                const usersTable = document.getElementById('users-table');

                let debounceTimer;
                const filters = {
                    search: '{{ request('search', '') }}',
                    user_type: '{{ request('user_type', '') }}',
                    is_active: '{{ request('is_active', '') }}'
                };

                function updateActiveFiltersUI() {
                    const count = Object.values(filters).filter(v => v !== '').length;
                    if (count > 0) {
                        badge.textContent = count;
                        badge.classList.remove('hidden');
                        clearBtn.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                        clearBtn.classList.add('hidden');
                    }
                }

                function setLoading(loading) {
                    if (loading) {
                        loadingIcon.classList.remove('hidden');
                        filterIcon.classList.add('hidden');
                        submitBtn.classList.add('opacity-75');
                    } else {
                        loadingIcon.classList.add('hidden');
                        filterIcon.classList.remove('hidden');
                        submitBtn.classList.remove('opacity-75');
                    }
                }

                async function filter() {
                    setLoading(true);

                    try {
                        const params = new URLSearchParams();
                        Object.entries(filters).forEach(([key, value]) => {
                            if (value !== '') {
                                params.append(key, value);
                            }
                        });

                        const url = '{{ route('users.index') }}' + (params.toString() ? '?' + params.toString() :
                            '');
                        window.history.replaceState({}, '', url);

                        const response = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html'
                            }
                        });

                        if (response.ok) {
                            const html = await response.text();
                            usersTable.innerHTML = html;
                        }
                    } catch (error) {
                        console.error('Filter error:', error);
                        if (typeof SpireUI !== 'undefined' && SpireUI.toast) {
                            SpireUI.toast.error('Erro ao filtrar usuários');
                        }
                    } finally {
                        setLoading(false);
                        updateActiveFiltersUI();
                    }
                }

                // Form submit
                form?.addEventListener('submit', (e) => {
                    e.preventDefault();
                    filters.search = searchInput.value;
                    filter();
                });

                // Search input with debounce
                searchInput?.addEventListener('input', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        filters.search = searchInput.value;
                        filter();
                    }, 300);
                });

                // Listen for select changes
                window.addEventListener('select:change', (e) => {
                    if (e.detail.name === 'user_type') {
                        filters.user_type = e.detail.value;
                        filter();
                    }
                    if (e.detail.name === 'is_active') {
                        filters.is_active = e.detail.value;
                        filter();
                    }
                });

                // Clear filters
                clearBtn?.addEventListener('click', () => {
                    filters.search = '';
                    filters.user_type = '';
                    filters.is_active = '';
                    searchInput.value = '';

                    // Reset select components
                    window.dispatchEvent(new CustomEvent('select-reset', {
                        detail: {
                            name: '*'
                        }
                    }));

                    filter();
                });

                // Initial UI update
                updateActiveFiltersUI();
            });
        </script>
    @endpush
</x-layouts.module>
