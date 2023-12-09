<?php

namespace App\Http\Controllers\Api;

use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\MarketResource;
use App\Http\Resources\MarketCollection;
use App\Http\Requests\MarketStoreRequest;
use App\Http\Requests\MarketUpdateRequest;

class MarketController extends Controller
{
    public function index(Request $request): MarketCollection
    {
        $this->authorize('view-any', Market::class);

        $search = $request->get('search', '');

        $markets = Market::search($search)
            ->latest()
            ->paginate();

        return new MarketCollection($markets);
    }

    public function store(MarketStoreRequest $request): MarketResource
    {
        $this->authorize('create', Market::class);

        $validated = $request->validated();

        $market = Market::create($validated);

        return new MarketResource($market);
    }

    public function show(Request $request, Market $market): MarketResource
    {
        $this->authorize('view', $market);

        return new MarketResource($market);
    }

    public function update(
        MarketUpdateRequest $request,
        Market $market
    ): MarketResource {
        $this->authorize('update', $market);

        $validated = $request->validated();

        $market->update($validated);

        return new MarketResource($market);
    }

    public function destroy(Request $request, Market $market): Response
    {
        $this->authorize('delete', $market);

        $market->delete();

        return response()->noContent();
    }
}
