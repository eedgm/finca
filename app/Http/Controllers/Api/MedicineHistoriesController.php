<?php
namespace App\Http\Controllers\Api;

use App\Models\History;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\HistoryCollection;

class MedicineHistoriesController extends Controller
{
    public function index(
        Request $request,
        Medicine $medicine
    ): HistoryCollection {
        $this->authorize('view', $medicine);

        $search = $request->get('search', '');

        $histories = $medicine
            ->histories()
            ->search($search)
            ->latest()
            ->paginate();

        return new HistoryCollection($histories);
    }

    public function store(
        Request $request,
        Medicine $medicine,
        History $history
    ): Response {
        $this->authorize('update', $medicine);

        $medicine->histories()->syncWithoutDetaching([$history->id]);

        return response()->noContent();
    }

    public function destroy(
        Request $request,
        Medicine $medicine,
        History $history
    ): Response {
        $this->authorize('update', $medicine);

        $medicine->histories()->detach($history);

        return response()->noContent();
    }
}
