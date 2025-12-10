<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProductModelStoreRequest;
use App\Http\Requests\ProductModelUpdateRequest;
use App\Models\Brand;
use App\Models\ProductCategory;
use App\Models\ProductModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProductModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|string
    {
        Gate::authorize('viewAny', ProductModel::class);

        $productModels = $this->getFilteredProductModels($request);

        // Contagem por status para as tabs
        $counts = [
            'all' => ProductModel::count(),
            'active' => ProductModel::where('is_active', true)->count(),
            'inactive' => ProductModel::where('is_active', false)->count(),
        ];

        // Dados para filtros
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $categories = ProductCategory::orderBy('name')->get();

        // Contagem de filtros ativos
        $activeFiltersCount = collect([
            $request->filled('brand_id'),
            $request->filled('product_category_id'),
        ])->filter()->count();

        // Se for requisição AJAX, retorna apenas o partial da tabela
        if ($request->ajax()) {
            return view('product-models.partials.table-filament', [
                'productModels' => $productModels,
            ])->render();
        }

        return view('product-models.index', [
            'productModels' => $productModels,
            'brands' => $brands,
            'categories' => $categories,
            'counts' => $counts,
            'activeFiltersCount' => $activeFiltersCount,
        ]);
    }

    /**
     * Get filtered product models query.
     */
    private function getFilteredProductModels(Request $request): LengthAwarePaginator
    {
        $query = ProductModel::query()
            ->with(['brand', 'category']);

        // Ordenação
        $sortField = $request->input('sort', 'model_name');
        $sortDirection = $request->input('direction', 'asc');

        // Validar campos permitidos para ordenação
        $allowedSortFields = ['model_name', 'model_code', 'brand_id', 'is_active', 'created_at'];
        if (in_array($sortField, $allowedSortFields, true)) {
            $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('model_name');
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

        // Filtro por marca
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }

        // Filtro por categoria
        if ($request->filled('product_category_id')) {
            $query->where('product_category_id', $request->input('product_category_id'));
        }

        // Busca por nome ou código
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('model_name', 'like', "%{$search}%")
                    ->orWhere('model_code', 'like', "%{$search}%")
                    ->orWhere('manufacturer_model', 'like', "%{$search}%")
                    ->orWhere('ean', 'like', "%{$search}%");
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
        Gate::authorize('create', ProductModel::class);

        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $categories = ProductCategory::orderBy('name')->get();

        return view('product-models.create', [
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductModelStoreRequest $request): RedirectResponse
    {
        Gate::authorize('create', ProductModel::class);

        $validated = $request->validated();

        $productModel = ProductModel::create($validated);

        return to_route('product-models.show', $productModel)
            ->with('success', 'Modelo de produto criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductModel $productModel): View
    {
        Gate::authorize('view', $productModel);

        $productModel->load(['brand', 'category', 'parts']);

        return view('product-models.show', [
            'productModel' => $productModel,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductModel $productModel): View
    {
        Gate::authorize('update', $productModel);

        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $categories = ProductCategory::orderBy('name')->get();

        return view('product-models.edit', [
            'productModel' => $productModel,
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductModelUpdateRequest $request, ProductModel $productModel): RedirectResponse
    {
        Gate::authorize('update', $productModel);

        $validated = $request->validated();

        $productModel->update($validated);

        return to_route('product-models.show', $productModel)
            ->with('success', 'Modelo de produto atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductModel $productModel): RedirectResponse
    {
        Gate::authorize('delete', $productModel);

        // Verificar se tem ordens de serviço vinculadas
        if ($productModel->serviceOrders()->exists()) {
            return back()->with('error', 'Não é possível excluir um modelo de produto com ordens de serviço vinculadas.');
        }

        $productModel->delete();

        return to_route('product-models.index')
            ->with('success', 'Modelo de produto excluído com sucesso.');
    }

    /**
     * Toggle product model active status.
     */
    public function toggleActive(ProductModel $productModel): RedirectResponse
    {
        Gate::authorize('update', $productModel);

        $productModel->update(['is_active' => ! $productModel->is_active]);

        $status = $productModel->is_active ? 'ativado' : 'desativado';

        return back()->with('success', "Modelo de produto {$status} com sucesso.");
    }
}
