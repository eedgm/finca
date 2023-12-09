<?php

namespace App\Http\Controllers;

use App\Models\Market;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\MarketStoreRequest;
use App\Http\Requests\MarketUpdateRequest;

class MarketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Market::class);

        $search = $request->get('search', '');

        $markets = Market::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.markets.index', compact('markets', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Market::class);

        return view('app.markets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MarketStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Market::class);

        $validated = $request->validated();

        $market = Market::create($validated);

        return redirect()
            ->route('markets.edit', $market)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Market $market): View
    {
        $this->authorize('view', $market);

        return view('app.markets.show', compact('market'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Market $market): View
    {
        $this->authorize('update', $market);

        return view('app.markets.edit', compact('market'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        MarketUpdateRequest $request,
        Market $market
    ): RedirectResponse {
        $this->authorize('update', $market);

        $validated = $request->validated();

        $market->update($validated);

        return redirect()
            ->route('markets.edit', $market)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Market $market): RedirectResponse
    {
        $this->authorize('delete', $market);

        $market->delete();

        return redirect()
            ->route('markets.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
