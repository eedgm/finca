<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\CowType;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\HistoryStoreRequest;
use App\Http\Requests\HistoryUpdateRequest;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', History::class);

        $search = $request->get('search', '');

        $histories = History::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.histories.index', compact('histories', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', History::class);

        $cowTypes = CowType::pluck('name', 'id');

        return view('app.histories.create', compact('cowTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HistoryStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', History::class);

        $validated = $request->validated();
        if ($request->hasFile('picture')) {
            $validated['picture'] = $request->file('picture')->store('public');
        }

        $history = History::create($validated);

        return redirect()
            ->route('histories.edit', $history)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, History $history): View
    {
        $this->authorize('view', $history);

        return view('app.histories.show', compact('history'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, History $history): View
    {
        $this->authorize('update', $history);

        $cowTypes = CowType::pluck('name', 'id');

        return view('app.histories.edit', compact('history', 'cowTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        HistoryUpdateRequest $request,
        History $history
    ): RedirectResponse {
        $this->authorize('update', $history);

        $validated = $request->validated();
        if ($request->hasFile('picture')) {
            if ($history->picture) {
                Storage::delete($history->picture);
            }

            $validated['picture'] = $request->file('picture')->store('public');
        }

        $history->update($validated);

        return redirect()
            ->route('histories.edit', $history)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        History $history
    ): RedirectResponse {
        $this->authorize('delete', $history);

        if ($history->picture) {
            Storage::delete($history->picture);
        }

        $history->delete();

        return redirect()
            ->route('histories.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
