<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|string
    {
        Gate::authorize('viewAny', Customer::class);

        $customers = $this->getFilteredCustomers($request);

        // Contagem por status para as tabs
        $counts = [
            'all' => Customer::forTenant($request->user()->tenant_id)->count(),
            'pf' => Customer::forTenant($request->user()->tenant_id)->where('customer_type', 'PF')->count(),
            'pj' => Customer::forTenant($request->user()->tenant_id)->where('customer_type', 'PJ')->count(),
        ];

        // Contagem de filtros ativos
        $activeFiltersCount = collect([
            $request->filled('customer_type'),
            $request->filled('state'),
            $request->filled('city'),
        ])->filter()->count();

        // Se for requisição AJAX, retorna apenas o partial da tabela
        if ($request->ajax()) {
            return view('customers.partials.table', [
                'customers' => $customers,
            ])->render();
        }

        return view('customers.index', [
            'customers' => $customers,
            'counts' => $counts,
            'activeFiltersCount' => $activeFiltersCount,
            'states' => $this->getBrazilianStates(),
        ]);
    }

    /**
     * Get filtered customers query.
     */
    private function getFilteredCustomers(Request $request): LengthAwarePaginator
    {
        $query = Customer::query()
            ->forTenant($request->user()->tenant_id);

        // Ordenação
        $sortField = $request->input('sort', 'name');
        $sortDirection = $request->input('direction', 'asc');

        // Validar campos permitidos para ordenação
        $allowedSortFields = ['name', 'document', 'email', 'city', 'state', 'created_at'];
        if (in_array($sortField, $allowedSortFields, true)) {
            $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('name');
        }

        // Filtro por tipo de cliente (tabs)
        if ($request->filled('type')) {
            $type = $request->input('type');
            if ($type === 'pf') {
                $query->where('customer_type', 'PF');
            } elseif ($type === 'pj') {
                $query->where('customer_type', 'PJ');
            }
        } elseif ($request->filled('customer_type')) {
            $query->where('customer_type', $request->input('customer_type'));
        }

        // Filtro por estado
        if ($request->filled('state')) {
            $query->where('state', $request->input('state'));
        }

        // Filtro por cidade
        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->input('city')}%");
        }

        // Busca por nome, documento ou email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('document', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
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
        Gate::authorize('create', Customer::class);

        return view('customers.create', [
            'states' => $this->getBrazilianStates(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerStoreRequest $request): RedirectResponse
    {
        Gate::authorize('create', Customer::class);

        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        // Sanitiza o documento (remove formatação)
        $data['document'] = preg_replace('/\D/', '', $data['document']);

        // Sanitiza o CEP
        if (! empty($data['postal_code'])) {
            $data['postal_code'] = preg_replace('/\D/', '', $data['postal_code']);
        }

        $customer = Customer::create($data);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Cliente cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): View
    {
        Gate::authorize('view', $customer);

        $customer->load(['serviceOrders']);

        return view('customers.show', [
            'customer' => $customer,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer): View
    {
        Gate::authorize('update', $customer);

        return view('customers.edit', [
            'customer' => $customer,
            'states' => $this->getBrazilianStates(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerUpdateRequest $request, Customer $customer): RedirectResponse
    {
        Gate::authorize('update', $customer);

        $data = $request->validated();

        // Sanitiza o documento (remove formatação)
        $data['document'] = preg_replace('/\D/', '', $data['document']);

        // Sanitiza o CEP
        if (! empty($data['postal_code'])) {
            $data['postal_code'] = preg_replace('/\D/', '', $data['postal_code']);
        }

        $customer->update($data);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        Gate::authorize('delete', $customer);

        // Verifica se há ordens de serviço ou pedidos vinculados
        if ($customer->serviceOrders()->exists() || $customer->orders()->exists()) {
            return redirect()
                ->route('customers.index')
                ->with('error', 'Não é possível excluir um cliente com ordens de serviço ou pedidos vinculados.');
        }

        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Cliente excluído com sucesso!');
    }

    /**
     * Get Brazilian states list.
     *
     * @return array<string, string>
     */
    private function getBrazilianStates(): array
    {
        return [
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapá',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceará',
            'DF' => 'Distrito Federal',
            'ES' => 'Espírito Santo',
            'GO' => 'Goiás',
            'MA' => 'Maranhão',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Pará',
            'PB' => 'Paraíba',
            'PR' => 'Paraná',
            'PE' => 'Pernambuco',
            'PI' => 'Piauí',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondônia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'São Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins',
        ];
    }
}
