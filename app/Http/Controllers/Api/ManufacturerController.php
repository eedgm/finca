<?php

namespace App\Http\Controllers\Api;

use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ManufacturerResource;
use App\Http\Resources\ManufacturerCollection;
use App\Http\Requests\ManufacturerStoreRequest;
use App\Http\Requests\ManufacturerUpdateRequest;

class ManufacturerController extends Controller
{
    public function index(Request $request): ManufacturerCollection
    {
        $this->authorize('view-any', Manufacturer::class);

        $search = $request->get('search', '');

        $manufacturers = Manufacturer::search($search)
            ->latest()
            ->paginate();

        return new ManufacturerCollection($manufacturers);
    }

    public function store(
        ManufacturerStoreRequest $request
    ): ManufacturerResource {
        $this->authorize('create', Manufacturer::class);

        $validated = $request->validated();

        $manufacturer = Manufacturer::create($validated);

        return new ManufacturerResource($manufacturer);
    }

    public function show(
        Request $request,
        Manufacturer $manufacturer
    ): ManufacturerResource {
        $this->authorize('view', $manufacturer);

        return new ManufacturerResource($manufacturer);
    }

    public function update(
        ManufacturerUpdateRequest $request,
        Manufacturer $manufacturer
    ): ManufacturerResource {
        $this->authorize('update', $manufacturer);

        $validated = $request->validated();

        $manufacturer->update($validated);

        return new ManufacturerResource($manufacturer);
    }

    public function destroy(
        Request $request,
        Manufacturer $manufacturer
    ): Response {
        $this->authorize('delete', $manufacturer);

        $manufacturer->delete();

        return response()->noContent();
    }
}
