<?php
namespace App\Http\Controllers\Api;

use App\Models\History;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\MedicineCollection;

class HistoryMedicinesController extends Controller
{
    public function index(
        Request $request,
        History $history
    ): MedicineCollection {
        $this->authorize('view', $history);

        $search = $request->get('search', '');

        $medicines = $history
            ->medicines()
            ->search($search)
            ->latest()
            ->paginate();

        return new MedicineCollection($medicines);
    }

    public function store(
        Request $request,
        History $history,
        Medicine $medicine
    ): Response {
        $this->authorize('update', $history);

        $history->medicines()->syncWithoutDetaching([$medicine->id]);

        return response()->noContent();
    }

    public function destroy(
        Request $request,
        History $history,
        Medicine $medicine
    ): Response {
        $this->authorize('update', $history);

        $history->medicines()->detach($medicine);

        return response()->noContent();
    }
}
