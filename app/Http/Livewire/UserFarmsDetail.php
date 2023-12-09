<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Farm;
use Livewire\Component;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserFarmsDetail extends Component
{
    use AuthorizesRequests;

    public User $user;
    public Farm $farm;
    public $farmsForSelect = [];
    public $farm_id = null;

    public $showingModal = false;
    public $modalTitle = 'New Farm';

    protected $rules = [
        'farm_id' => ['required', 'exists:farms,id'],
    ];

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->farmsForSelect = Farm::pluck('name', 'id');
        $this->resetFarmData();
    }

    public function resetFarmData(): void
    {
        $this->farm = new Farm();

        $this->farm_id = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newFarm(): void
    {
        $this->modalTitle = trans('crud.user_farms.new_title');
        $this->resetFarmData();

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

        $this->authorize('create', Farm::class);

        $this->user->farms()->attach($this->farm_id, []);

        $this->hideModal();
    }

    public function detach($farm): void
    {
        $this->authorize('delete-any', Farm::class);

        $this->user->farms()->detach($farm);

        $this->resetFarmData();
    }

    public function render(): View
    {
        return view('livewire.user-farms-detail', [
            'userFarms' => $this->user
                ->farms()
                ->withPivot([])
                ->paginate(20),
        ]);
    }
}
