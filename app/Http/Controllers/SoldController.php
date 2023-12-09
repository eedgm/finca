<?php

namespace App\Http\Controllers;

use App\Models\Cow;
use App\Models\Sold;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\SoldStoreRequest;
use App\Http\Requests\SoldUpdateRequest;

class SoldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Sold::class);

        $search = $request->get('search', '');

        $solds = Sold::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.solds.index', compact('solds', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Sold::class);

        $cows = Cow::pluck('name', 'id');

        return view('app.solds.create', compact('cows'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SoldStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Sold::class);

        $validated = $request->validated();

        $sold = Sold::create($validated);

        return redirect()
            ->route('solds.edit', $sold)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Sold $sold): View
    {
        $this->authorize('view', $sold);

        return view('app.solds.show', compact('sold'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Sold $sold): View
    {
        $this->authorize('update', $sold);

        $cows = Cow::pluck('name', 'id');

        return view('app.solds.edit', compact('sold', 'cows'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        SoldUpdateRequest $request,
        Sold $sold
    ): RedirectResponse {
        $this->authorize('update', $sold);

        $validated = $request->validated();

        $sold->update($validated);

        return redirect()
            ->route('solds.edit', $sold)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Sold $sold): RedirectResponse
    {
        $this->authorize('delete', $sold);

        $sold->delete();

        return redirect()
            ->route('solds.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
