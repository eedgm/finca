<?php

namespace App\Http\Controllers\Api;

use App\Models\Market;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MedicineResource;
use App\Http\Resources\MedicineCollection;

class MarketMedicinesController extends Controller
{
    public function index(Request $request, Market $market): MedicineCollection
    {
        $this->authorize('view', $market);

        $search = $request->get('search', '');

        $medicines = $market
            ->medicines()
            ->search($search)
            ->latest()
            ->paginate();

        return new MedicineCollection($medicines);
    }

    public function store(Request $request, Market $market): MedicineResource
    {
        $this->authorize('create', Medicine::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'expiration_date' => ['nullable', 'date'],
            'code' => ['nullable', 'max:255', 'string'],
            'cc' => ['nullable', 'numeric'],
            'cost' => ['nullable', 'numeric'],
        ]);

        $medicine = $market->medicines()->create($validated);

        return new MedicineResource($medicine);
    }
}
