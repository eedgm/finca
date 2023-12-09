<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\History;
use App\Models\Medicine;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HistoryMedicinesDetail extends Component
{
    use AuthorizesRequests;

    public History $history;
    public Medicine $medicine;
    public $medicinesForSelect = [];
    public $medicine_id = null;
    public $cc;

    public $showingModal = false;
    public $modalTitle = 'New Medicine';

    protected $rules = [
        'medicine_id' => ['required', 'exists:medicines,id'],
        'cc' => ['nullable', 'numeric'],
    ];

    public function mount(History $history): void
    {
        $this->history = $history;
        $this->medicinesForSelect = Medicine::pluck('name', 'id');
        $this->resetMedicineData();
    }

    public function resetMedicineData(): void
    {
        $this->medicine = new Medicine();

        $this->medicine_id = null;
        $this->cc = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newMedicine(): void
    {
        $this->modalTitle = trans('crud.history_medicines.new_title');
        $this->resetMedicineData();

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

        $this->authorize('create', Medicine::class);

        $this->history->medicines()->attach($this->medicine_id, [
            'cc' => $this->cc,
        ]);

        $this->hideModal();
    }

    public function detach($medicine): void
    {
        $this->authorize('delete-any', Medicine::class);

        $this->history->medicines()->detach($medicine);

        $this->resetMedicineData();
    }

    public function render(): View
    {
        return view('livewire.history-medicines-detail', [
            'historyMedicines' => $this->history
                ->medicines()
                ->withPivot(['cc'])
                ->paginate(20),
        ]);
    }
}
