<?php

namespace App\Http\Livewire;

use App\Models\Material;
use App\Models\InventoryMaterial;
use App\Models\Farm;
use App\Models\Market;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InventoryMaterials extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    use WithFileUploads;

    // Modal properties
    public $showingModal = false;
    public $showingViewModal = false;
    public $showingMaterialModal = false;
    public $modalTitle = 'Nueva Transacción';
    public $editing = false;
    
    // Form properties
    public $inventoryMaterial;
    public $materialId;
    public $quantity;
    public $cost;
    public $type = 'entrada';
    public $selectedMaterial = null;
    
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
    
    // Search and filters
    public $search = '';
    public $filterType = '';
    public $filterMaterialId = '';
    
    // Data for selects
    public $materialsForSelect = [];
    public $farmsForSelect = [];
    public $marketsForSelect = [];
    
    protected $rules = [
        'materialId' => ['required', 'exists:materials,id'],
        'quantity' => ['required', 'integer', 'min:1'],
        'cost' => ['nullable', 'numeric', 'min:0'],
        'type' => ['required', 'in:entrada,salida,ajuste'],
        'materialName' => ['required', 'max:255', 'string'],
        'materialDescription' => ['nullable', 'string'],
        'materialFarmId' => ['required', 'exists:farms,id'],
        'materialMarketId' => ['required', 'exists:markets,id'],
        'materialStatus' => ['required', 'boolean'],
        'materialImage' => ['image', 'max:5000', 'nullable'],
        'newMarketName' => ['required', 'max:255', 'string'],
        'newMarketPhone' => ['nullable', 'max:255', 'string'],
        'newMarketDirection' => ['nullable', 'max:255', 'string'],
    ];

    public function mount(): void
    {
        $this->authorize('view-any', InventoryMaterial::class);
        $this->loadSelectData();
    }

    public function loadSelectData(): void
    {
        $user = auth()->user();
        $farmIds = $user->farms->pluck('id');
        
        $this->materialsForSelect = Material::whereIn('farm_id', $farmIds)
            ->where('status', true)
            ->pluck('name', 'id')
            ->toArray();
        
        $this->farmsForSelect = $user->farms->pluck('name', 'id')->toArray();
        $this->marketsForSelect = Market::pluck('name', 'id')->toArray();
    }

    public function newTransaction($type = 'entrada'): void
    {
        $this->editing = false;
        $this->type = $type;
        $this->modalTitle = $type === 'entrada' ? 'Agregar Material' : ($type === 'salida' ? 'Consumir Material' : 'Ajustar Material');
        $this->resetForm();
        $this->loadSelectData();
        $this->showingModal = true;
    }

    public function viewTransaction($inventoryMaterialId): void
    {
        $this->inventoryMaterial = InventoryMaterial::with(['material', 'user'])->findOrFail($inventoryMaterialId);
        $this->authorize('view', $this->inventoryMaterial);
        $this->showingViewModal = true;
    }

    public function editTransaction($inventoryMaterialId): void
    {
        $this->editing = true;
        $this->inventoryMaterial = InventoryMaterial::findOrFail($inventoryMaterialId);
        $this->authorize('update', $this->inventoryMaterial);
        
        $this->materialId = $this->inventoryMaterial->material_id;
        $this->quantity = $this->inventoryMaterial->quantity;
        $this->cost = $this->inventoryMaterial->cost;
        $this->type = $this->inventoryMaterial->type;
        $this->modalTitle = 'Editar Transacción';
        
        $this->loadSelectData();
        $this->showingModal = true;
    }

    public function resetForm(): void
    {
        $this->materialId = null;
        $this->quantity = null;
        $this->cost = null;
        $this->inventoryMaterial = null;
        $this->selectedMaterial = null;
        $this->resetErrorBag();
    }

    public function saveTransaction(): void
    {
        $this->validate();

        // For salida, check if there's enough stock
        if ($this->type === 'salida') {
            $currentStock = $this->getCurrentStock($this->materialId);
            if ($currentStock < $this->quantity) {
                $this->addError('quantity', "Stock insuficiente. Stock actual: {$currentStock}");
                return;
            }
        }

        if ($this->editing) {
            $this->authorize('update', $this->inventoryMaterial);
        } else {
            $this->authorize('create', InventoryMaterial::class);
            $this->inventoryMaterial = new InventoryMaterial();
        }

        $this->inventoryMaterial->material_id = $this->materialId;
        $this->inventoryMaterial->quantity = $this->type === 'salida' ? -abs($this->quantity) : abs($this->quantity);
        $this->inventoryMaterial->cost = $this->cost;
        $this->inventoryMaterial->type = $this->type;
        $this->inventoryMaterial->user_id = auth()->id();
        $this->inventoryMaterial->status = true;

        $this->inventoryMaterial->save();
        
        $this->showingModal = false;
        $this->resetForm();
    }

    public function deleteTransaction($inventoryMaterialId): void
    {
        $inventoryMaterial = InventoryMaterial::findOrFail($inventoryMaterialId);
        $this->authorize('delete', $inventoryMaterial);
        $inventoryMaterial->delete();
    }

    public function getCurrentStock($materialId): int
    {
        return InventoryMaterial::where('material_id', $materialId)
            ->where('status', true)
            ->sum('quantity');
    }

    public function updatedMaterialId($value): void
    {
        if ($value) {
            $material = Material::find($value);
            if ($material) {
                $material->current_stock = $this->getCurrentStock($value);
                $this->selectedMaterial = $material;
            }
        } else {
            $this->selectedMaterial = null;
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterType(): void
    {
        $this->resetPage();
    }

    public function updatedFilterMaterialId(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->filterType = '';
        $this->filterMaterialId = '';
        $this->resetPage();
    }

    public function newMaterial(): void
    {
        $this->editing = false;
        $this->resetMaterialForm();
        $this->loadSelectData();
        $this->showingMaterialModal = true;
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
        ]);

        $this->authorize('create', Material::class);
        $this->material = new Material();

        $this->material->name = $this->materialName;
        $this->material->description = $this->materialDescription;
        $this->material->farm_id = $this->materialFarmId;
        $this->material->market_id = $this->materialMarketId;
        $this->material->status = $this->materialStatus;

        if ($this->materialImage) {
            // Compress and optimize image
            $compressedPath = \App\Classes\General::compressImage($this->materialImage, 'public/materials', 1200, 1200, 75);
            if ($compressedPath && file_exists(storage_path('app/' . $compressedPath))) {
                $this->material->image = $compressedPath;
            } else {
                // Fallback to original if compression fails
                $this->material->image = $this->materialImage->store('public/materials');
            }
        }

        $this->material->save();
        
        $this->uploadIteration++;
        $this->showingMaterialModal = false;
        $this->resetMaterialForm();
        $this->loadSelectData();
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
        $this->showingMaterialModal = false;
        $this->showingMarketModal = false;
        $this->resetForm();
        $this->resetMaterialForm();
    }

    public function render(): View
    {
        $user = auth()->user();
        $farmIds = $user->farms->pluck('id');
        
        // Get materials with their current stock
        $materials = Material::whereIn('farm_id', $farmIds)
            ->where('status', true)
            ->with(['inventoryMaterials' => function($query) {
                $query->where('status', true);
            }])
            ->get()
            ->map(function($material) {
                $material->current_stock = InventoryMaterial::where('material_id', $material->id)
                    ->where('status', true)
                    ->sum('quantity');
                return $material;
            });

        // Get inventory transactions
        $query = InventoryMaterial::with(['material.farm', 'user'])
            ->whereHas('material', function($q) use ($farmIds) {
                $q->whereIn('farm_id', $farmIds);
            })
            ->latest();

        if ($this->search) {
            $query->whereHas('material', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterMaterialId) {
            $query->where('material_id', $this->filterMaterialId);
        }

        $inventoryMaterials = $query->paginate(15);

        return view('livewire.inventory-materials', [
            'inventoryMaterials' => $inventoryMaterials,
            'materials' => $materials,
            'types' => InventoryMaterial::$types,
        ]);
    }
}
