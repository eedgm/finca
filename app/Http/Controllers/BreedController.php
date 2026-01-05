<?php

namespace App\Http\Controllers;

use App\Models\Breed;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class BreedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Breed::class);

        $search = $request->get('search', '');

        $breeds = Breed::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.breeds.index', compact('breeds', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Breed::class);

        return view('app.breeds.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Breed::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:breeds,name'],
            'description' => ['nullable', 'string'],
        ]);

        $breed = Breed::create($validated);

        return redirect()
            ->route('breeds.edit', $breed)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Breed $breed): View
    {
        $this->authorize('view', $breed);

        return view('app.breeds.show', compact('breed'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Breed $breed): View
    {
        $this->authorize('update', $breed);

        return view('app.breeds.edit', compact('breed'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        Breed $breed
    ): RedirectResponse {
        $this->authorize('update', $breed);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('breeds')->ignore($breed->id)],
            'description' => ['nullable', 'string'],
        ]);

        $breed->update($validated);

        return redirect()
            ->route('breeds.edit', $breed)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Breed $breed
    ): RedirectResponse {
        $this->authorize('delete', $breed);

        $breed->delete();

        return redirect()
            ->route('breeds.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
