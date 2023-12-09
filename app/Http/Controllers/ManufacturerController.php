<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ManufacturerStoreRequest;
use App\Http\Requests\ManufacturerUpdateRequest;

class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Manufacturer::class);

        $search = $request->get('search', '');

        $manufacturers = Manufacturer::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.manufacturers.index',
            compact('manufacturers', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Manufacturer::class);

        return view('app.manufacturers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ManufacturerStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Manufacturer::class);

        $validated = $request->validated();

        $manufacturer = Manufacturer::create($validated);

        return redirect()
            ->route('manufacturers.edit', $manufacturer)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Manufacturer $manufacturer): View
    {
        $this->authorize('view', $manufacturer);

        return view('app.manufacturers.show', compact('manufacturer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Manufacturer $manufacturer): View
    {
        $this->authorize('update', $manufacturer);

        return view('app.manufacturers.edit', compact('manufacturer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ManufacturerUpdateRequest $request,
        Manufacturer $manufacturer
    ): RedirectResponse {
        $this->authorize('update', $manufacturer);

        $validated = $request->validated();

        $manufacturer->update($validated);

        return redirect()
            ->route('manufacturers.edit', $manufacturer)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Manufacturer $manufacturer
    ): RedirectResponse {
        $this->authorize('delete', $manufacturer);

        $manufacturer->delete();

        return redirect()
            ->route('manufacturers.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
