@php
    use App\Enums\InspectionStatus;

    // Mapas literais para que o Tailwind detecte as classes durante o build.
    // bg-amber-50 border-amber-200 text-amber-900 hover:bg-amber-100 dark:bg-amber-900/20 dark:border-amber-700/60 dark:text-amber-100 dark:hover:bg-amber-900/30
    // bg-sky-50 border-sky-200 text-sky-900 hover:bg-sky-100 dark:bg-sky-900/20 dark:border-sky-700/60 dark:text-sky-100 dark:hover:bg-sky-900/30
    // bg-blue-50 border-blue-200 text-blue-900 hover:bg-blue-100 dark:bg-blue-900/20 dark:border-blue-700/60 dark:text-blue-100 dark:hover:bg-blue-900/30
    // bg-emerald-50 border-emerald-200 text-emerald-900 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:border-emerald-700/60 dark:text-emerald-100 dark:hover:bg-emerald-900/30
    // bg-rose-50 border-rose-200 text-rose-900 hover:bg-rose-100 dark:bg-rose-900/20 dark:border-rose-700/60 dark:text-rose-100 dark:hover:bg-rose-900/30
    // ring-2 ring-amber-500 ring-sky-500 ring-blue-500 ring-emerald-500 ring-rose-500 ring-offset-2 ring-offset-white dark:ring-offset-gray-900
    // bg-amber-100 bg-sky-100 bg-blue-100 bg-emerald-100 bg-rose-100
    // dark:bg-amber-900/40 dark:bg-sky-900/40 dark:bg-blue-900/40 dark:bg-emerald-900/40 dark:bg-rose-900/40

@endphp

