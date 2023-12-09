<?php

namespace App\Http\Controllers\Api;

use App\Models\Sold;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\SoldResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\SoldCollection;
use App\Http\Requests\SoldStoreRequest;
use App\Http\Requests\SoldUpdateRequest;

class SoldController extends Controller
{
    public function index(Request $request): SoldCollection
    {
        $this->authorize('view-any', Sold::class);

        $search = $request->get('search', '');

        $solds = Sold::search($search)
            ->latest()
            ->paginate();

        return new SoldCollection($solds);
    }

    public function store(SoldStoreRequest $request): SoldResource
    {
        $this->authorize('create', Sold::class);

        $validated = $request->validated();

        $sold = Sold::create($validated);

        return new SoldResource($sold);
    }

    public function show(Request $request, Sold $sold): SoldResource
    {
        $this->authorize('view', $sold);

        return new SoldResource($sold);
    }

    public function update(SoldUpdateRequest $request, Sold $sold): SoldResource
    {
        $this->authorize('update', $sold);

        $validated = $request->validated();

        $sold->update($validated);

        return new SoldResource($sold);
    }

    public function destroy(Request $request, Sold $sold): Response
    {
        $this->authorize('delete', $sold);

        $sold->delete();

        return response()->noContent();
    }
}
