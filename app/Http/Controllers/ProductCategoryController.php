<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\ProductLine;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|string
    {
        $categories = $this->getFilteredCategories($request);

        $counts = [
            'all' => ProductCategory::count(),
        ];

        $productLines = ProductLine::orderBy('name')->get();

        $activeFiltersCount = collect([
            $request->filled('product_line_id'),
        ])->filter()->count();

        if ($request->ajax()) {
            return view('product-categories.partials.table-filament', [
                'categories' => $categories,
            ])->render();
        }

        return view('product-categories.index', [
            'categories' => $categories,
            'productLines' => $productLines,
            'counts' => $counts,
            'activeFiltersCount' => $activeFiltersCount,
        ]);
    }

    /**
     * Get filtered categories query.
     */
    private function getFilteredCategories(Request $request): LengthAwarePaginator
    {
        $query = ProductCategory::query()
            ->with('productLine')
            ->withCount('productModels');

        // Ordenação
        $sortField = $request->input('sort', 'name');
        $sortDirection = $request->input('direction', 'asc');

        $allowedSortFields = ['name', 'product_line_id', 'created_at'];
        if (in_array($sortField, $allowedSortFields, true)) {
            $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('name');
        }

        // Filtro por linha de produto
        if ($request->filled('product_line_id')) {
            $query->where('product_line_id', $request->input('product_line_id'));
        }

        // Busca
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
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
        $productLines = ProductLine::orderBy('name')->get();

        return view('product-categories.create', [
            'productLines' => $productLines,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_line_id' => ['required', 'integer', 'exists:product_lines,id'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
        ], [], [
            'product_line_id' => 'linha de produto',
            'name' => 'nome',
            'description' => 'descrição',
        ]);

        $category = ProductCategory::create($validated);

        return to_route('product-categories.index')
            ->with('success', "Categoria \"{$category->name}\" criada com sucesso.");
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $productCategory): View
    {
        $productCategory->load(['productLine', 'productModels']);

        return view('product-categories.show', [
            'category' => $productCategory,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $productCategory): View
    {
        $productLines = ProductLine::orderBy('name')->get();

        return view('product-categories.edit', [
            'category' => $productCategory,
            'productLines' => $productLines,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCategory $productCategory): RedirectResponse
    {
        $validated = $request->validate([
            'product_line_id' => ['required', 'integer', 'exists:product_lines,id'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
        ], [], [
            'product_line_id' => 'linha de produto',
            'name' => 'nome',
            'description' => 'descrição',
        ]);

        $productCategory->update($validated);

        return to_route('product-categories.index')
            ->with('success', "Categoria \"{$productCategory->name}\" atualizada com sucesso.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory): RedirectResponse
    {
        if ($productCategory->productModels()->exists()) {
            return back()->with('error', 'Não é possível excluir uma categoria com modelos de produto vinculados.');
        }

        $name = $productCategory->name;
        $productCategory->delete();

        return to_route('product-categories.index')
            ->with('success', "Categoria \"{$name}\" excluída com sucesso.");
    }
}
