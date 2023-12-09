<?php

namespace App\Http\Controllers;

use App\Models\Cow;
use App\Models\Farm;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CowStoreRequest;
use App\Http\Requests\CowUpdateRequest;
use Illuminate\Support\Facades\Storage;

class CowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Cow::class);

        $search = $request->get('search', '');

        $cows = Cow::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.cows.index', compact('cows', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Cow::class);

        $farms = Farm::pluck('name', 'id');

        return view('app.cows.create', compact('farms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CowStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Cow::class);

        $validated = $request->validated();
        if ($request->hasFile('picture')) {
            $validated['picture'] = $request->file('picture')->store('public');
        }

        $cow = Cow::create($validated);

        return redirect()
            ->route('cows.edit', $cow)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Cow $cow): View
    {
        $this->authorize('view', $cow);

        return view('app.cows.show', compact('cow'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Cow $cow): View
    {
        $this->authorize('update', $cow);

        $farms = Farm::pluck('name', 'id');

        return view('app.cows.edit', compact('cow', 'farms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        CowUpdateRequest $request,
        Cow $cow
    ): RedirectResponse {
        $this->authorize('update', $cow);

        $validated = $request->validated();
        if ($request->hasFile('picture')) {
            if ($cow->picture) {
                Storage::delete($cow->picture);
            }

            $validated['picture'] = $request->file('picture')->store('public');
        }

        $cow->update($validated);

        return redirect()
            ->route('cows.edit', $cow)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Cow $cow): RedirectResponse
    {
        $this->authorize('delete', $cow);

        if ($cow->picture) {
            Storage::delete($cow->picture);
        }

        $cow->delete();

        return redirect()
            ->route('cows.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
