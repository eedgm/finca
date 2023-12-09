<?php

namespace App\Http\Livewire;

use App\Models\Cow;
use Livewire\Component;
use App\Models\History;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CowHistoriesDetail extends Component
{
    use AuthorizesRequests;

    public Cow $cow;
    public History $history;
    public $historiesForSelect = [];
    public $history_id = null;

    public $showingModal = false;
    public $modalTitle = 'New History';

    protected $rules = [
        'history_id' => ['required', 'exists:histories,id'],
    ];

    public function mount(Cow $cow): void
    {
        $this->cow = $cow;
        $this->historiesForSelect = History::pluck('date', 'id');
        $this->resetHistoryData();
    }

    public function resetHistoryData(): void
    {
        $this->history = new History();

        $this->history_id = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newHistory(): void
    {
        $this->modalTitle = trans('crud.cow_histories.new_title');
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

        $this->cow->histories()->attach($this->history_id, []);

        $this->hideModal();
    }

    public function detach($history): void
    {
        $this->authorize('delete-any', History::class);

        $this->cow->histories()->detach($history);

        $this->resetHistoryData();
    }

    public function render(): View
    {
        return view('livewire.cow-histories-detail', [
            'cowHistories' => $this->cow
                ->histories()
                ->withPivot([])
                ->paginate(20),
        ]);
    }
}
