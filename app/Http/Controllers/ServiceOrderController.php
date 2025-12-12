<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ServiceOrderStoreRequest;
use App\Http\Requests\ServiceOrderUpdateRequest;
use App\Models\Brand;
use App\Models\Partner;
use App\Models\RepairType;
use App\Models\ServiceLocation;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderStatus;
use App\Models\ServiceType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ServiceOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|string
    {
        Gate::authorize('viewAny', ServiceOrder::class);

        $serviceOrders = $this->getFilteredServiceOrders($request);
        $statuses = $this->getStatusesWithCounts($request);

        // Contagem de filtros ativos
        $activeFiltersCount = collect([
            $request->filled('status_id'),
            $request->filled('partner_id'),
            $request->filled('brand_id'),
            $request->filled('warranty_type'),
            $request->filled('date_from'),
            $request->filled('date_to'),
        ])->filter()->count();

        // Se for requisição AJAX, retorna apenas o partial da tabela
        if ($request->ajax()) {
            return view('service-orders.partials.table', [
                'serviceOrders' => $serviceOrders,
            ])->render();
        }

        return view('service-orders.index', [
            'serviceOrders' => $serviceOrders,
            'statuses' => $statuses,
            'activeFiltersCount' => $activeFiltersCount,
            'partners' => $this->getPartnerOptions($request),
            'brands' => $this->getBrandOptions($request),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', ServiceOrder::class);

        return view('service-orders.create', $this->getFormData($request));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceOrderStoreRequest $request): RedirectResponse
    {
        Gate::authorize('create', ServiceOrder::class);

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request): ServiceOrder {
            $tenantId = $request->user()->tenant_id;

            // Gera o próximo número da OS para o tenant
            $orderNumber = ServiceOrder::forTenant($tenantId)->max('order_number') + 1;

            return ServiceOrder::create([
                ...$validated,
                'tenant_id' => $tenantId,
                'order_number' => $orderNumber,
                'opened_at' => now(),
                'opened_by' => $request->user()->id,
            ]);
        });

        return to_route('service-orders.index')
            ->with('success', 'Ordem de Serviço criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, ServiceOrder $serviceOrder): View
    {
        Gate::authorize('view', $serviceOrder);

        $serviceOrder->load([
            'customer',
            'partner',
            'brand',
            'productModel',
            'status',
            'subStatus',
            'serviceType',
            'parts.part',
            'costs.costType',
            'comments.user',
        ]);

        // Determina a seção ativa
        $activeSection = $request->input('section', 'dados');

        return view('service-orders.show', [
            'serviceOrder' => $serviceOrder,
            'activeSection' => $activeSection,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, ServiceOrder $serviceOrder): View
    {
        Gate::authorize('update', $serviceOrder);

        $serviceOrder->load([
            'customer',
            'partner',
            'brand',
            'productModel',
            'status',
            'parts.part',
            'costs',
            'evidence',
            'comments.user',
            'supports.user',
        ]);

        return view('service-orders.edit', [
            'serviceOrder' => $serviceOrder,
            ...$this->getFormData($request),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceOrderUpdateRequest $request, ServiceOrder $serviceOrder): RedirectResponse
    {
        Gate::authorize('update', $serviceOrder);

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $serviceOrder): void {
            $serviceOrder->update($validated);
        });

        return to_route('service-orders.show', $serviceOrder)
            ->with('success', 'Ordem de Serviço atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceOrder $serviceOrder): RedirectResponse
    {
        Gate::authorize('delete', $serviceOrder);

        // Verifica se a OS pode ser excluída (apenas OSs não finalizadas)
        if ($serviceOrder->isClosed()) {
            return to_route('service-orders.index')
                ->with('error', 'Não é possível excluir uma OS já finalizada.');
        }

        $serviceOrder->delete();

        return to_route('service-orders.index')
            ->with('success', 'Ordem de Serviço excluída com sucesso.');
    }

    /**
     * Get filtered service orders query.
     */
    private function getFilteredServiceOrders(Request $request): LengthAwarePaginator
    {
        $query = ServiceOrder::query()
            ->forTenant($request->user()->tenant_id)
            ->with(['customer', 'partner', 'brand', 'productModel', 'status', 'priority']);

        // Ordenação
        $sortField = $request->input('sort', 'order_number');
        $sortDirection = $request->input('direction', 'desc');

        $allowedSortFields = ['order_number', 'customer_id', 'status_id', 'opened_at', 'closed_at', 'created_at'];
        if (in_array($sortField, $allowedSortFields, true)) {
            $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('order_number', 'desc');
        }

        // Filtro por status (tabs)
        if ($request->filled('status')) {
            $status = $request->input('status');
            match ($status) {
                'open' => $query->open(),
                'closed' => $query->closed(),
                'canceled' => $query->canceled(),
                default => $query->where('status_id', $status),
            };
        }

        // Filtro por parceiro
        if ($request->filled('partner_id')) {
            $query->where('partner_id', $request->input('partner_id'));
        }

        // Filtro por marca
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }

        // Filtro por tipo de garantia
        if ($request->filled('warranty_type')) {
            $query->where('warranty_type', $request->input('warranty_type'));
        }

        // Filtro por período
        if ($request->filled('date_from')) {
            $query->whereDate('opened_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('opened_at', '<=', $request->input('date_to'));
        }

        // Busca por número, protocolo, cliente ou serial
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('protocol', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('manufacturer_order', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search): void {
                        $cq->where('name', 'like', "%{$search}%")
                            ->orWhere('document', 'like', "%{$search}%");
                    });
            });
        }

        $perPage = $request->integer('per_page', 15);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get statuses with order counts.
     *
     * @return array<string, mixed>
     */
    private function getStatusesWithCounts(Request $request): array
    {
        $tenantId = $request->user()->tenant_id;

        $statusCounts = ServiceOrder::forTenant($tenantId)
            ->selectRaw('status_id, COUNT(*) as count')
            ->groupBy('status_id')
            ->pluck('count', 'status_id')
            ->toArray();

        $statusModels = ServiceOrderStatus::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get();

        $statuses = [];
        foreach ($statusModels as $status) {
            $statuses[] = [
                'id' => $status->id,
                'name' => $status->name,
                'code' => $status->code,
                'color' => $status->color,
                'count' => $statusCounts[$status->id] ?? 0,
            ];
        }

        // Adiciona contadores fixos
        $openCount = ServiceOrder::forTenant($tenantId)->open()->count();
        $closedCount = ServiceOrder::forTenant($tenantId)->closed()->count();
        $canceledCount = ServiceOrder::forTenant($tenantId)->canceled()->count();
        $totalCount = ServiceOrder::forTenant($tenantId)->count();

        return [
            'all' => $totalCount,
            'open' => $openCount,
            'closed' => $closedCount,
            'canceled' => $canceledCount,
            'byStatus' => $statuses,
        ];
    }

    /**
     * Get form data for create/edit views.
     */
    private function getFormData(Request $request): array
    {
        $tenantId = $request->user()->tenant_id;

        return [
            'partners' => Partner::forTenant($tenantId)
                ->where('status', 'active')
                ->orderBy('trade_name')
                ->get(['id', 'trade_name']),
            'brands' => Brand::query()
                ->whereHas('manufacturer', fn ($q) => $q->where('tenant_id', $tenantId))
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']),
            'statuses' => ServiceOrderStatus::query()
                ->where('is_active', true)
                ->orderBy('display_order')
                ->get(['id', 'name', 'color']),
            'serviceTypes' => ServiceType::where('is_active', true)
                ->orderBy('display_order')
                ->get(['id', 'name']),
            'serviceLocations' => ServiceLocation::where('is_active', true)
                ->orderBy('display_order')
                ->get(['id', 'name']),
            'repairTypes' => RepairType::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']),
            // Warranty types as simple array (enum in DB)
            'warrantyTypes' => [
                ['id' => 'in_warranty', 'name' => 'Em Garantia'],
                ['id' => 'out_of_warranty', 'name' => 'Fora de Garantia'],
            ],
        ];
    }

    /**
     * Get partner options for filters.
     */
    private function getPartnerOptions(Request $request): array
    {
        return Partner::forTenant($request->user()->tenant_id)
            ->where('status', 'active')
            ->orderBy('trade_name')
            ->pluck('trade_name', 'id')
            ->toArray();
    }

    /**
     * Get brand options for filters.
     */
    private function getBrandOptions(Request $request): array
    {
        $tenantId = $request->user()->tenant_id;

        return Brand::query()
            ->whereHas('manufacturer', fn ($q) => $q->where('tenant_id', $tenantId))
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}
