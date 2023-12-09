<?php

namespace App\Http\Controllers\Api;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\MedicineResource;
use App\Http\Resources\MedicineCollection;
use App\Http\Requests\MedicineStoreRequest;
use App\Http\Requests\MedicineUpdateRequest;

class MedicineController extends Controller
{
    public function index(Request $request): MedicineCollection
    {
        $this->authorize('view-any', Medicine::class);

        $search = $request->get('search', '');

        $medicines = Medicine::search($search)
            ->latest()
            ->paginate();

        return new MedicineCollection($medicines);
    }

    public function store(MedicineStoreRequest $request): MedicineResource
    {
        $this->authorize('create', Medicine::class);

        $validated = $request->validated();

        $medicine = Medicine::create($validated);

        return new MedicineResource($medicine);
    }

    public function show(Request $request, Medicine $medicine): MedicineResource
    {
        $this->authorize('view', $medicine);

        return new MedicineResource($medicine);
    }

    public function update(
        MedicineUpdateRequest $request,
        Medicine $medicine
    ): MedicineResource {
        $this->authorize('update', $medicine);

        $validated = $request->validated();

        $medicine->update($validated);

        return new MedicineResource($medicine);
    }

    public function destroy(Request $request, Medicine $medicine): Response
    {
        $this->authorize('delete', $medicine);

        $medicine->delete();

        return response()->noContent();
    }
}
