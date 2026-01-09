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
            
            // Compress and optimize image
            $compressedPath = $this->compressImage($this->materialImage, 'public/materials', 1200, 1200, 75);
            if ($compressedPath && file_exists(storage_path('app/' . $compressedPath))) {
                $this->material->image = $compressedPath;
            } else {
                // Fallback to original if compression fails
                $this->material->image = $this->materialImage->store('public/materials');
            }
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

    /**
     * Compress and optimize image
     * Returns the storage path or null if compression fails
     */
    private function compressImage($imageFile, $directory = 'public', $maxWidth = 1200, $maxHeight = 1200, $quality = 75): ?string
    {
        try {
            $imagePath = $imageFile->getRealPath();
            
            if (!$imagePath || !file_exists($imagePath)) {
                return null;
            }
            
            $imageInfo = getimagesize($imagePath);
            
            if (!$imageInfo) {
                return null;
            }
        } catch (\Exception $e) {
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

