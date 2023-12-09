<?php

namespace App\Http\Controllers\Api;

use App\Models\Farm;
use Illuminate\Http\Request;
use App\Http\Resources\CowResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\CowCollection;

class FarmCowsController extends Controller
{
    public function index(Request $request, Farm $farm): CowCollection
    {
        $this->authorize('view', $farm);

        $search = $request->get('search', '');

        $cows = $farm
            ->cows()
            ->search($search)
            ->latest()
            ->paginate();

        return new CowCollection($cows);
    }

    public function store(Request $request, Farm $farm): CowResource
    {
        $this->authorize('create', Cow::class);

        $validated = $request->validate([
            'number' => ['nullable', 'numeric'],
            'name' => ['nullable', 'max:255', 'string'],
            'gender' => ['required', 'in:male,female'],
            'parent_id' => ['nullable', 'max:255'],
            'mother_id' => ['nullable', 'max:255'],
            'owner' => ['nullable', 'max:255', 'string'],
            'sold' => ['required', 'boolean'],
            'picture' => ['image', 'max:1024', 'nullable'],
            'born' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('picture')) {
            $validated['picture'] = $request->file('picture')->store('public');
        }

        $cow = $farm->cows()->create($validated);

        return new CowResource($cow);
    }
}
