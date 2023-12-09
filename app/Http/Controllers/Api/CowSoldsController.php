<?php

namespace App\Http\Controllers\Api;

use App\Models\Cow;
use Illuminate\Http\Request;
use App\Http\Resources\SoldResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\SoldCollection;

class CowSoldsController extends Controller
{
    public function index(Request $request, Cow $cow): SoldCollection
    {
        $this->authorize('view', $cow);

        $search = $request->get('search', '');

        $solds = $cow
            ->solds()
            ->search($search)
            ->latest()
            ->paginate();

        return new SoldCollection($solds);
    }

    public function store(Request $request, Cow $cow): SoldResource
    {
        $this->authorize('create', Sold::class);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'pounds' => ['nullable', 'numeric'],
            'kilograms' => ['nullable', 'numeric'],
            'price' => ['nullable', 'numeric'],
            'number_sold' => ['nullable', 'max:255', 'string'],
        ]);

        $sold = $cow->solds()->create($validated);

        return new SoldResource($sold);
    }
}
