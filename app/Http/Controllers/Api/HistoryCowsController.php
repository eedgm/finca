<?php
namespace App\Http\Controllers\Api;

use App\Models\Cow;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\CowCollection;

class HistoryCowsController extends Controller
{
    public function index(Request $request, History $history): CowCollection
    {
        $this->authorize('view', $history);

        $search = $request->get('search', '');

        $cows = $history
            ->cows()
            ->search($search)
            ->latest()
            ->paginate();

        return new CowCollection($cows);
    }

    public function store(
        Request $request,
        History $history,
        Cow $cow
    ): Response {
        $this->authorize('update', $history);

        $history->cows()->syncWithoutDetaching([$cow->id]);

        return response()->noContent();
    }

    public function destroy(
        Request $request,
        History $history,
        Cow $cow
    ): Response {
        $this->authorize('update', $history);

        $history->cows()->detach($cow);

        return response()->noContent();
    }
}
