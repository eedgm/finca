<?php

namespace App\Http\Controllers\Api;

use App\Models\Cow;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\CowResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\CowCollection;
use App\Http\Requests\CowStoreRequest;
use App\Http\Requests\CowUpdateRequest;
use Illuminate\Support\Facades\Storage;

class CowController extends Controller
{
    public function index(Request $request): CowCollection
    {
        $this->authorize('view-any', Cow::class);

        $search = $request->get('search', '');

        $cows = Cow::search($search)
            ->latest()
            ->paginate();

        return new CowCollection($cows);
    }

    public function store(CowStoreRequest $request): CowResource
    {
        $this->authorize('create', Cow::class);

        $validated = $request->validated();
        if ($request->hasFile('picture')) {
            $validated['picture'] = $request->file('picture')->store('public');
        }

        $cow = Cow::create($validated);

        return new CowResource($cow);
    }

    public function show(Request $request, Cow $cow): CowResource
    {
        $this->authorize('view', $cow);

        return new CowResource($cow);
    }

    public function update(CowUpdateRequest $request, Cow $cow): CowResource
    {
        $this->authorize('update', $cow);

        $validated = $request->validated();

        if ($request->hasFile('picture')) {
            if ($cow->picture) {
                Storage::delete($cow->picture);
            }

            $validated['picture'] = $request->file('picture')->store('public');
        }

        $cow->update($validated);

        return new CowResource($cow);
    }

    public function destroy(Request $request, Cow $cow): Response
    {
        $this->authorize('delete', $cow);

        if ($cow->picture) {
            Storage::delete($cow->picture);
        }

        $cow->delete();

        return response()->noContent();
    }
}
