<?php

namespace App\Http\Controllers\Api;

use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\FarmResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\FarmCollection;
use App\Http\Requests\FarmStoreRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FarmUpdateRequest;

class FarmController extends Controller
{
    public function index(Request $request): FarmCollection
    {
        $this->authorize('view-any', Farm::class);

        $search = $request->get('search', '');

        $farms = Farm::search($search)
            ->latest()
            ->paginate();

        return new FarmCollection($farms);
    }

    public function store(FarmStoreRequest $request): FarmResource
    {
        $this->authorize('create', Farm::class);

        $validated = $request->validated();
        if ($request->hasFile('cattle_brand')) {
            $validated['cattle_brand'] = $request
                ->file('cattle_brand')
                ->store('public');
        }

        $farm = Farm::create($validated);

        return new FarmResource($farm);
    }

    public function show(Request $request, Farm $farm): FarmResource
    {
        $this->authorize('view', $farm);

        return new FarmResource($farm);
    }

    public function update(FarmUpdateRequest $request, Farm $farm): FarmResource
    {
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

        return new FarmResource($farm);
    }

    public function destroy(Request $request, Farm $farm): Response
    {
        $this->authorize('delete', $farm);

        if ($farm->cattle_brand) {
            Storage::delete($farm->cattle_brand);
        }

        $farm->delete();

        return response()->noContent();
    }
}
