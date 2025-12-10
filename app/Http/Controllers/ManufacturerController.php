<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ManufacturerStoreRequest;
use App\Http\Requests\ManufacturerUpdateRequest;
use App\Models\Manufacturer;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|string
    {
        Gate::authorize('viewAny', Manufacturer::class);

        $manufacturers = $this->getFilteredManufacturers($request);

        // Contagem por status para as tabs
        $counts = [
            'all' => Manufacturer::count(),
            'active' => Manufacturer::where('is_active', true)->count(),
            'inactive' => Manufacturer::where('is_active', false)->count(),
        ];

        // Contagem de filtros ativos
        $activeFiltersCount = collect([
            $request->filled('tenant_id'),
            $request->filled('is_active'),
        ])->filter()->count();

        // Se for requisição AJAX, retorna apenas o partial da tabela
        if ($request->ajax()) {
            return view('manufacturers.partials.table', [
                'manufacturers' => $manufacturers,
            ])->render();
        }

        return view('manufacturers.index', [
            'manufacturers' => $manufacturers,
            'counts' => $counts,
            'activeFiltersCount' => $activeFiltersCount,
            'tenants' => $this->getAvailableTenants($request->user()),
        ]);
    }

    /**
     * Get filtered manufacturers query.
     */
    private function getFilteredManufacturers(Request $request): LengthAwarePaginator
    {
        $query = Manufacturer::query()
            ->with(['tenant'])
            ->withCount(['brands']);

        // Ordenação
        $sortField = $request->input('sort', 'name');
        $sortDirection = $request->input('direction', 'asc');

        // Validar campos permitidos para ordenação
        $allowedSortFields = ['name', 'document', 'email', 'is_active', 'created_at'];
        if (in_array($sortField, $allowedSortFields, true)) {
            $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('name');
        }

        // Filtro por tenant
        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->input('tenant_id'));
        }

        // Filtro por status (tabs usam "status", formulários usam "is_active")
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        } elseif ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Busca por nome, documento ou email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('document', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Scope por tipo de usuário logado
        $user = $request->user();
        // Manufacturer só vê o próprio
        if ($user instanceof User && $user->isManufacturer()) {
            $query->where('id', $user->manufacturer_id);
        }

        $perPage = $request->integer('per_page', 10);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', Manufacturer::class);

        return view('manufacturers.create', [
            'tenants' => $this->getAvailableTenants($request->user()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ManufacturerStoreRequest $request): RedirectResponse
    {
        Gate::authorize('create', Manufacturer::class);

        $validated = $request->validated();

        $manufacturer = Manufacturer::create($validated);

        return to_route('manufacturers.show', $manufacturer)
            ->with('success', 'Fabricante criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Manufacturer $manufacturer): View
    {
        Gate::authorize('view', $manufacturer);

        $manufacturer->load(['tenant', 'brands']);
        $manufacturer->loadCount(['brands']);

        return view('manufacturers.show', [
            'manufacturer' => $manufacturer,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Manufacturer $manufacturer): View
    {
        Gate::authorize('update', $manufacturer);

        return view('manufacturers.edit', [
            'manufacturer' => $manufacturer,
            'tenants' => $this->getAvailableTenants($request->user()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ManufacturerUpdateRequest $request, Manufacturer $manufacturer): RedirectResponse
    {
        Gate::authorize('update', $manufacturer);

        $validated = $request->validated();

        $manufacturer->update($validated);

        return to_route('manufacturers.show', $manufacturer)
            ->with('success', 'Fabricante atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Manufacturer $manufacturer): RedirectResponse
    {
        Gate::authorize('delete', $manufacturer);

        // Verificar se tem marcas vinculadas
        if ($manufacturer->brands()->exists()) {
            return back()->with('error', 'Não é possível excluir um fabricante com marcas vinculadas.');
        }

        $manufacturer->delete();

        return to_route('manufacturers.index')
            ->with('success', 'Fabricante excluído com sucesso.');
    }

    /**
     * Toggle manufacturer active status.
     */
    public function toggleActive(Manufacturer $manufacturer): RedirectResponse
    {
        Gate::authorize('update', $manufacturer);

        $manufacturer->update(['is_active' => ! $manufacturer->is_active]);

        $status = $manufacturer->is_active ? 'ativado' : 'desativado';

        return back()->with('success', "Fabricante {$status} com sucesso.");
    }

    /**
     * Get available tenants for manufacturer creation.
     *
     * @return Collection<int, Tenant>
     */
    private function getAvailableTenants(?User $user): Collection
    {
        if ($user?->isSpire()) {
            return Tenant::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        return Tenant::query()->whereRaw('1 = 0')->get();
    }
}
