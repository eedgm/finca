<?php

namespace App\Http\Controllers\Api;

use App\Models\CowType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\HistoryResource;
use App\Http\Resources\HistoryCollection;

class CowTypeHistoriesController extends Controller
{
    public function index(Request $request, CowType $cowType): HistoryCollection
    {
        $this->authorize('view', $cowType);

        $search = $request->get('search', '');

        $histories = $cowType
            ->histories()
            ->search($search)
            ->latest()
            ->paginate();

        return new HistoryCollection($histories);
    }

    public function store(Request $request, CowType $cowType): HistoryResource
    {
        $this->authorize('create', History::class);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'weight' => ['nullable', 'numeric'],
            'comments' => ['nullable', 'max:255', 'string'],
            'picture' => ['image', 'max:1024', 'nullable'],
        ]);

        if ($request->hasFile('picture')) {
            $validated['picture'] = $request->file('picture')->store('public');
        }

        $history = $cowType->histories()->create($validated);

        return new HistoryResource($history);
    }
}
