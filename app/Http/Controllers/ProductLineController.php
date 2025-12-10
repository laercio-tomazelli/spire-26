<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ProductLine;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductLineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|string
    {
        $productLines = $this->getFilteredProductLines($request);

        $counts = [
            'all' => ProductLine::count(),
        ];

        if ($request->ajax()) {
            return view('product-lines.partials.table-filament', [
                'productLines' => $productLines,
            ])->render();
        }

        return view('product-lines.index', [
            'productLines' => $productLines,
            'counts' => $counts,
        ]);
    }

    /**
     * Get filtered product lines query.
     */
    private function getFilteredProductLines(Request $request): LengthAwarePaginator
    {
        $query = ProductLine::query()
            ->withCount('categories');

        // Ordenação
        $sortField = $request->input('sort', 'name');
        $sortDirection = $request->input('direction', 'asc');

        $allowedSortFields = ['name', 'created_at'];
        if (in_array($sortField, $allowedSortFields, true)) {
            $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('name');
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
        return view('product-lines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:product_lines,name'],
            'description' => ['nullable', 'string', 'max:255'],
        ], [], [
            'name' => 'nome',
            'description' => 'descrição',
        ]);

        $productLine = ProductLine::create($validated);

        return to_route('product-lines.index')
            ->with('success', "Linha de produto \"{$productLine->name}\" criada com sucesso.");
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductLine $productLine): View
    {
        $productLine->load('categories');

        return view('product-lines.show', [
            'productLine' => $productLine,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductLine $productLine): View
    {
        return view('product-lines.edit', [
            'productLine' => $productLine,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductLine $productLine): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:product_lines,name,'.$productLine->id],
            'description' => ['nullable', 'string', 'max:255'],
        ], [], [
            'name' => 'nome',
            'description' => 'descrição',
        ]);

        $productLine->update($validated);

        return to_route('product-lines.index')
            ->with('success', "Linha de produto \"{$productLine->name}\" atualizada com sucesso.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductLine $productLine): RedirectResponse
    {
        if ($productLine->categories()->exists()) {
            return back()->with('error', 'Não é possível excluir uma linha de produto com categorias vinculadas.');
        }

        $name = $productLine->name;
        $productLine->delete();

        return to_route('product-lines.index')
            ->with('success', "Linha de produto \"{$name}\" excluída com sucesso.");
    }
}
