<?php

namespace App\Http\Controllers;

use App\Models\CowType;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CowTypeStoreRequest;
use App\Http\Requests\CowTypeUpdateRequest;

class CowTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', CowType::class);

        $search = $request->get('search', '');

        $cowTypes = CowType::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.cow_types.index', compact('cowTypes', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', CowType::class);

        return view('app.cow_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CowTypeStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', CowType::class);

        $validated = $request->validated();

        $cowType = CowType::create($validated);

        return redirect()
            ->route('cow-types.edit', $cowType)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, CowType $cowType): View
    {
        $this->authorize('view', $cowType);

        return view('app.cow_types.show', compact('cowType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, CowType $cowType): View
    {
        $this->authorize('update', $cowType);

        return view('app.cow_types.edit', compact('cowType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        CowTypeUpdateRequest $request,
        CowType $cowType
    ): RedirectResponse {
        $this->authorize('update', $cowType);

        $validated = $request->validated();

        $cowType->update($validated);

        return redirect()
            ->route('cow-types.edit', $cowType)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        CowType $cowType
    ): RedirectResponse {
        $this->authorize('delete', $cowType);

        $cowType->delete();

        return redirect()
            ->route('cow-types.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
