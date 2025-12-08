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

    {{-- Filter Container --}}
    <div id="users-filter-container" data-url="{{ route('users.index') }}" data-csrf="{{ csrf_token() }}">
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
                    <button type="submit" id="filter-btn"
                        class="relative inline-flex items-center justify-center h-9 px-4 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <svg id="filter-loading" class="w-4 h-4 mr-1.5 animate-spin hidden" fill="none"
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
                        <span id="filter-count"
                            class="absolute -top-2 -right-2 hidden items-center justify-center min-w-5 h-5 px-1.5 text-xs font-bold text-white bg-red-500 rounded-full"></span>
                    </button>
                    <button id="clear-filters-btn" type="button"
                        class="hidden inline-flex items-center justify-center h-9 px-4 text-sm font-medium text-gray-700 dark:text-gray-200 bg-transparent hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
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

    {{-- Vanilla JS Filter Logic --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('users-filter-container');
                const form = document.getElementById('users-filter-form');
                const searchInput = document.getElementById('filter-search');
                const tableContainer = document.getElementById('users-table');
                const filterBtn = document.getElementById('filter-btn');
                const filterLoading = document.getElementById('filter-loading');
                const filterIcon = document.getElementById('filter-icon');
                const filterCount = document.getElementById('filter-count');
                const clearBtn = document.getElementById('clear-filters-btn');

                if (!container || !form || !tableContainer) return;

                const baseUrl = container.dataset.url;
                let loading = false;

                // Debounce function
                const debounce = (fn, delay) => {
                    let timer;
                    return (...args) => {
                        clearTimeout(timer);
                        timer = setTimeout(() => fn(...args), delay);
                    };
                };

                // Get current filter values
                const getFilters = () => {
                    const formData = new FormData(form);
                    const filters = {};
                    formData.forEach((value, key) => {
                        if (value) filters[key] = value;
                    });
                    return filters;
                };

                // Update filter count badge
                const updateFilterCount = () => {
                    const filters = getFilters();
                    const count = Object.keys(filters).length;

                    if (count > 0) {
                        filterCount.textContent = count;
                        filterCount.classList.remove('hidden');
                        filterCount.classList.add('flex');
                        clearBtn.classList.remove('hidden');
                    } else {
                        filterCount.classList.add('hidden');
                        filterCount.classList.remove('flex');
                        clearBtn.classList.add('hidden');
                    }
                };

                // Apply filters via AJAX
                const applyFilters = async () => {
                    if (loading) return;
                    loading = true;

                    // Show loading state
                    filterLoading.classList.remove('hidden');
                    filterIcon.classList.add('hidden');
                    filterBtn.classList.add('opacity-75');

                    try {
                        const filters = getFilters();
                        const params = new URLSearchParams(filters);
                        const url = baseUrl + (params.toString() ? '?' + params.toString() : '');

                        // Update browser URL without reload
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
                            tableContainer.innerHTML = html;
                        }

                        updateFilterCount();
                    } catch (error) {
                        console.error('Filter error:', error);
                        if (typeof Spire !== 'undefined' && Spire.toast) {
                            Spire.toast.error('Erro ao filtrar usuários');
                        }
                    } finally {
                        loading = false;
                        filterLoading.classList.add('hidden');
                        filterIcon.classList.remove('hidden');
                        filterBtn.classList.remove('opacity-75');
                    }
                };

                // Debounced search
                const debouncedFilter = debounce(applyFilters, 300);

                // Event listeners
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    applyFilters();
                });

                searchInput.addEventListener('input', debouncedFilter);

                // Listen for select changes
                window.addEventListener('select-change', (e) => {
                    if (e.detail?.name === 'user_type' || e.detail?.name === 'is_active') {
                        applyFilters();
                    }
                });

                // Clear filters
                clearBtn.addEventListener('click', () => {
                    // Reset form
                    form.reset();

                    // Reset select components
                    window.dispatchEvent(new CustomEvent('select-reset', {
                        detail: {
                            name: '*'
                        }
                    }));

                    // Apply empty filters
                    applyFilters();
                });

                // Initial count update
                updateFilterCount();
            });
        </script>
    @endpush
</x-layouts.module>
