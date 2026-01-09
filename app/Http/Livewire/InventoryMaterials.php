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
            $compressedPath = $this->compressImage($this->materialImage, 'public/materials', 1200, 1200, 75);
            if ($compressedPath) {
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

    /**
     * Compress and optimize image
     * Returns the storage path or null if compression fails
     */
    private function compressImage($imageFile, $directory = 'public', $maxWidth = 1200, $maxHeight = 1200, $quality = 75): ?string
    {
        $imagePath = $imageFile->getRealPath();
        $imageInfo = getimagesize($imagePath);
        
        if (!$imageInfo) {
            return null;
        }
        
        list($width, $height, $imageType) = $imageInfo;
        
        // Only resize if image is larger than max dimensions
        $needsResize = $width > $maxWidth || $height > $maxHeight;
        
        if ($needsResize) {
            // Calculate new dimensions maintaining aspect ratio
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }
        
        // Create image resource based on type
        $source = null;
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $source = @imagecreatefromjpeg($imagePath);
                break;
            case IMAGETYPE_PNG:
                $source = @imagecreatefrompng($imagePath);
                break;
            case IMAGETYPE_GIF:
                $source = @imagecreatefromgif($imagePath);
                break;
            case IMAGETYPE_WEBP:
                if (function_exists('imagecreatefromwebp')) {
                    $source = @imagecreatefromwebp($imagePath);
                }
                break;
        }
        
        if (!$source) {
            return null;
        }
        
        // Create optimized image
        $optimized = imagecreatetruecolor($newWidth, $newHeight);
        
        // Enable better quality resampling
        imagealphablending($optimized, false);
        imagesavealpha($optimized, true);
        
        // Preserve transparency for PNG and GIF
        if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
            $transparent = imagecolorallocatealpha($optimized, 255, 255, 255, 127);
            imagefilledrectangle($optimized, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        // Use better resampling algorithm
        if ($needsResize) {
            imagecopyresampled($optimized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        } else {
            imagecopy($optimized, $source, 0, 0, 0, 0, $width, $height);
        }
        
        // Save optimized image as JPEG (best compression)
        $filename = uniqid() . '.jpg';
        $path = $directory . '/' . $filename;
        $fullPath = storage_path('app/' . $path);
        
        // Create directory if it doesn't exist
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        // Try progressive JPEG for better compression
        imageinterlace($optimized, 1);
        
        // Save with optimized quality
        $saved = imagejpeg($optimized, $fullPath, $quality);
        
        // Clean up
        imagedestroy($source);
        imagedestroy($optimized);
        
        if (!$saved) {
            return null;
        }
        
        // Try to further optimize file size by re-reading and re-saving if still too large
        $fileSize = filesize($fullPath);
        $maxFileSize = 500 * 1024; // 500KB target
        
        if ($fileSize > $maxFileSize && $quality > 60) {
            // Try with lower quality
            $lowerQuality = max(60, $quality - 10);
            $source2 = @imagecreatefromjpeg($fullPath);
            if ($source2) {
                imageinterlace($source2, 1);
                imagejpeg($source2, $fullPath, $lowerQuality);
                imagedestroy($source2);
            }
        }
        
        return $path;
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
