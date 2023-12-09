<?php

namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\Medicine;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Manufacturer;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\MedicineStoreRequest;
use App\Http\Requests\MedicineUpdateRequest;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Medicine::class);

        $search = $request->get('search', '');

        $medicines = Medicine::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.medicines.index', compact('medicines', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Medicine::class);

        $manufacturers = Manufacturer::pluck('name', 'id');
        $markets = Market::pluck('name', 'id');

        return view(
            'app.medicines.create',
            compact('manufacturers', 'markets')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MedicineStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Medicine::class);

        $validated = $request->validated();

        $medicine = Medicine::create($validated);

        return redirect()
            ->route('medicines.edit', $medicine)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Medicine $medicine): View
    {
        $this->authorize('view', $medicine);

        return view('app.medicines.show', compact('medicine'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Medicine $medicine): View
    {
        $this->authorize('update', $medicine);

        $manufacturers = Manufacturer::pluck('name', 'id');
        $markets = Market::pluck('name', 'id');

        return view(
            'app.medicines.edit',
            compact('medicine', 'manufacturers', 'markets')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        MedicineUpdateRequest $request,
        Medicine $medicine
    ): RedirectResponse {
        $this->authorize('update', $medicine);

        $validated = $request->validated();

        $medicine->update($validated);

        return redirect()
            ->route('medicines.edit', $medicine)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Medicine $medicine
    ): RedirectResponse {
        $this->authorize('delete', $medicine);

        $medicine->delete();

        return redirect()
            ->route('medicines.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
