<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PartStoreRequest;
use App\Http\Requests\PartUpdateRequest;
use App\Models\Part;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|string
    {
        Gate::authorize('viewAny', Part::class);

        $parts = $this->getFilteredParts($request);

        // Contagem por status para as tabs
        $counts = [
            'all' => Part::count(),
            'active' => Part::where('is_active', true)->count(),
            'inactive' => Part::where('is_active', false)->count(),
        ];

        // Contagem de filtros ativos
        $activeFiltersCount = collect([
            $request->filled('unit'),
            $request->filled('origin'),
        ])->filter()->count();

        // Se for requisição AJAX, retorna apenas o partial da tabela
        if ($request->ajax()) {
            return view('parts.partials.table-filament', [
                'parts' => $parts,
            ])->render();
        }

        return view('parts.index', [
            'parts' => $parts,
            'counts' => $counts,
            'activeFiltersCount' => $activeFiltersCount,
        ]);
    }

    /**
     * Get filtered parts query.
     */
    private function getFilteredParts(Request $request): LengthAwarePaginator
    {
        $query = Part::query();

        // Ordenação
        $sortField = $request->input('sort', 'description');
        $sortDirection = $request->input('direction', 'asc');

        // Validar campos permitidos para ordenação
        $allowedSortFields = ['description', 'part_code', 'unit', 'price', 'is_active', 'created_at'];
        if (in_array($sortField, $allowedSortFields, true)) {
            $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('description');
        }

        // Filtro por busca
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('part_code', 'like', "%{$search}%")
                    ->orWhere('ean', 'like', "%{$search}%")
                    ->orWhere('manufacturer_code', 'like', "%{$search}%");
            });
        }

        // Filtro por status (tabs)
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filtro por unidade
        if ($request->filled('unit')) {
            $query->where('unit', $request->input('unit'));
        }

        // Filtro por origem
        if ($request->filled('origin')) {
            $query->where('origin', $request->input('origin'));
        }

        return $query->paginate(15)->withQueryString();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Part::class);

        return view('parts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PartStoreRequest $request): RedirectResponse
    {
        Gate::authorize('create', Part::class);

        $data = $request->validated();
        $data['tenant_id'] = Auth::user()->tenant_id;
        $data['is_active'] = $request->boolean('is_active');
        $data['is_display'] = $request->boolean('is_display');

        $part = Part::create($data);

        return to_route('parts.show', $part)
            ->with('success', 'Peça criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Part $part): View
    {
        Gate::authorize('view', $part);

        $part->load(['productModels.brand', 'productModels.category']);

        return view('parts.show', ['part' => $part]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Part $part): View
    {
        Gate::authorize('update', $part);

        return view('parts.edit', ['part' => $part]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PartUpdateRequest $request, Part $part): RedirectResponse
    {
        Gate::authorize('update', $part);

        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['is_display'] = $request->boolean('is_display');

        $part->update($data);

        return to_route('parts.show', $part)
            ->with('success', 'Peça atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Part $part): RedirectResponse
    {
        Gate::authorize('delete', $part);

        $part->delete();

        return to_route('parts.index')
            ->with('success', 'Peça excluída com sucesso.');
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Part $part): RedirectResponse
    {
        Gate::authorize('update', $part);

        $part->update(['is_active' => ! $part->is_active]);

        $status = $part->is_active ? 'ativada' : 'desativada';

        return back()
            ->with('success', "Peça {$status} com sucesso.");
    }
}
