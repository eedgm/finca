<?php

namespace App\Http\Livewire;

use App\Models\Market;
use Livewire\Component;
use App\Models\Medicine;
use Illuminate\View\View;
use Livewire\WithPagination;
use App\Models\Manufacturer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MarketMedicinesDetail extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public Market $market;
    public Medicine $medicine;
    public $manufacturersForSelect = [];
    public $medicineExpirationDate;

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Medicine';

    protected $rules = [
        'medicine.name' => ['required', 'max:255', 'string'],
        'medicine.manufacturer_id' => ['required', 'exists:manufacturers,id'],
        'medicineExpirationDate' => ['nullable', 'date'],
        'medicine.code' => ['nullable', 'max:255', 'string'],
        'medicine.cc' => ['nullable', 'numeric'],
        'medicine.cost' => ['nullable', 'numeric'],
    ];

    public function mount(Market $market): void
    {
        $this->market = $market;
        $this->manufacturersForSelect = Manufacturer::pluck('name', 'id');
        $this->resetMedicineData();
    }

    public function resetMedicineData(): void
    {
        $this->medicine = new Medicine();

        $this->medicineExpirationDate = null;
        $this->medicine->manufacturer_id = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newMedicine(): void
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.market_medicines.new_title');
        $this->resetMedicineData();

        $this->showModal();
    }

    public function editMedicine(Medicine $medicine): void
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.market_medicines.edit_title');
        $this->medicine = $medicine;

        $this->medicineExpirationDate = optional(
            $this->medicine->expiration_date
        )->format('Y-m-d');

        $this->dispatchBrowserEvent('refresh');

        $this->showModal();
    }

    public function showModal(): void
    {
        $this->resetErrorBag();
        $this->showingModal = true;
    }

    public function hideModal(): void
    {
        $this->showingModal = false;
    }

    public function save(): void
    {
        $this->validate();

        if (!$this->medicine->market_id) {
            $this->authorize('create', Medicine::class);

            $this->medicine->market_id = $this->market->id;
        } else {
            $this->authorize('update', $this->medicine);
        }

        $this->medicine->expiration_date = \Carbon\Carbon::make(
            $this->medicineExpirationDate
        );

        $this->medicine->save();

        $this->hideModal();
    }

    public function destroySelected(): void
    {
        $this->authorize('delete-any', Medicine::class);

        Medicine::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->allSelected = false;

        $this->resetMedicineData();
    }

    public function toggleFullSelection(): void
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->market->medicines as $medicine) {
            array_push($this->selected, $medicine->id);
        }
    }

    public function render(): View
    {
        return view('livewire.market-medicines-detail', [
            'medicines' => $this->market->medicines()->paginate(20),
        ]);
    }
}
