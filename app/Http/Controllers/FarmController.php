<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\FarmStoreRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FarmUpdateRequest;

class FarmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Farm::class);

        $search = $request->get('search', '');

        $farms = Farm::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.farms.index', compact('farms', 'search'));
    }

    public function dashboard(Request $request): View
    {
        $this->authorize('view-any', Farm::class);
        return view('app.farms.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Farm::class);

        return view('app.farms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FarmStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Farm::class);

        $validated = $request->validated();
        if ($request->hasFile('cattle_brand')) {
            $validated['cattle_brand'] = $request
                ->file('cattle_brand')
                ->store('public');
        }

        $farm = Farm::create($validated);

        return redirect()
            ->route('farms.edit', $farm)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Farm $farm): View
    {
        $this->authorize('view', $farm);

        return view('app.farms.show', compact('farm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Farm $farm): View
    {
        $this->authorize('update', $farm);

        return view('app.farms.edit', compact('farm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        FarmUpdateRequest $request,
        Farm $farm
    ): RedirectResponse {
        $this->authorize('update', $farm);

        $validated = $request->validated();
        if ($request->hasFile('cattle_brand')) {
            if ($farm->cattle_brand) {
                Storage::delete($farm->cattle_brand);
            }

            $validated['cattle_brand'] = $request
                ->file('cattle_brand')
                ->store('public');
        }

        $farm->update($validated);

        return redirect()
            ->route('farms.edit', $farm)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Farm $farm): RedirectResponse
    {
        $this->authorize('delete', $farm);

        if ($farm->cattle_brand) {
            Storage::delete($farm->cattle_brand);
        }

        $farm->delete();

        return redirect()
            ->route('farms.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
