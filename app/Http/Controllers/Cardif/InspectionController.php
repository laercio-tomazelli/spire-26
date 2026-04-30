<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cardif;

use App\Enums\InspectionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\InspectionRequest;
use App\Models\Inspection;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InspectionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Inspection::query()
            ->with(['assignedTo'])
            ->latest('id');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('claim_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('assigned_to_user_id')) {
            $query->where('assigned_to_user_id', $request->input('assigned_to_user_id'));
        }

        if ($request->filled('inspection_date')) {
            $query->whereDate('inspection_date', $request->input('inspection_date'));
        }

        /** @var LengthAwarePaginator $inspections */
        $inspections = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('cardif.inspections.partials.table', [
                'inspections' => $inspections,
            ]);
        }

        return view('cardif.inspections.index', [
            'inspections' => $inspections,
            'statuses' => InspectionStatus::selectOptions(),
            'analysts' => $this->analysts(),
            'totals' => $this->totalsByStatus(),
        ]);
    }

    public function create(): View
    {
        return view('cardif.inspections.create', [
            'inspection' => new Inspection(['status' => InspectionStatus::ToSchedule]),
            'statuses' => InspectionStatus::selectOptions(),
            'analysts' => $this->analysts(),
        ]);
    }

    public function store(InspectionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by_user_id'] = $request->user()?->id;

        $inspection = Inspection::create($data);

        return to_route('cardif.inspections.show', $inspection)
            ->with('success', 'Vistoria criada com sucesso.');
    }

    public function show(Inspection $inspection): View
    {
        $inspection->load(['assignedTo', 'createdBy']);

        return view('cardif.inspections.show', [
            'inspection' => $inspection,
        ]);
    }

    public function edit(Inspection $inspection): View
    {
        return view('cardif.inspections.edit', [
            'inspection' => $inspection,
            'statuses' => InspectionStatus::selectOptions(),
            'analysts' => $this->analysts(),
        ]);
    }

    public function update(InspectionRequest $request, Inspection $inspection): RedirectResponse
    {
        $inspection->update($request->validated());

        return to_route('cardif.inspections.show', $inspection)
            ->with('success', 'Vistoria atualizada com sucesso.');
    }

    public function destroy(Inspection $inspection): RedirectResponse
    {
        $inspection->delete();

        return to_route('cardif.inspections.index')
            ->with('success', 'Vistoria excluída com sucesso.');
    }

    /**
     * @return array<int, array{value: int, label: string}>
     */
    private function analysts(): array
    {
        return User::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (User $u): array => ['value' => $u->id, 'label' => $u->name])
            ->all();
    }

    /**
     * @return array<string, int>
     */
    private function totalsByStatus(): array
    {
        return Inspection::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();
    }
}
