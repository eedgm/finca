<?php

namespace App\Http\Controllers\Api;

use App\Models\Manufacturer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MedicineResource;
use App\Http\Resources\MedicineCollection;

class ManufacturerMedicinesController extends Controller
{
    public function index(
        Request $request,
        Manufacturer $manufacturer
    ): MedicineCollection {
        $this->authorize('view', $manufacturer);

        $search = $request->get('search', '');

        $medicines = $manufacturer
            ->medicines()
            ->search($search)
            ->latest()
            ->paginate();

        return new MedicineCollection($medicines);
    }

    public function store(
        Request $request,
        Manufacturer $manufacturer
    ): MedicineResource {
        $this->authorize('create', Medicine::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'expiration_date' => ['nullable', 'date'],
            'code' => ['nullable', 'max:255', 'string'],
            'cc' => ['nullable', 'numeric'],
            'cost' => ['nullable', 'numeric'],
            'market_id' => ['required', 'exists:markets,id'],
        ]);

        $medicine = $manufacturer->medicines()->create($validated);

        return new MedicineResource($medicine);
    }
}
