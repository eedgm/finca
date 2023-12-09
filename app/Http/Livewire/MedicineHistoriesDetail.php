<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\History;
use App\Models\Medicine;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MedicineHistoriesDetail extends Component
{
    use AuthorizesRequests;

    public Medicine $medicine;
    public History $history;
    public $historiesForSelect = [];
    public $history_id = null;
    public $cc;

    public $showingModal = false;
    public $modalTitle = 'New History';

    protected $rules = [
        'history_id' => ['required', 'exists:histories,id'],
        'cc' => ['nullable', 'numeric'],
    ];

    public function mount(Medicine $medicine): void
    {
        $this->medicine = $medicine;
        $this->historiesForSelect = History::pluck('date', 'id');
        $this->resetHistoryData();
    }

    public function resetHistoryData(): void
    {
        $this->history = new History();

        $this->history_id = null;
        $this->cc = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newHistory(): void
    {
        $this->modalTitle = trans('crud.medicine_histories.new_title');
        $this->resetHistoryData();

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

        $this->authorize('create', History::class);

        $this->medicine->histories()->attach($this->history_id, [
            'cc' => $this->cc,
        ]);

        $this->hideModal();
    }

    public function detach($history): void
    {
        $this->authorize('delete-any', History::class);

        $this->medicine->histories()->detach($history);

        $this->resetHistoryData();
    }

    public function render(): View
    {
        return view('livewire.medicine-histories-detail', [
            'medicineHistories' => $this->medicine
                ->histories()
                ->withPivot(['cc'])
                ->paginate(20),
        ]);
    }
}
