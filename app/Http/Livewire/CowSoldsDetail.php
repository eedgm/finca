<?php

namespace App\Http\Livewire;

use App\Models\Cow;
use App\Models\Sold;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CowSoldsDetail extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public Cow $cow;
    public Sold $sold;
    public $soldDate;

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Sold';

    protected $rules = [
        'soldDate' => ['required', 'date'],
        'sold.pounds' => ['nullable', 'numeric'],
        'sold.kilograms' => ['nullable', 'numeric'],
        'sold.price' => ['nullable', 'numeric'],
        'sold.number_sold' => ['nullable', 'max:255', 'string'],
    ];

    public function mount(Cow $cow): void
    {
        $this->cow = $cow;
        $this->resetSoldData();
    }

    public function resetSoldData(): void
    {
        $this->sold = new Sold();

        $this->soldDate = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newSold(): void
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.cow_solds.new_title');
        $this->resetSoldData();

        $this->showModal();
    }

    public function editSold(Sold $sold): void
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.cow_solds.edit_title');
        $this->sold = $sold;

        $this->soldDate = optional($this->sold->date)->format('Y-m-d');

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

        if (!$this->sold->cow_id) {
            $this->authorize('create', Sold::class);

            $this->sold->cow_id = $this->cow->id;
        } else {
            $this->authorize('update', $this->sold);
        }

        $this->sold->date = \Carbon\Carbon::make($this->soldDate);

        $this->sold->save();

        $this->hideModal();
    }

    public function destroySelected(): void
    {
        $this->authorize('delete-any', Sold::class);

        Sold::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->allSelected = false;

        $this->resetSoldData();
    }

    public function toggleFullSelection(): void
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->cow->solds as $sold) {
            array_push($this->selected, $sold->id);
        }
    }

    public function render(): View
    {
        return view('livewire.cow-solds-detail', [
            'solds' => $this->cow->solds()->paginate(20),
        ]);
    }
}
