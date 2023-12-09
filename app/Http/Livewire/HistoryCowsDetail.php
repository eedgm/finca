<?php

namespace App\Http\Livewire;

use App\Models\Cow;
use Livewire\Component;
use App\Models\History;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HistoryCowsDetail extends Component
{
    use AuthorizesRequests;

    public History $history;
    public Cow $cow;
    public $cowsForSelect = [];
    public $cow_id = null;

    public $showingModal = false;
    public $modalTitle = 'New Cow';

    protected $rules = [
        'cow_id' => ['required', 'exists:cows,id'],
    ];

    public function mount(History $history): void
    {
        $this->history = $history;
        $this->cowsForSelect = Cow::pluck('number', 'id');
        $this->resetCowData();
    }

    public function resetCowData(): void
    {
        $this->cow = new Cow();

        $this->cow_id = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newCow(): void
    {
        $this->modalTitle = trans('crud.history_cows.new_title');
        $this->resetCowData();

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

        $this->authorize('create', Cow::class);

        $this->history->cows()->attach($this->cow_id, []);

        $this->hideModal();
    }

    public function detach($cow): void
    {
        $this->authorize('delete-any', Cow::class);

        $this->history->cows()->detach($cow);

        $this->resetCowData();
    }

    public function render(): View
    {
        return view('livewire.history-cows-detail', [
            'historyCows' => $this->history
                ->cows()
                ->withPivot([])
                ->paginate(20),
        ]);
    }
}
