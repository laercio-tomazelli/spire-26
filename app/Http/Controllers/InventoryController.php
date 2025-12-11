<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Part;
use App\Models\Warehouse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class InventoryController extends Controller
{
    /**
     * Display a listing of inventory items.
     */
    public function index(Request $request): View|string
    {
        Gate::authorize('viewAny', Warehouse::class);

        $items = $this->getFilteredItems($request);

        // Contagem para as tabs
        $counts = [
            'all' => InventoryItem::count(),
            'available' => InventoryItem::where('available_quantity', '>', 0)->count(),
            'reserved' => InventoryItem::where('reserved_quantity', '>', 0)->count(),
            'defective' => InventoryItem::where('defective_quantity', '>', 0)->count(),
            'empty' => InventoryItem::where('available_quantity', '<=', 0)
                ->where('reserved_quantity', '<=', 0)
                ->where('defective_quantity', '<=', 0)
                ->count(),
        ];

        // Contagem de filtros ativos
        $activeFiltersCount = collect([
            $request->filled('warehouse_id'),
            $request->filled('part_id'),
        ])->filter()->count();

        // Se for requisição AJAX, retorna apenas o partial da tabela
        if ($request->ajax()) {
            return view('inventory.partials.table-filament', [
                'items' => $items,
            ])->render();
        }

        // Dados para os filtros
        $warehouses = Warehouse::orderBy('name')->get(['id', 'name', 'code']);
        $parts = Part::orderBy('description')->get(['id', 'description', 'part_code']);

        return view('inventory.index', [
            'items' => $items,
            'counts' => $counts,
            'activeFiltersCount' => $activeFiltersCount,
            'warehouses' => $warehouses,
            'parts' => $parts,
        ]);
    }

    /**
     * Get filtered inventory items.
     */
    private function getFilteredItems(Request $request): LengthAwarePaginator
    {
        $query = InventoryItem::query()
            ->with(['warehouse', 'part']);

        // Ordenação
        $sortField = $request->input('sort', 'part_code');
        $sortDirection = $request->input('direction', 'asc');

        // Validar campos permitidos para ordenação
        $allowedSortFields = ['part_code', 'available_quantity', 'reserved_quantity', 'defective_quantity', 'updated_at'];
        if (in_array($sortField, $allowedSortFields, true)) {
            $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('part_code');
        }

        // Filtro por busca
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('part_code', 'like', "%{$search}%")
                    ->orWhereHas('part', function ($pq) use ($search): void {
                        $pq->where('description', 'like', "%{$search}%");
                    });
            });
        }

        // Filtro por status
        if ($request->filled('status')) {
            switch ($request->input('status')) {
                case 'available':
                    $query->where('available_quantity', '>', 0);
                    break;
                case 'reserved':
                    $query->where('reserved_quantity', '>', 0);
                    break;
                case 'defective':
                    $query->where('defective_quantity', '>', 0);
                    break;
                case 'empty':
                    $query->where('available_quantity', '<=', 0)
                        ->where('reserved_quantity', '<=', 0)
                        ->where('defective_quantity', '<=', 0);
                    break;
            }
        }

        // Filtro por depósito
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->input('warehouse_id'));
        }

        // Filtro por peça
        if ($request->filled('part_id')) {
            $query->where('part_id', $request->input('part_id'));
        }

        $perPage = $request->integer('per_page', 15);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Display the specified inventory item.
     */
    public function show(InventoryItem $inventoryItem): View
    {
        Gate::authorize('view', $inventoryItem->warehouse);

        $inventoryItem->load(['warehouse', 'part', 'transactions' => function ($query): void {
            $query->latest()->limit(20);
        }]);

        return view('inventory.show', [
            'item' => $inventoryItem,
        ]);
    }
}
