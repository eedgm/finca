<?php

namespace App\Http\Livewire;

use App\Models\Farm;
use App\Models\User;
use Livewire\Component;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FarmUsersDetail extends Component
{
    use AuthorizesRequests;

    public Farm $farm;
    public User $user;
    public $usersForSelect = [];
    public $user_id = null;

    public $showingModal = false;
    public $modalTitle = 'New User';

    protected $rules = [
        'user_id' => ['required', 'exists:users,id'],
    ];

    public function mount(Farm $farm): void
    {
        $this->farm = $farm;
        $this->usersForSelect = User::pluck('name', 'id');
        $this->resetUserData();
    }

    public function resetUserData(): void
    {
        $this->user = new User();

        $this->user_id = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newUser(): void
    {
        $this->modalTitle = trans('crud.farm_users.new_title');
        $this->resetUserData();

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

        $this->authorize('create', User::class);

        $this->farm->users()->attach($this->user_id, []);

        $this->hideModal();
    }

    public function detach($user): void
    {
        $this->authorize('delete-any', User::class);

        $this->farm->users()->detach($user);

        $this->resetUserData();
    }

    public function render(): View
    {
        return view('livewire.farm-users-detail', [
            'farmUsers' => $this->farm
                ->users()
                ->withPivot([])
                ->paginate(20),
        ]);
    }
}
