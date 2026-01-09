<?php

namespace App\Http\Livewire;

use App\Models\Medicine;
use App\Models\Manufacturer;
use App\Models\Market;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MedicineIndex extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    use WithFileUploads;

    // Modal properties
    public $showingModal = false;
    public $showingViewModal = false;
    public $showingHistoriesModal = false;
    public $modalTitle = 'Nueva Medicina';
    public $editing = false;
    public $selectedMedicineForHistories = null;
    
    // Medicine form properties
    public $medicine;
    public $medicineName;
    public $medicineManufacturerId;
    public $medicineExpirationDate;
    public $medicineCode;
    public $medicineCc;
    public $medicineTotalCc;
    public $medicineCost;
    public $medicineMarketId;
    public $medicineDiscarded = false;
    public $medicinePicture;
    public $uploadIteration = 0;
    
    // New manufacturer/market properties
    public $newManufacturerName = '';
    public $newMarketName = '';
    public $newMarketPhone = '';
    public $newMarketDirection = '';
    public $showingManufacturerModal = false;
    public $showingMarketModal = false;
    public $exhaustedMedicines = [];
    // Search
    public $search = '';
    
    // Data for selects
    public $manufacturersForSelect = [];
    public $marketsForSelect = [];
    
    protected $rules = [
        'medicineName' => ['required', 'max:255', 'string'],
        'medicineManufacturerId' => ['required', 'exists:manufacturers,id'],
        'medicineExpirationDate' => ['nullable', 'date'],
        'medicineCode' => ['nullable', 'max:255', 'string'],
        'medicineCc' => ['nullable', 'numeric'],
        'medicineTotalCc' => ['nullable', 'numeric'],
        'medicineCost' => ['nullable', 'numeric'],
        'medicineDiscarded' => ['nullable', 'boolean'],
        'medicineMarketId' => ['required', 'exists:markets,id'],
        'medicinePicture' => ['image', 'max:5000', 'nullable'],
        'newManufacturerName' => ['required', 'max:255', 'string'],
        'newMarketName' => ['required', 'max:255', 'string'],
        'newMarketPhone' => ['nullable', 'max:255', 'string'],
        'newMarketDirection' => ['nullable', 'max:255', 'string'],
    ];

    public function mount(): void
    {
        $this->authorize('view-any', Medicine::class);
        $this->loadSelectData();

        $this->exhaustedMedicines = Medicine::where(function($query) {
            $query->where('discarded', true)
                  ->orWhere(function($q) {
                      $q->whereNotNull('total_cc')
                        ->where('total_cc', '<=', 0);
                  });
        })->get();
    }

    public function loadSelectData(): void
    {
        $this->manufacturersForSelect = Manufacturer::pluck('name', 'id')->toArray();
        $this->marketsForSelect = Market::pluck('name', 'id')->toArray();
    }

    public function newMedicine(): void
    {
        $this->editing = false;
        $this->modalTitle = 'Nueva Medicina';
        $this->resetMedicineForm();
        $this->showingModal = true;
    }

    public function viewMedicine($medicineId): void
    {
        $this->medicine = Medicine::with(['manufacturer', 'market'])->findOrFail($medicineId);
        $this->authorize('view', $this->medicine);
        $this->showingViewModal = true;
    }

    public function editMedicine($medicineId): void
    {
        $this->editing = true;
        $this->modalTitle = 'Editar Medicina';
        $this->medicine = Medicine::findOrFail($medicineId);
        $this->authorize('update', $this->medicine);
        
        $this->medicineName = $this->medicine->name;
        $this->medicineManufacturerId = $this->medicine->manufacturer_id;
        $this->medicineExpirationDate = $this->medicine->expiration_date ? $this->medicine->expiration_date->format('Y-m-d') : null;
        $this->medicineCode = $this->medicine->code;
        $this->medicineCc = $this->medicine->cc;
        $this->medicineTotalCc = $this->medicine->total_cc;
        $this->medicineCost = $this->medicine->cost;
        $this->medicineMarketId = $this->medicine->market_id;
        $this->medicineDiscarded = $this->medicine->discarded ?? false;
        
        $this->showingModal = true;
    }

    public function resetMedicineForm(): void
    {
        $this->medicineName = null;
        $this->medicineManufacturerId = null;
        $this->medicineExpirationDate = null;
        $this->medicineCode = null;
        $this->medicineCc = null;
        $this->medicineTotalCc = null;
        $this->medicineCost = null;
        $this->medicineMarketId = null;
        $this->medicineDiscarded = false;
        $this->medicinePicture = null;
        $this->medicine = null;
        $this->resetErrorBag();
        $this->uploadIteration++;
    }

    public function saveMedicine(): void
    {
        $this->validate([
            'medicineName' => ['required', 'max:255', 'string'],
            'medicineManufacturerId' => ['required', 'exists:manufacturers,id'],
            'medicineExpirationDate' => ['nullable', 'date'],
            'medicineCode' => ['nullable', 'max:255', 'string'],
            'medicineCc' => ['nullable', 'numeric'],
            'medicineCost' => ['nullable', 'numeric'],
            'medicineMarketId' => ['required', 'exists:markets,id'],
            'medicinePicture' => ['image', 'max:5000', 'nullable'],
        ]);

        if ($this->editing) {
            $this->authorize('update', $this->medicine);
        } else {
            $this->authorize('create', Medicine::class);
            $this->medicine = new Medicine();
        }

        $this->medicine->name = $this->medicineName;
        $this->medicine->manufacturer_id = $this->medicineManufacturerId;
        $this->medicine->expiration_date = $this->medicineExpirationDate ? \Carbon\Carbon::make($this->medicineExpirationDate) : null;
        $this->medicine->code = $this->medicineCode;
        $this->medicine->cc = $this->medicineCc;
        $this->medicine->total_cc = $this->medicineTotalCc;
        $this->medicine->cost = $this->medicineCost;
        $this->medicine->market_id = $this->medicineMarketId;
        $this->medicine->discarded = $this->medicineDiscarded;

        if ($this->medicinePicture) {
            if ($this->editing && $this->medicine->picture) {
                Storage::delete($this->medicine->picture);
            }
            
            // Compress and optimize image
            $compressedPath = $this->compressImage($this->medicinePicture, 'public', 1200, 1200, 75);
            if ($compressedPath) {
                $this->medicine->picture = $compressedPath;
            } else {
                // Fallback to original if compression fails
                $this->medicine->picture = $this->medicinePicture->store('public');
            }
        }

        $this->medicine->save();
        $this->uploadIteration++;
        $this->showingModal = false;
        $this->resetMedicineForm();
        $this->loadSelectData();
    }

    public function deleteMedicine($medicineId): void
    {
        $medicine = Medicine::findOrFail($medicineId);
        $this->authorize('delete', $medicine);
        
        if ($medicine->picture) {
            Storage::delete($medicine->picture);
        }
        
        $medicine->delete();
    }

    public function newManufacturer(): void
    {
        $this->newManufacturerName = '';
        $this->showingManufacturerModal = true;
    }

    public function saveManufacturer(): void
    {
        $this->validate([
            'newManufacturerName' => ['required', 'max:255', 'string'],
        ]);

        $this->authorize('create', Manufacturer::class);

        // Check if manufacturer already exists
        $manufacturer = Manufacturer::firstOrCreate(
            ['name' => $this->newManufacturerName]
        );

        $this->medicineManufacturerId = $manufacturer->id;
        $this->showingManufacturerModal = false;
        $this->newManufacturerName = '';
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

        // Check if market already exists
        $market = Market::firstOrCreate(
            ['name' => $this->newMarketName],
            [
                'phone' => $this->newMarketPhone,
                'direction' => $this->newMarketDirection,
            ]
        );

        $this->medicineMarketId = $market->id;
        $this->showingMarketModal = false;
        $this->newMarketName = '';
        $this->newMarketPhone = '';
        $this->newMarketDirection = '';
        $this->loadSelectData();
    }

    public function viewHistories($medicineId): void
    {
        $this->selectedMedicineForHistories = Medicine::with(['histories' => function($query) use ($medicineId) {
            $query->orderBy('date', 'desc')
                  ->with(['cowType', 'cows'])
                  ->withPivot('cc');
        }])->findOrFail($medicineId);
        
        $this->authorize('view', $this->selectedMedicineForHistories);
        $this->showingHistoriesModal = true;
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
        $this->showingHistoriesModal = false;
        $this->showingManufacturerModal = false;
        $this->showingMarketModal = false;
        $this->selectedMedicineForHistories = null;
        $this->resetMedicineForm();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $query = Medicine::with(['manufacturer', 'market'])
            ->latest();

        if ($this->search) {
            $query->search($this->search);
        }

        $medicines = $query->paginate(10);

        return view('livewire.medicine-index', [
            'medicines' => $medicines,
        ]);
    }
}

