<?php
namespace App\Http\Controllers\Api;

use App\Models\Cow;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\HistoryCollection;

class CowHistoriesController extends Controller
{
    public function index(Request $request, Cow $cow): HistoryCollection
    {
        $this->authorize('view', $cow);

        $search = $request->get('search', '');

        $histories = $cow
            ->histories()
            ->search($search)
            ->latest()
            ->paginate();

        return new HistoryCollection($histories);
    }

    public function store(
        Request $request,
        Cow $cow,
        History $history
    ): Response {
        $this->authorize('update', $cow);

        $cow->histories()->syncWithoutDetaching([$history->id]);

        return response()->noContent();
    }

    public function destroy(
        Request $request,
        Cow $cow,
        History $history
    ): Response {
        $this->authorize('update', $cow);

        $cow->histories()->detach($history);

        return response()->noContent();
    }
}
