<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|string
    {
        Gate::authorize('viewAny', Role::class);

        $roles = $this->getFilteredRoles($request);

        // Contagem por status para as tabs
        $counts = [
            'all' => Role::count(),
            'system' => Role::where('is_system', true)->count(),
            'custom' => Role::where('is_system', false)->count(),
        ];

        // Se for requisição AJAX, retorna apenas o partial da tabela
        if ($request->ajax()) {
            return view('roles.partials.table', [
                'roles' => $roles,
            ])->render();
        }

        return view('roles.index', [
            'roles' => $roles,
            'counts' => $counts,
        ]);
    }

    /**
     * Get filtered roles query.
     */
    private function getFilteredRoles(Request $request): LengthAwarePaginator
    {
        $query = Role::query()
            ->withCount(['permissions', 'users']);

        // Ordenação
        $sortField = $request->input('sort', 'name');
        $sortDirection = $request->input('direction', 'asc');

        // Validar campos permitidos para ordenação
        $allowedSortFields = ['name', 'slug', 'is_system', 'created_at'];
        if (in_array($sortField, $allowedSortFields, true)) {
            $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('name');
        }

        // Filtro por tipo (system/custom)
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'system') {
                $query->where('is_system', true);
            } elseif ($status === 'custom') {
                $query->where('is_system', false);
            }
        }

        // Busca por nome ou slug
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
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
        Gate::authorize('create', Role::class);

        $permissions = Permission::orderBy('group')->orderBy('name')->get();
        $permissionsByGroup = $permissions->groupBy('group');

        return view('roles.create', [
            'permissions' => $permissions,
            'permissionsByGroup' => $permissionsByGroup,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleStoreRequest $request): RedirectResponse
    {
        Gate::authorize('create', Role::class);

        $validated = $request->validated();

        // Gerar slug a partir do nome
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_system'] = false;

        $role = Role::create($validated);

        // Sincronizar permissões
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->input('permissions', []));
        }

        return to_route('roles.show', $role)
            ->with('success', 'Perfil criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): View
    {
        Gate::authorize('view', $role);

        $role->load(['permissions', 'users']);
        $role->loadCount(['permissions', 'users']);

        $permissionsByGroup = $role->permissions->groupBy('group');

        return view('roles.show', [
            'role' => $role,
            'permissionsByGroup' => $permissionsByGroup,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): View
    {
        Gate::authorize('update', $role);

        $permissions = Permission::orderBy('group')->orderBy('name')->get();
        $permissionsByGroup = $permissions->groupBy('group');
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();

        return view('roles.edit', [
            'role' => $role,
            'permissions' => $permissions,
            'permissionsByGroup' => $permissionsByGroup,
            'rolePermissionIds' => $rolePermissionIds,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUpdateRequest $request, Role $role): RedirectResponse
    {
        Gate::authorize('update', $role);

        // Não permitir editar roles de sistema
        if ($role->is_system) {
            return back()->with('error', 'Perfis de sistema não podem ser editados.');
        }

        $validated = $request->validated();

        // Atualizar slug se o nome mudou
        if (isset($validated['name']) && $validated['name'] !== $role->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $role->update($validated);

        // Sincronizar permissões
        $role->permissions()->sync($request->input('permissions', []));

        return to_route('roles.show', $role)
            ->with('success', 'Perfil atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        Gate::authorize('delete', $role);

        // Não permitir excluir roles de sistema
        if ($role->is_system) {
            return back()->with('error', 'Perfis de sistema não podem ser excluídos.');
        }

        // Verificar se tem usuários vinculados
        if ($role->users()->exists()) {
            return back()->with('error', 'Não é possível excluir um perfil com usuários vinculados.');
        }

        $role->delete();

        return to_route('roles.index')
            ->with('success', 'Perfil excluído com sucesso.');
    }
}
