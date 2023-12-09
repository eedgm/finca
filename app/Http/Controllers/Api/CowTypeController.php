<?php

namespace App\Http\Controllers\Api;

use App\Models\CowType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\CowTypeResource;
use App\Http\Resources\CowTypeCollection;
use App\Http\Requests\CowTypeStoreRequest;
use App\Http\Requests\CowTypeUpdateRequest;

class CowTypeController extends Controller
{
    public function index(Request $request): CowTypeCollection
    {
        $this->authorize('view-any', CowType::class);

        $search = $request->get('search', '');

        $cowTypes = CowType::search($search)
            ->latest()
            ->paginate();

        return new CowTypeCollection($cowTypes);
    }

    public function store(CowTypeStoreRequest $request): CowTypeResource
    {
        $this->authorize('create', CowType::class);

        $validated = $request->validated();

        $cowType = CowType::create($validated);

        return new CowTypeResource($cowType);
    }

    public function show(Request $request, CowType $cowType): CowTypeResource
    {
        $this->authorize('view', $cowType);

        return new CowTypeResource($cowType);
    }

    public function update(
        CowTypeUpdateRequest $request,
        CowType $cowType
    ): CowTypeResource {
        $this->authorize('update', $cowType);

        $validated = $request->validated();

        $cowType->update($validated);

        return new CowTypeResource($cowType);
    }

    public function destroy(Request $request, CowType $cowType): Response
    {
        $this->authorize('delete', $cowType);

        $cowType->delete();

        return response()->noContent();
    }
}
