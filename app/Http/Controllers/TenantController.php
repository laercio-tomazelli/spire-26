<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TenantStoreRequest;
use App\Http\Requests\TenantUpdateRequest;
use App\Models\Tenant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|string
    {
        Gate::authorize('viewAny', Tenant::class);

        $tenants = $this->getFilteredTenants($request);

        // Contagem por status para as tabs
        $counts = [
            'all' => Tenant::count(),
            'active' => Tenant::where('is_active', true)->count(),
            'inactive' => Tenant::where('is_active', false)->count(),
        ];

        // Contagem de filtros ativos
        $activeFiltersCount = collect([
            $request->filled('is_active'),
        ])->filter()->count();

        // Se for requisição AJAX, retorna apenas o partial da tabela
        if ($request->ajax()) {
            return view('tenants.partials.table', [
                'tenants' => $tenants,
            ])->render();
        }

        return view('tenants.index', [
            'tenants' => $tenants,
            'counts' => $counts,
            'activeFiltersCount' => $activeFiltersCount,
        ]);
    }

    /**
     * Get filtered tenants query.
     */
    private function getFilteredTenants(Request $request): LengthAwarePaginator
    {
        $query = Tenant::query()
            ->withCount(['users', 'manufacturers']);

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

        $perPage = $request->integer('per_page', 10);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Tenant::class);

        return view('tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TenantStoreRequest $request): RedirectResponse
    {
        Gate::authorize('create', Tenant::class);

        $validated = $request->validated();

        $tenant = Tenant::create($validated);

        return to_route('tenants.show', $tenant)
            ->with('success', 'Tenant criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant): View
    {
        Gate::authorize('view', $tenant);

        $tenant->loadCount(['users', 'manufacturers', 'partners']);

        return view('tenants.show', [
            'tenant' => $tenant,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant): View
    {
        Gate::authorize('update', $tenant);

        return view('tenants.edit', [
            'tenant' => $tenant,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TenantUpdateRequest $request, Tenant $tenant): RedirectResponse
    {
        Gate::authorize('update', $tenant);

        $validated = $request->validated();

        $tenant->update($validated);

        return to_route('tenants.show', $tenant)
            ->with('success', 'Tenant atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant): RedirectResponse
    {
        Gate::authorize('delete', $tenant);

        // Verificar se tem usuários vinculados
        if ($tenant->users()->exists()) {
            return back()->with('error', 'Não é possível excluir um tenant com usuários vinculados.');
        }

        $tenant->delete();

        return to_route('tenants.index')
            ->with('success', 'Tenant excluído com sucesso.');
    }

    /**
     * Toggle tenant active status.
     */
    public function toggleActive(Tenant $tenant): RedirectResponse
    {
        Gate::authorize('update', $tenant);

        $tenant->update(['is_active' => ! $tenant->is_active]);

        $status = $tenant->is_active ? 'ativado' : 'desativado';

        return back()->with('success', "Tenant {$status} com sucesso.");
    }
}
