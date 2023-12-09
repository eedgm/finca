<?php

namespace App\Http\Livewire;

use App\Models\Cow;
use App\Models\Farm;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FarmCowsDetail extends Component
{
    use WithPagination;
    use WithFileUploads;
    use AuthorizesRequests;

    public Farm $farm;
    public Cow $cow;
    public $cowPicture;
    public $uploadIteration = 0;
    public $cowBorn;

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Cow';

    protected $rules = [
        'cow.number' => ['nullable', 'numeric'],
        'cow.name' => ['nullable', 'max:255', 'string'],
        'cow.gender' => ['required', 'in:male,female'],
        'cow.parent_id' => ['nullable', 'max:255'],
        'cow.mother_id' => ['nullable', 'max:255'],
        'cow.owner' => ['nullable', 'max:255', 'string'],
        'cowPicture' => ['image', 'max:1024', 'nullable'],
        'cow.sold' => ['required', 'boolean'],
        'cowBorn' => ['nullable', 'date'],
    ];

    public function mount(Farm $farm): void
    {
        $this->farm = $farm;
        $this->resetCowData();
    }

    public function resetCowData(): void
    {
        $this->cow = new Cow();

        $this->cowPicture = null;
        $this->cowBorn = null;
        $this->cow->gender = 'male';

        $this->dispatchBrowserEvent('refresh');
    }

    public function newCow(): void
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.farm_cows.new_title');
        $this->resetCowData();

        $this->showModal();
    }

    public function editCow(Cow $cow): void
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.farm_cows.edit_title');
        $this->cow = $cow;

        $this->cowBorn = optional($this->cow->born)->format('Y-m-d');

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

        if (!$this->cow->farm_id) {
            $this->authorize('create', Cow::class);

            $this->cow->farm_id = $this->farm->id;
        } else {
            $this->authorize('update', $this->cow);
        }

        if ($this->cowPicture) {
            $this->cow->picture = $this->cowPicture->store('public');
        }

        $this->cow->born = \Carbon\Carbon::make($this->cowBorn);

        $this->cow->save();

        $this->uploadIteration++;

        $this->hideModal();
    }

    public function destroySelected(): void
    {
        $this->authorize('delete-any', Cow::class);

        collect($this->selected)->each(function (string $id) {
            $cow = Cow::findOrFail($id);

            if ($cow->picture) {
                Storage::delete($cow->picture);
            }

            $cow->delete();
        });

        $this->selected = [];
        $this->allSelected = false;

        $this->resetCowData();
    }

    public function toggleFullSelection(): void
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->farm->cows as $cow) {
            array_push($this->selected, $cow->id);
        }
    }

    public function render(): View
    {
        return view('livewire.farm-cows-detail', [
            'cows' => $this->farm->cows()->paginate(20),
        ]);
    }
}
