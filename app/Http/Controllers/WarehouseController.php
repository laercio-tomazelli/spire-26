<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Partner;
use App\Models\Warehouse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|string
    {
        Gate::authorize('viewAny', Warehouse::class);

        $warehouses = $this->getFilteredWarehouses($request);

        // Contagem por tipo para as tabs
        $counts = [
            'all' => Warehouse::count(),
            'main' => Warehouse::where('type', 'main')->count(),
            'partner' => Warehouse::where('type', 'partner')->count(),
            'buffer' => Warehouse::where('type', 'buffer')->count(),
            'defective' => Warehouse::where('type', 'defective')->count(),
        ];

        // Contagem de filtros ativos
        $activeFiltersCount = collect([
            $request->filled('partner_id'),
        ])->filter()->count();

        // Se for requisição AJAX, retorna apenas o partial da tabela
        if ($request->ajax()) {
            return view('warehouses.partials.table-filament', [
                'warehouses' => $warehouses,
            ])->render();
        }

        // Lista de parceiros para o filtro
        $partners = Partner::orderBy('trade_name')->get(['id', 'trade_name']);

        return view('warehouses.index', [
            'warehouses' => $warehouses,
            'counts' => $counts,
            'activeFiltersCount' => $activeFiltersCount,
            'partners' => $partners,
        ]);
    }

    /**
     * Get filtered warehouses query.
     */
    private function getFilteredWarehouses(Request $request): LengthAwarePaginator
    {
        $query = Warehouse::query()->with(['partner', 'tenant']);

        // Ordenação
        $sortField = $request->input('sort', 'name');
        $sortDirection = $request->input('direction', 'asc');

        // Validar campos permitidos para ordenação
        $allowedSortFields = ['name', 'code', 'type', 'location', 'created_at'];
        if (in_array($sortField, $allowedSortFields, true)) {
            $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('name');
        }

        // Filtro por busca
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filtro por tipo
        if ($request->filled('type') && $request->input('type') !== 'all') {
            $query->where('type', $request->input('type'));
        }

        // Filtro por parceiro
        if ($request->filled('partner_id')) {
            $query->where('partner_id', $request->input('partner_id'));
        }

        $perPage = $request->integer('per_page', 15);

        $paginator = $query->paginate($perPage)->withQueryString();

        // Add inventory items count without global scopes (InventoryItem has BelongsToTenant but no tenant_id column)
        $warehouseIds = $paginator->pluck('id')->toArray();
        if (! empty($warehouseIds)) {
            $itemCounts = InventoryItem::withoutGlobalScopes()
                ->selectRaw('warehouse_id, COUNT(*) as count')
                ->whereIn('warehouse_id', $warehouseIds)
                ->groupBy('warehouse_id')
                ->pluck('count', 'warehouse_id');

            $paginator->getCollection()->transform(function ($warehouse) use ($itemCounts) {
                $warehouse->inventory_items_count = $itemCounts[$warehouse->id] ?? 0;

                return $warehouse;
            });
        }

        return $paginator;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Warehouse::class);

        $partners = Partner::orderBy('trade_name')->get(['id', 'trade_name']);

        return view('warehouses.create', [
            'partners' => $partners,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Warehouse::class);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:main,partner,buffer,defective'],
            'partner_id' => ['nullable', 'exists:partners,id'],
        ]);

        $validated['tenant_id'] = Auth::user()->tenant_id;

        $warehouse = Warehouse::create($validated);

        return to_route('warehouses.show', $warehouse)
            ->with('success', 'Depósito criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse): View
    {
        Gate::authorize('view', $warehouse);

        $warehouse->load(['partner', 'tenant']);

        // Load inventory items without global scopes (InventoryItem has BelongsToTenant but no tenant_id column)
        $inventoryItems = InventoryItem::withoutGlobalScopes()
            ->where('warehouse_id', $warehouse->id)
            ->with('part')
            ->get();

        // Estatísticas do depósito
        $stats = [
            'total_items' => $inventoryItems->count(),
            'total_quantity' => $inventoryItems->sum('available_quantity'),
            'reserved_quantity' => $inventoryItems->sum('reserved_quantity'),
            'defective_quantity' => $inventoryItems->sum('defective_quantity'),
        ];

        return view('warehouses.show', [
            'warehouse' => $warehouse,
            'inventoryItems' => $inventoryItems,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse): View
    {
        Gate::authorize('update', $warehouse);

        $partners = Partner::orderBy('trade_name')->get(['id', 'trade_name']);

        return view('warehouses.edit', [
            'warehouse' => $warehouse,
            'partners' => $partners,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        Gate::authorize('update', $warehouse);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:main,partner,buffer,defective'],
            'partner_id' => ['nullable', 'exists:partners,id'],
        ]);

        $warehouse->update($validated);

        return to_route('warehouses.show', $warehouse)
            ->with('success', 'Depósito atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        Gate::authorize('delete', $warehouse);

        // Verificar se há itens no estoque
        if ($warehouse->inventoryItems()->exists()) {
            return back()->with('error', 'Não é possível excluir um depósito que possui itens em estoque.');
        }

        $warehouse->delete();

        return to_route('warehouses.index')
            ->with('success', 'Depósito excluído com sucesso.');
    }
}
