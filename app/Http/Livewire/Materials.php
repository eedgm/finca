<?php

namespace App\Http\Livewire;

use App\Models\Material;
use App\Models\Farm;
use App\Models\Market;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Materials extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    use WithFileUploads;

    // Modal properties
    public $showingModal = false;
    public $showingViewModal = false;
    public $modalTitle = 'Nuevo Material';
    public $editing = false;
    
    // Material form properties
    public $material;
    public $materialName;
    public $materialDescription;
    public $materialFarmId;
    public $materialMarketId;
    public $materialStatus = true;
    public $materialImage;
    public $uploadIteration = 0;
    
    // New market properties
    public $newMarketName = '';
    public $newMarketPhone = '';
    public $newMarketDirection = '';
    public $showingMarketModal = false;
    
    // Search
    public $search = '';
    
    // Data for selects
    public $farmsForSelect = [];
    public $marketsForSelect = [];
    public $materialCode = null;

    protected $rules = [
        'materialName' => ['required', 'max:255', 'string'],
        'materialDescription' => ['nullable', 'string'],
        'materialFarmId' => ['required', 'exists:farms,id'],
        'materialMarketId' => ['required', 'exists:markets,id'],
        'materialStatus' => ['required', 'boolean'],
        'materialImage' => ['image', 'max:5000', 'nullable'],
        'materialCode' => ['nullable', 'max:255', 'string'],
        'newMarketName' => ['required', 'max:255', 'string'],
        'newMarketPhone' => ['nullable', 'max:255', 'string'],
        'newMarketDirection' => ['nullable', 'max:255', 'string'],
    ];

    public function mount(): void
    {
        $this->authorize('view-any', Material::class);
        $this->loadSelectData();
    }

    public function loadSelectData(): void
    {
        $user = auth()->user();
        $this->farmsForSelect = $user->farms->pluck('name', 'id')->toArray();
        $this->marketsForSelect = Market::pluck('name', 'id')->toArray();
    }

    public function newMaterial(): void
    {
        $this->editing = false;
        $this->modalTitle = 'Nuevo Material';
        $this->resetMaterialForm();
        $this->loadSelectData();
        $this->showingModal = true;
    }

    public function viewMaterial($materialId): void
    {
        $this->material = Material::with(['farm', 'market'])->findOrFail($materialId);
        $this->authorize('view', $this->material);
        $this->showingViewModal = true;
    }

    public function editMaterial($materialId): void
    {
        $this->editing = true;
        $this->modalTitle = 'Editar Material';
        $this->material = Material::findOrFail($materialId);
        $this->authorize('update', $this->material);
        
        $this->materialName = $this->material->name;
        $this->materialDescription = $this->material->description;
        $this->materialFarmId = $this->material->farm_id;
        $this->materialMarketId = $this->material->market_id;
        $this->materialStatus = $this->material->status ?? true;
        $this->materialCode = $this->material->code;
        $this->loadSelectData();
        $this->showingModal = true;
    }

    public function resetMaterialForm(): void
    {
        $this->materialName = null;
        $this->materialDescription = null;
        $this->materialFarmId = null;
        $this->materialMarketId = null;
        $this->materialStatus = true;
        $this->materialImage = null;
        $this->material = null;
        $this->materialCode = null;
        $this->resetErrorBag();
        $this->uploadIteration++;
    }

    public function saveMaterial(): void
    {
        $this->validate([
            'materialName' => ['required', 'max:255', 'string'],
            'materialDescription' => ['nullable', 'string'],
            'materialFarmId' => ['required', 'exists:farms,id'],
            'materialMarketId' => ['required', 'exists:markets,id'],
            'materialStatus' => ['required', 'boolean'],
            'materialImage' => ['image', 'max:5000', 'nullable'],
            'materialCode' => ['nullable', 'max:255', 'string'],
        ]);

        if ($this->editing) {
            $this->authorize('update', $this->material);
        } else {
            $this->authorize('create', Material::class);
            $this->material = new Material();
        }

        $this->material->name = $this->materialName;
        $this->material->description = $this->materialDescription;
        $this->material->farm_id = $this->materialFarmId;
        $this->material->market_id = $this->materialMarketId;
        $this->material->status = $this->materialStatus;
        $this->material->code = $this->materialCode;
        if ($this->materialImage) {
            if ($this->editing && $this->material->image) {
                Storage::delete($this->material->image);
            }
            $this->material->image = $this->materialImage->store('public/materials');
        }

        $this->material->save();
        $this->uploadIteration++;
        $this->showingModal = false;
        $this->resetMaterialForm();
        $this->loadSelectData();
    }

    public function deleteMaterial($materialId): void
    {
        $material = Material::findOrFail($materialId);
        $this->authorize('delete', $material);
        
        if ($material->image) {
            Storage::delete($material->image);
        }
        
        $material->delete();
    }

    public function newMarket(): void
    {
        $this->newMarketName = '';
        $this->newMarketPhone = '';
        $this->newMarketDirection = '';
        $this->showingMarketModal = true;
    }

    public function saveMarket(): void
    {
        $this->validate([
            'newMarketName' => ['required', 'max:255', 'string'],
            'newMarketPhone' => ['nullable', 'max:255', 'string'],
            'newMarketDirection' => ['nullable', 'max:255', 'string'],
        ]);

        $this->authorize('create', Market::class);

        // Check if market already exists
        $market = Market::firstOrCreate(
            ['name' => $this->newMarketName],
            [
                'phone' => $this->newMarketPhone,
                'direction' => $this->newMarketDirection,
            ]
        );

        $this->materialMarketId = $market->id;
        $this->showingMarketModal = false;
        $this->newMarketName = '';
        $this->newMarketPhone = '';
        $this->newMarketDirection = '';
        $this->loadSelectData();
    }

    public function closeModals(): void
    {
        $this->showingModal = false;
        $this->showingViewModal = false;
        $this->showingMarketModal = false;
        $this->resetMaterialForm();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $user = auth()->user();
        $farmIds = $user->farms->pluck('id');
        
        $query = Material::with(['farm', 'market'])
            ->whereIn('farm_id', $farmIds)
            ->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        $materials = $query->paginate(10);

        return view('livewire.materials', [
            'materials' => $materials,
        ]);
    }
}

