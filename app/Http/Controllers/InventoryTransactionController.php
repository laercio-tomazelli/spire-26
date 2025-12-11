<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Part;
use App\Models\Warehouse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class InventoryTransactionController extends Controller
{
    /**
     * Display a listing of inventory transactions.
     */
    public function index(Request $request): View|string
    {
        Gate::authorize('viewAny', Warehouse::class);

        $transactions = $this->getFilteredTransactions($request);

        // Contagem para as tabs
        $counts = [
            'all' => InventoryTransaction::count(),
            'in' => InventoryTransaction::where('quantity', '>', 0)->count(),
            'out' => InventoryTransaction::where('quantity', '<', 0)->count(),
        ];

        // Contagem de filtros ativos
        $activeFiltersCount = collect([
            $request->filled('warehouse_id'),
            $request->filled('part_id'),
            $request->filled('date_from'),
            $request->filled('date_to'),
        ])->filter()->count();

        // Se for requisição AJAX, retorna apenas o partial da tabela
        if ($request->ajax()) {
            return view('inventory-transactions.partials.table-filament', [
                'transactions' => $transactions,
            ])->render();
        }

        // Dados para os filtros
        $warehouses = Warehouse::orderBy('name')->get(['id', 'name', 'code']);
        $parts = Part::orderBy('description')->limit(100)->get(['id', 'description', 'part_code']);

        return view('inventory-transactions.index', [
            'transactions' => $transactions,
            'counts' => $counts,
            'activeFiltersCount' => $activeFiltersCount,
            'warehouses' => $warehouses,
            'parts' => $parts,
        ]);
    }

    /**
     * Get filtered transactions.
     */
    private function getFilteredTransactions(Request $request): LengthAwarePaginator
    {
        $query = InventoryTransaction::query()
            ->with(['warehouse', 'part', 'user']);

        // Ordenação
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        // Validar campos permitidos para ordenação
        $allowedSortFields = ['part_code', 'quantity', 'created_at', 'document_number'];
        if (in_array($sortField, $allowedSortFields, true)) {
            $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Filtro por busca
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('part_code', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%")
                    ->orWhereHas('part', function ($pq) use ($search): void {
                        $pq->where('description', 'like', "%{$search}%");
                    });
            });
        }

        // Filtro por tipo (entrada/saída)
        if ($request->filled('type')) {
            switch ($request->input('type')) {
                case 'in':
                    $query->where('quantity', '>', 0);
                    break;
                case 'out':
                    $query->where('quantity', '<', 0);
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

        // Filtro por data
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $perPage = $request->integer('per_page', 15);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Display the specified transaction.
     */
    public function show(InventoryTransaction $inventoryTransaction): View
    {
        Gate::authorize('view', $inventoryTransaction->warehouse);

        $inventoryTransaction->load(['warehouse', 'part', 'user']);

        return view('inventory-transactions.show', [
            'transaction' => $inventoryTransaction,
        ]);
    }
}