<x-layouts.cardif>
    <x-slot:header>Vistorias</x-slot:header>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Vistorias</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Controle e agendamento de vistorias de seguros Cardif.
                </p>
            </div>
            <a href="{{ route('cardif.inspections.create') }}">
                <x-spire::button>+ Nova Vistoria</x-spire::button>
            </a>
        </div>

        @if (session('success'))
            <x-spire::alert type="success">{{ session('success') }}</x-spire::alert>
        @endif

        {{-- Resumo por status --}}
        <div class="grid gap-3 grid-cols-2 md:grid-cols-4 lg:grid-cols-8">
            @foreach (InspectionStatus::cases() as $st)
                @php
                    $inactive = $st->cardInactiveClasses();
                    $active = $st->cardActiveClasses();
                    $isActive = request('status') === $st->value;
                @endphp
                <a href="{{ route('cardif.inspections.index', ['status' => $st->value]) }}"
                    data-status-filter="{{ $st->value }}" data-active-class="{{ $active }}"
                    data-inactive-class="{{ $inactive }}"
                    class="block p-3 rounded-lg border text-center transition-all cursor-pointer {{ $inactive }} {{ $isActive ? $active : '' }}">
                    <div class="text-xs font-medium uppercase tracking-wide opacity-80 leading-tight">
                        {{ $st->label() }}
                    </div>
                    <div class="text-2xl font-bold mt-1">
                        {{ $totals[$st->value] ?? 0 }}
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Filtros --}}
        <x-spire::card>
            <form id="inspections-filters" method="GET" class="grid gap-3 md:grid-cols-4">
                <x-spire::input name="search" placeholder="Sinistro ou cliente" :value="request('search')" />
                <select name="status"
                    class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                    <option value="">— Todos status —</option>
                    @foreach ($statuses as $s)
                        <option value="{{ $s['value'] }}" @selected(request('status') === $s['value'])>
                            {{ $s['label'] }}</option>
                    @endforeach
                </select>
                <select name="assigned_to_user_id"
                    class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                    <option value="">— Todos analistas —</option>
                    @foreach ($analysts as $a)
                        <option value="{{ $a['value'] }}" @selected((int) request('assigned_to_user_id') === (int) $a['value'])>
                            {{ $a['label'] }}</option>
                    @endforeach
                </select>
                <input type="date" name="inspection_date" value="{{ request('inspection_date') }}"
                    class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                <div class="md:col-span-4 flex justify-end gap-2">
                    <a href="{{ route('cardif.inspections.index') }}" data-clear-filters
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50">
                        Limpar
                    </a>
                    <x-spire::button type="submit">Filtrar</x-spire::button>
                </div>
            </form>
        </x-spire::card>

        {{-- Tabela --}}
        <x-spire::card>
            <div id="inspections-table" data-url="{{ route('cardif.inspections.index') }}">
                @include('cardif.inspections.partials.table', ['inspections' => $inspections])
            </div>
        </x-spire::card>
    </div>

    @push('scripts')
        <script>
            (function() {
                const container = document.getElementById('inspections-table');
                if (!container) return;
                const baseUrl = container.dataset.url;
                const filtersForm = document.getElementById('inspections-filters');

                function buildUrl(extraParams = {}) {
                    const params = new URLSearchParams();
                    if (filtersForm) {
                        new FormData(filtersForm).forEach((v, k) => {
                            if (v !== '' && v !== null) params.set(k, v);
                        });
                    }
                    Object.entries(extraParams).forEach(([k, v]) => {
                        if (v === null || v === '') params.delete(k);
                        else params.set(k, v);
                    });
                    const qs = params.toString();
                    return qs ? `${baseUrl}?${qs}` : baseUrl;
                }

                async function load(url, {
                    push = true
                } = {}) {
                    container.classList.add('opacity-50', 'pointer-events-none');
                    try {
                        const res = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html',
                            },
                        });
                        const html = await res.text();
                        container.innerHTML = html;
                        if (push) history.pushState({
                            ajax: true
                        }, '', url);
                    } finally {
                        container.classList.remove('opacity-50', 'pointer-events-none');
                    }
                }

                // Pagination links (delegated)
                container.addEventListener('click', (e) => {
                    const a = e.target.closest('a[href]');
                    if (!a) return;
                    const url = new URL(a.href, window.location.origin);
                    if (url.pathname !== new URL(baseUrl, window.location.origin).pathname) return;
                    e.preventDefault();
                    load(url.toString());
                });

                // Filter form submit (AJAX)
                if (filtersForm) {
                    filtersForm.addEventListener('submit', (e) => {
                        e.preventDefault();
                        load(buildUrl());
                    });

                    // Auto-filter on change
                    let searchTimer = null;
                    filtersForm.querySelectorAll('input, select').forEach((el) => {
                        const isSearch = el.tagName === 'INPUT' && el.type !== 'date';
                        if (isSearch) {
                            el.addEventListener('input', () => {
                                clearTimeout(searchTimer);
                                const v = (el.value || '').trim();
                                if (v.length > 0 && v.length < 2) return;
                                searchTimer = setTimeout(() => load(buildUrl()), 350);
                            });
                        } else {
                            el.addEventListener('change', () => load(buildUrl()));
                        }
                    });
                }

                // Status summary cards (data-status filter)
                function updateStatusCards(activeStatus) {
                    document.querySelectorAll('[data-status-filter]').forEach((el) => {
                        const isActive = el.dataset.statusFilter === activeStatus;
                        const activeCls = (el.dataset.activeClass || '').split(/\s+/).filter(Boolean);
                        const inactiveCls = (el.dataset.inactiveClass || '').split(/\s+/).filter(Boolean);
                        el.classList.remove(...activeCls, ...inactiveCls);
                        el.classList.add(...(isActive ? activeCls : inactiveCls));
                    });
                }

                document.querySelectorAll('[data-status-filter]').forEach((el) => {
                    el.addEventListener('click', (e) => {
                        e.preventDefault();
                        const current = el.dataset.statusFilter || '';
                        const statusInput = filtersForm?.querySelector('[name="status"]');
                        // Toggle: clicking the active card clears the filter
                        const isAlreadyActive = el.classList.contains((el.dataset.activeClass || '').split(
                            /\s+/)[0]);
                        const next = isAlreadyActive ? '' : current;
                        if (statusInput) statusInput.value = next;
                        updateStatusCards(next);
                        load(buildUrl({
                            status: next
                        }));
                    });
                });

                // Clear filters link (AJAX)
                document.querySelectorAll('[data-clear-filters]').forEach((el) => {
                    el.addEventListener('click', (e) => {
                        e.preventDefault();
                        if (filtersForm) filtersForm.reset();
                        updateStatusCards('');
                        load(el.getAttribute('href') || baseUrl);
                    });
                });

                // Browser back/forward
                window.addEventListener('popstate', () => {
                    load(window.location.href, {
                        push: false
                    });
                });
            })();
        </script>
    @endpush
</x-layouts.cardif>
