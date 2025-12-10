<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TeamStoreRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TeamController extends Controller
{
    /**
     * Display a listing of teams.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Team::class);

        $query = Team::query()
            ->withCount(['users', 'roles', 'permissions']);

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->input('status') !== '') {
            $isActive = $request->input('status') === 'active';
            $query->where('is_active', $isActive);
        }

        // Tenant filter (Spire users)
        if ($tenantId = $request->input('tenant_id')) {
            $query->where('tenant_id', $tenantId);
        }

        // Sorting
        $sortField = $request->input('sort', 'name');
        $sortDirection = $request->input('direction', 'asc');
        $allowedSorts = ['name', 'created_at', 'users_count'];

        if (in_array($sortField, $allowedSorts, true)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Pagination
        $perPage = (int) $request->input('per_page', 10);
        $teams = $query->paginate($perPage)->withQueryString();

        // Counts for tabs
        $counts = [
            'all' => Team::count(),
            'active' => Team::where('is_active', true)->count(),
            'inactive' => Team::where('is_active', false)->count(),
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return view('teams.partials.table-filament', ['teams' => $teams]);
        }

        return view('teams.index', ['teams' => $teams, 'counts' => $counts]);
    }

    /**
     * Show the form for creating a new team.
     */
    public function create(): View
    {
        Gate::authorize('create', Team::class);

        $roles = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('group')->orderBy('name')->get()->groupBy('group');
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('teams.create', ['roles' => $roles, 'permissions' => $permissions, 'users' => $users]);
    }

    /**
     * Store a newly created team.
     */
    public function store(TeamStoreRequest $request): RedirectResponse
    {
        Gate::authorize('create', Team::class);

        $validated = $request->validated();

        $team = Team::create([
            'tenant_id' => $validated['tenant_id'] ?? null,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Sync roles
        if (! empty($validated['roles'])) {
            $team->roles()->sync($validated['roles']);
        }

        // Sync permissions
        if (! empty($validated['permissions'])) {
            $permissionData = [];
            foreach ($validated['permissions'] as $permissionId) {
                $permissionData[$permissionId] = ['granted' => true];
            }
            $team->permissions()->sync($permissionData);
        }

        // Sync users
        if (! empty($validated['users'])) {
            $userData = [];
            foreach ($validated['users'] as $userId) {
                $isLeader = in_array($userId, $validated['leaders'] ?? [], true);
                $userData[$userId] = ['is_leader' => $isLeader];
            }
            $team->users()->sync($userData);
        }

        return to_route('teams.index')
            ->with('success', 'Time criado com sucesso.');
    }

    /**
     * Display the specified team.
     */
    public function show(Team $team): View
    {
        Gate::authorize('view', $team);

        $team->load(['users', 'roles.permissions', 'permissions', 'tenant']);

        return view('teams.show', ['team' => $team]);
    }

    /**
     * Show the form for editing the specified team.
     */
    public function edit(Team $team): View
    {
        Gate::authorize('update', $team);

        $team->load(['users', 'roles', 'permissions']);
        $roles = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('group')->orderBy('name')->get()->groupBy('group');
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('teams.edit', ['team' => $team, 'roles' => $roles, 'permissions' => $permissions, 'users' => $users]);
    }

    /**
     * Update the specified team.
     */
    public function update(TeamUpdateRequest $request, Team $team): RedirectResponse
    {
        Gate::authorize('update', $team);

        $validated = $request->validated();

        $team->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Sync roles
        $team->roles()->sync($validated['roles'] ?? []);

        // Sync permissions
        $permissionData = [];
        foreach ($validated['permissions'] ?? [] as $permissionId) {
            $permissionData[$permissionId] = ['granted' => true];
        }
        $team->permissions()->sync($permissionData);

        // Sync users with leader status
        $userData = [];
        foreach ($validated['users'] ?? [] as $userId) {
            $isLeader = in_array($userId, $validated['leaders'] ?? [], true);
            $userData[$userId] = ['is_leader' => $isLeader];
        }
        $team->users()->sync($userData);

        return to_route('teams.index')
            ->with('success', 'Time atualizado com sucesso.');
    }

    /**
     * Remove the specified team.
     */
    public function destroy(Team $team): RedirectResponse
    {
        Gate::authorize('delete', $team);

        $team->delete();

        return to_route('teams.index')
            ->with('success', 'Time excluído com sucesso.');
    }

    /**
     * Toggle team active status.
     */
    public function toggleActive(Team $team): RedirectResponse
    {
        Gate::authorize('update', $team);

        $team->update(['is_active' => ! $team->is_active]);

        $status = $team->is_active ? 'ativado' : 'desativado';

        return back()
            ->with('success', "Time {$status} com sucesso.");
    }
}
