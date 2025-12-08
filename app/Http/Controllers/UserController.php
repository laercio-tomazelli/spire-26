<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Manufacturer;
use App\Models\Partner;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|string
    {
        Gate::authorize('viewAny', User::class);

        $users = $this->getFilteredUsers($request);

        // Se for requisição AJAX, retorna apenas o partial da tabela
        if ($request->ajax()) {
            return view('users.partials.table', [
                'users' => $users,
            ])->render();
        }

        return view('users.index', [
            'users' => $users,
            'userTypes' => UserType::selectOptions(),
        ]);
    }

    /**
     * Get filtered users query.
     */
    private function getFilteredUsers(Request $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = User::query()
            ->with(['partner', 'manufacturer', 'tenant'])
            ->orderBy('name');

        // Filtro por tipo de usuário
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->input('user_type'));
        }

        // Filtro por status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Busca por nome ou email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Scope por tipo de usuário logado
        $user = $request->user();
        if ($user instanceof User) {
            // Partner admin só vê usuários do mesmo partner
            if ($user->isPartnerAdmin()) {
                $query->where('partner_id', $user->partner_id);
            }
            // Manufacturer só vê usuários do mesmo manufacturer
            elseif ($user->isManufacturer()) {
                $query->where('manufacturer_id', $user->manufacturer_id);
            }
        }

        return $query->paginate(15)->withQueryString();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', User::class);

        $user = $request->user();

        return view('users.create', [
            'userTypes' => $this->getAvailableUserTypes($user),
            'partners' => $this->getAvailablePartners($user),
            'manufacturers' => $this->getAvailableManufacturers($user),
            'tenants' => $this->getAvailableTenants($user),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $validated = $request->validated();

        // Set created_by_user_id
        $validated['created_by_user_id'] = $request->user()?->id;

        // Hash password is handled by model cast
        $user = User::create($validated);

        return to_route('users.show', $user)
            ->with('success', 'Usuário criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        Gate::authorize('view', $user);

        $user->load(['partner', 'manufacturer', 'tenant', 'createdByUser', 'roles']);

        return view('users.show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, User $user): View
    {
        Gate::authorize('update', $user);

        $authUser = $request->user();

        return view('users.edit', [
            'user' => $user,
            'userTypes' => $this->getAvailableUserTypes($authUser),
            'partners' => $this->getAvailablePartners($authUser),
            'manufacturers' => $this->getAvailableManufacturers($authUser),
            'tenants' => $this->getAvailableTenants($authUser),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $validated = $request->validated();

        // Remove password if empty (keep existing)
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return to_route('users.show', $user)
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return to_route('users.index')
            ->with('success', 'Usuário excluído com sucesso.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleActive(User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'ativado' : 'desativado';

        return back()->with('success', "Usuário {$status} com sucesso.");
    }

    /**
     * Get available user types based on authenticated user.
     *
     * @return array<int, array{value: string, label: string, icon: string, color: string}>
     */
    private function getAvailableUserTypes(?User $user): array
    {
        $types = [];

        // Spire can create all types
        if ($user?->isSpire()) {
            $types = UserType::selectOptions();
        }
        // Partner admin can only create Partner users
        elseif ($user?->isPartnerAdmin()) {
            $types[] = UserType::Partner->toSelectOption();
        }
        // Manufacturer can create Manufacturer users
        elseif ($user?->isManufacturer()) {
            $types[] = UserType::Manufacturer->toSelectOption();
        }

        return $types;
    }

    /**
     * Get available partners for user creation.
     *
     * @return Collection<int, Partner>
     */
    private function getAvailablePartners(?User $user): Collection
    {
        if ($user?->isSpire()) {
            return Partner::query()->orderBy('trade_name')->get(['id', 'trade_name', 'code']);
        }

        if ($user?->isPartnerAdmin() && $user->partner) {
            return Partner::query()
                ->where('id', $user->partner_id)
                ->get(['id', 'trade_name', 'code']);
        }

        return Partner::query()->whereRaw('1 = 0')->get();
    }

    /**
     * Get available manufacturers for user creation.
     *
     * @return Collection<int, Manufacturer>
     */
    private function getAvailableManufacturers(?User $user): Collection
    {
        if ($user?->isSpire()) {
            return Manufacturer::query()->orderBy('name')->get(['id', 'name']);
        }

        if ($user?->isManufacturer() && $user->manufacturer) {
            return Manufacturer::query()
                ->where('id', $user->manufacturer_id)
                ->get(['id', 'name']);
        }

        return Manufacturer::query()->whereRaw('1 = 0')->get();
    }

    /**
     * Get available tenants for user creation.
     *
     * @return Collection<int, Tenant>
     */
    private function getAvailableTenants(?User $user): Collection
    {
        if ($user?->isSpire()) {
            return Tenant::query()->orderBy('name')->get(['id', 'name']);
        }

        return Tenant::query()->whereRaw('1 = 0')->get();
    }
}
