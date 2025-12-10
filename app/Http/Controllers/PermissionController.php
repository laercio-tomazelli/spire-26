<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PermissionStoreRequest;
use App\Http\Requests\PermissionUpdateRequest;
use App\Models\Permission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|string
    {
        Gate::authorize('viewAny', Permission::class);

        $permissions = $this->getFilteredPermissions($request);

        // Grupos únicos para o filtro
        $groups = Permission::distinct()->pluck('group')->filter()->sort()->values();

        // Contagem por grupo para as tabs
        $counts = [
            'all' => Permission::count(),
        ];

        // Se for requisição AJAX, retorna apenas o partial da tabela
        if ($request->ajax()) {
            return view('permissions.partials.table-filament', [
                'permissions' => $permissions,
            ])->render();
        }

        return view('permissions.index', [
            'permissions' => $permissions,
            'groups' => $groups,
            'counts' => $counts,
        ]);
    }

    /**
     * Get filtered permissions query.
     */
    private function getFilteredPermissions(Request $request): LengthAwarePaginator
    {
        $query = Permission::query()
            ->withCount(['roles', 'users']);

        // Ordenação
        $sortField = $request->input('sort', 'group');
        $sortDirection = $request->input('direction', 'asc');

        // Validar campos permitidos para ordenação
        $allowedSortFields = ['name', 'slug', 'group', 'created_at'];
        if (in_array($sortField, $allowedSortFields, true)) {
            $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
            // Ordenação secundária por nome
            if ($sortField !== 'name') {
                $query->orderBy('name');
            }
        } else {
            $query->orderBy('group')->orderBy('name');
        }

        // Filtro por grupo
        if ($request->filled('group')) {
            $query->where('group', $request->input('group'));
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

        $perPage = $request->integer('per_page', 25);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Permission::class);

        $groups = Permission::distinct()->pluck('group')->filter()->sort()->values();

        return view('permissions.create', [
            'groups' => $groups,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionStoreRequest $request): RedirectResponse
    {
        Gate::authorize('create', Permission::class);

        $validated = $request->validated();

        // Gerar slug a partir do grupo e nome
        $slugBase = $validated['group'] ? "{$validated['group']}.{$validated['name']}" : $validated['name'];
        $validated['slug'] = Str::slug($slugBase, '.');

        $permission = Permission::create($validated);

        return to_route('permissions.show', $permission)
            ->with('success', 'Permissão criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission): View
    {
        Gate::authorize('view', $permission);

        $permission->load(['roles', 'users']);
        $permission->loadCount(['roles', 'users']);

        return view('permissions.show', [
            'permission' => $permission,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission): View
    {
        Gate::authorize('update', $permission);

        $groups = Permission::distinct()->pluck('group')->filter()->sort()->values();

        return view('permissions.edit', [
            'permission' => $permission,
            'groups' => $groups,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionUpdateRequest $request, Permission $permission): RedirectResponse
    {
        Gate::authorize('update', $permission);

        $validated = $request->validated();

        // Atualizar slug se o grupo ou nome mudou
        if (
            (isset($validated['name']) && $validated['name'] !== $permission->name) ||
            (isset($validated['group']) && $validated['group'] !== $permission->group)
        ) {
            $group = $validated['group'] ?? $permission->group;
            $name = $validated['name'] ?? $permission->name;
            $slugBase = $group ? "{$group}.{$name}" : $name;
            $validated['slug'] = Str::slug($slugBase, '.');
        }

        $permission->update($validated);

        return to_route('permissions.show', $permission)
            ->with('success', 'Permissão atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        Gate::authorize('delete', $permission);

        // Verificar se tem roles vinculados
        if ($permission->roles()->exists()) {
            return back()->with('error', 'Não é possível excluir uma permissão vinculada a perfis.');
        }

        // Verificar se tem usuários com permissão direta
        if ($permission->users()->exists()) {
            return back()->with('error', 'Não é possível excluir uma permissão vinculada a usuários.');
        }

        $permission->delete();

        return to_route('permissions.index')
            ->with('success', 'Permissão excluída com sucesso.');
    }
}
