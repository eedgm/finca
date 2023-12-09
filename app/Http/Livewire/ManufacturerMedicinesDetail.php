<?php

namespace App\Http\Livewire;

use App\Models\Market;
use Livewire\Component;
use App\Models\Medicine;
use Illuminate\View\View;
use Livewire\WithPagination;
use App\Models\Manufacturer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ManufacturerMedicinesDetail extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public Manufacturer $manufacturer;
    public Medicine $medicine;
    public $marketsForSelect = [];
    public $medicineExpirationDate;

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Medicine';

    protected $rules = [
        'medicine.name' => ['required', 'max:255', 'string'],
        'medicineExpirationDate' => ['nullable', 'date'],
        'medicine.code' => ['nullable', 'max:255', 'string'],
        'medicine.cc' => ['nullable', 'numeric'],
        'medicine.cost' => ['nullable', 'numeric'],
        'medicine.market_id' => ['required', 'exists:markets,id'],
    ];

    public function mount(Manufacturer $manufacturer): void
    {
        $this->manufacturer = $manufacturer;
        $this->marketsForSelect = Market::pluck('name', 'id');
        $this->resetMedicineData();
    }

    public function resetMedicineData(): void
    {
        $this->medicine = new Medicine();

        $this->medicineExpirationDate = null;
        $this->medicine->market_id = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newMedicine(): void
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.manufacturer_medicines.new_title');
        $this->resetMedicineData();

        $this->showModal();
    }

    public function editMedicine(Medicine $medicine): void
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.manufacturer_medicines.edit_title');
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

        if (!$this->medicine->manufacturer_id) {
            $this->authorize('create', Medicine::class);

            $this->medicine->manufacturer_id = $this->manufacturer->id;
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

        foreach ($this->manufacturer->medicines as $medicine) {
            array_push($this->selected, $medicine->id);
        }
    }

    public function render(): View
    {
        return view('livewire.manufacturer-medicines-detail', [
            'medicines' => $this->manufacturer->medicines()->paginate(20),
        ]);
    }
}
