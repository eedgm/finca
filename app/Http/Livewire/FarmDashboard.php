<?php

namespace App\Http\Livewire;

use App\Models\Cow;
use App\Models\Farm;
use App\Models\History;
use App\Models\CowType;
use App\Models\Medicine;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FarmDashboard extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    // Cow modal properties
    public $showingCowModal = false;
    public $showingViewCowModal = false;
    public $showingHistoryModal = false;
    public $cowModalTitle = 'Nueva Vaca';
    public $editingCow = false;
    
    // Cow form properties
    public $cow;
    public $cowNumber;
    public $cowName;
    public $cowGender = 'male';
    public $cowParentId;
    public $cowMotherId;
    public $cowFarmId;
    public $cowOwner;
    public $cowSold = false;
    public $cowPicture;
    public $cowBorn;
    public $uploadIteration = 0;
    
    // History form properties
    public $history;
    public $historyDate;
    public $historyWeight;
    public $historyCowTypeId;
    public $historyComments;
    public $historyPicture;
    public $selectedCowId;
    public $selectedMedicines = [];
    public $medicineCc = [];
    public $selectedMedicine = null;
    public $medicineTotalCc = []; // Store total available CC for each medicine
    
    // Gallery properties
    public $showingGallery = false;
    public $galleryImages = [];
    public $currentImageIndex = 0;
    
    // Filters modal
    public $showingFiltersModal = false;
    
    // Data for selects
    public $farmsForSelect = [];
    public $cowTypesForSelect = [];
    public $medicinesForSelect = [];
    public $fathersForSelect = [];
    public $mothersForSelect = [];
    
    // Search properties
    public $searchNumber = '';
    public $searchGender = '';
    public $searchHistory = '';
    
    protected $rules = [
        'cowNumber' => ['nullable', 'numeric'],
        'cowName' => ['nullable', 'max:255', 'string'],
        'cowGender' => ['required', 'in:male,female'],
        'cowParentId' => ['nullable', 'exists:cows,id'],
        'cowMotherId' => ['nullable', 'exists:cows,id'],
        'cowFarmId' => ['required', 'exists:farms,id'],
        'cowOwner' => ['nullable', 'max:255', 'string'],
        'cowSold' => ['required', 'boolean'],
        'cowPicture' => ['image', 'max:5000', 'nullable'],
        'cowBorn' => ['nullable', 'date'],
        'historyDate' => ['required', 'date'],
        'historyWeight' => ['nullable', 'numeric'],
        'historyCowTypeId' => ['nullable', 'exists:cow_types,id'],
        'historyComments' => ['nullable', 'max:255', 'string'],
        'historyPicture' => ['image', 'max:5000', 'nullable'],
        'selectedCowId' => ['required', 'exists:cows,id'],
    ];

    public function mount(): void
    {
        $this->authorize('view-any', Farm::class);
        $this->loadSelectData();
    }

    public function loadSelectData(): void
    {
        $user = auth()->user();
        $this->farmsForSelect = $user->farms->pluck('name', 'id')->toArray();
        // $this->cowTypesForSelect = CowType::pluck('name', 'id')->toArray();
        // Only load available medicines (total_cc > 0 and not discarded)
        $this->medicinesForSelect = Medicine::where('discarded', false)
            ->where(function($query) {
                $query->where('total_cc', '>', 0)
                      ->orWhereNull('total_cc');
            })
            ->pluck('name', 'id')
            ->toArray();
        $this->loadParentsAndMothers();
    }
    
    public function loadParentsAndMothers(): void
    {
        $user = auth()->user();
        $farmIds = $user->farms->pluck('id');
        
        // Get Toro type ID
        $toroType = CowType::where('name', 'Toro')->first();
        // Get Vaca type ID
        $vacaType = CowType::where('name', 'Vaca')->first();
        
        // Load fathers (cows with Toro in their latest history)
        $this->fathersForSelect = [];
        if ($toroType) {
            $fathers = Cow::whereIn('farm_id', $farmIds)
                ->with(['histories' => function($query) {
                    $query->orderBy('date', 'desc')->with('cowType')->limit(1);
                }])
                ->get()
                ->filter(function($cow) use ($toroType) {
                    $latestHistory = $cow->histories()->latest()->first();
                    return $latestHistory && $latestHistory->cow_type_id == $toroType->id;
                });
            
            foreach ($fathers as $father) {
                $label = ($father->number ? '#' . $father->number : '') . ($father->name ? ' - ' . $father->name : '');
                $this->fathersForSelect[$father->id] = $label ?: 'Vaca #' . $father->id;
            }
        }
        
        // Load mothers (cows with Vaca in their latest history)
        $this->mothersForSelect = [];
        if ($vacaType) {
            $mothers = Cow::whereIn('farm_id', $farmIds)
                ->with(['histories' => function($query) {
                    $query->orderBy('date', 'desc')->with('cowType')->limit(1);
                }])
                ->get()
                ->filter(function($cow) use ($vacaType) {
                    $latestHistory = $cow->histories()->latest()->first();
                    return $latestHistory && $latestHistory->cow_type_id == $vacaType->id;
                });
            
            foreach ($mothers as $mother) {
                $label = ($mother->number ? '#' . $mother->number : '') . ($mother->name ? ' - ' . $mother->name : '');
                $this->mothersForSelect[$mother->id] = $label ?: 'Vaca #' . $mother->id;
            }
        }
    }

    public function newCow(): void
    {
        $this->editingCow = false;
        $this->cowModalTitle = 'Nueva Vaca';
        $this->resetCowForm();
        $this->showingCowModal = true;
    }

    public function viewCow($cowId): void
    {
        $this->cow = Cow::with(['farm', 'histories.cowType', 'histories.medicines' => function($query) {
            $query->withPivot('cc');
        }])->findOrFail($cowId);
        $this->authorize('view', $this->cow);
        $this->showingViewCowModal = true;
    }

    public function editCow($cowId): void
    {
        $this->editingCow = true;
        $this->cowModalTitle = 'Editar Vaca';
        $this->cow = Cow::findOrFail($cowId);
        $this->authorize('update', $this->cow);
        
        $this->cowNumber = $this->cow->number;
        $this->cowName = $this->cow->name;
        $this->cowGender = $this->cow->gender;
        $this->cowParentId = $this->cow->parent_id;
        $this->cowMotherId = $this->cow->mother_id;
        $this->cowFarmId = $this->cow->farm_id;
        $this->cowOwner = $this->cow->owner;
        $this->cowSold = $this->cow->sold;
        $this->cowBorn = $this->cow->born ? $this->cow->born->format('Y-m-d') : null;
        
        $this->loadParentsAndMothers();
        $this->showingCowModal = true;
    }

    public function newHistory($cowId): void
    {
        $this->selectedCowId = $cowId;
        $this->resetHistoryForm();
        $cow = Cow::findOrFail($cowId);
        $this->cowTypesForSelect = CowType::where('gender', $cow->gender)->pluck('name', 'id')->toArray();
        // obtener el tipo de vaca del ultimo historial de la vaca
        $lastHistory = $cow->histories()->latest()->first();
        if ($lastHistory) {
            $this->historyCowTypeId = $lastHistory->cow_type_id;
            $this->historyWeight = $lastHistory->weight;
        } else {
            $this->historyCowTypeId = null;
            $this->historyWeight = null;
        }
        // Reload available medicines
        $this->loadSelectData();

        $this->historyDate = now()->format('Y-m-d');
        $this->selectedMedicines = [];
        $this->medicineCc = [];
        $this->medicineTotalCc = [];
        $this->showingHistoryModal = true;
    }
    
    public function getCowHistoriesProperty()
    {
        if (!$this->selectedCowId) {
            return collect();
        }
        
        return Cow::with(['histories.cowType', 'histories.medicines' => function($query) {
                $query->withPivot('cc');
            }])
            ->findOrFail($this->selectedCowId)
            ->histories()
            ->orderBy('id', 'desc')
            ->get();
    }

    public function resetCowForm(): void
    {
        $this->cowNumber = null;
        $this->cowName = null;
        $this->cowGender = 'male';
        $this->cowParentId = null;
        $this->cowMotherId = null;
        $this->cowFarmId = null;
        $this->cowOwner = null;
        $this->cowSold = false;
        $this->cowPicture = null;
        $this->cowBorn = null;
        $this->cow = null;
        $this->resetErrorBag();
        $this->uploadIteration++;
    }

    public function resetHistoryForm(): void
    {
        $this->historyDate = null;
        $this->historyWeight = null;
        $this->historyCowTypeId = null;
        $this->historyComments = null;
        $this->historyPicture = null;
        $this->history = null;
        $this->selectedMedicines = [];
        $this->medicineCc = [];
        $this->medicineTotalCc = [];
        $this->resetErrorBag();
    }
    
    public function openGallery($cowId): void
    {
        $cow = Cow::with(['histories'])->findOrFail($cowId);
        $this->galleryImages = [];
        
        // Add cow picture
        if ($cow->picture) {
            $this->galleryImages[] = [
                'type' => 'cow',
                'url' => Storage::url($cow->picture),
                'title' => 'Foto de la Vaca',
                'date' => $cow->created_at,
            ];
        }
        
        // Add history pictures
        foreach ($cow->histories as $history) {
            if ($history->picture) {
                $this->galleryImages[] = [
                    'type' => 'history',
                    'url' => Storage::url($history->picture),
                    'title' => 'Historial - ' . $history->date->format('d/m/Y'),
                    'date' => $history->date,
                ];
            }
        }
        
        $this->currentImageIndex = 0;
        $this->showingGallery = true;
    }
    
    public function nextImage(): void
    {
        if ($this->currentImageIndex < count($this->galleryImages) - 1) {
            $this->currentImageIndex++;
        }
    }
    
    public function previousImage(): void
    {
        if ($this->currentImageIndex > 0) {
            $this->currentImageIndex--;
        }
    }
    
    public function closeGallery(): void
    {
        $this->showingGallery = false;
        $this->galleryImages = [];
        $this->currentImageIndex = 0;
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

    public function saveCow(): void
    {
        $this->validate([
            'cowNumber' => ['nullable', 'numeric'],
            'cowName' => ['nullable', 'max:255', 'string'],
            'cowGender' => ['required', 'in:male,female'],
            'cowParentId' => ['nullable', 'max:255'],
            'cowMotherId' => ['nullable', 'max:255'],
            'cowFarmId' => ['required', 'exists:farms,id'],
            'cowOwner' => ['nullable', 'max:255', 'string'],
            'cowSold' => ['required', 'boolean'],
            'cowPicture' => ['image', 'max:5000', 'nullable'],
            'cowBorn' => ['nullable', 'date'],
        ]);

        if ($this->editingCow) {
            $this->authorize('update', $this->cow);
        } else {
            $this->authorize('create', Cow::class);
            $this->cow = new Cow();
        }

        $this->cow->number = $this->cowNumber;
        $this->cow->name = $this->cowName;
        $this->cow->gender = $this->cowGender;
        $this->cow->parent_id = $this->cowParentId;
        $this->cow->mother_id = $this->cowMotherId;
        $this->cow->farm_id = $this->cowFarmId;
        $this->cow->owner = $this->cowOwner;
        $this->cow->sold = $this->cowSold;
        $this->cow->born = $this->cowBorn ? \Carbon\Carbon::make($this->cowBorn) : null;

        if ($this->cowPicture) {
            if ($this->editingCow && $this->cow->picture) {
                Storage::delete($this->cow->picture);
            }
            
            // Compress and optimize image
            $compressedPath = $this->compressImage($this->cowPicture, 'public/cows', 1200, 1200, 75);
            if ($compressedPath) {
                $this->cow->picture = $compressedPath;
            } else {
                // Fallback to original if compression fails
                $this->cow->picture = $this->cowPicture->store('public/cows');
            }
        }

        $this->cow->save();
        $this->uploadIteration++;
        $this->showingCowModal = false;
        $this->resetCowForm();
    }

    public function saveHistory(): void
    {
        $this->validate([
            'historyDate' => ['required', 'date'],
            'historyWeight' => ['nullable', 'numeric'],
            'historyCowTypeId' => ['nullable', 'exists:cow_types,id'],
            'historyComments' => ['nullable', 'max:255', 'string'],
            'historyPicture' => ['image', 'max:5000', 'nullable'],
            'selectedCowId' => ['required', 'exists:cows,id'],
        ]);

        $this->authorize('create', History::class);

        $cow = Cow::findOrFail($this->selectedCowId);
        $this->authorize('view', $cow);

        $history = new History();
        $history->date = $this->historyDate;
        $history->weight = $this->historyWeight;
        $history->cow_type_id = $this->historyCowTypeId;
        $history->comments = $this->historyComments;

        if ($this->historyPicture) {
            // Compress and optimize image
            $compressedPath = $this->compressImage($this->historyPicture, 'public/histories', 1200, 1200, 75);
            if ($compressedPath) {
                $history->picture = $compressedPath;
            } else {
                // Fallback to original if compression fails
                $history->picture = $this->historyPicture->store('public/histories');
            }
        }

        $history->save();
        $history->cows()->attach($this->selectedCowId);
        
        // Attach medicines if selected and decrease inventory
        if (!empty($this->selectedMedicines)) {
            $medicinesData = [];
            foreach ($this->selectedMedicines as $medicineId) {
                $ccUsed = $this->medicineCc[$medicineId] ?? 0;
                
                // Validate that CC used doesn't exceed available
                $medicine = Medicine::findOrFail($medicineId);
                if ($medicine->total_cc !== null && $ccUsed > 0) {
                    $availableCc = $medicine->total_cc ?? 0;
                    if ($ccUsed > $availableCc) {
                        $this->addError('medicineCc.' . $medicineId, 
                            "La cantidad de CC ({$ccUsed}) excede el disponible ({$availableCc} cc) para {$medicine->name}.");
                        return;
                    }
                }
                
                if ($ccUsed > 0) {
                    $medicinesData[$medicineId] = [
                        'cc' => $ccUsed,
                    ];
                    
                    // Decrease total_cc from medicine inventory
                    if ($medicine->total_cc !== null) {
                        $newTotal = max(0, ($medicine->total_cc ?? 0) - $ccUsed);
                        $medicine->total_cc = $newTotal;
                        $medicine->save();
                    }
                }
            }
            if (!empty($medicinesData)) {
                $history->medicines()->attach($medicinesData);
            }
        }

        $this->showingHistoryModal = false;
        $this->resetHistoryForm();
    }
    
    public function addMedicineToHistory($medicineId): void
    {
        $medicine = Medicine::findOrFail($medicineId);
        
        // Check if medicine is available (not discarded and has total_cc > 0)
        if ($medicine->discarded || ($medicine->total_cc !== null && $medicine->total_cc <= 0)) {
            return; // Don't add if discarded or out of stock
        }
        
        if (!in_array($medicineId, $this->selectedMedicines)) {
            $this->selectedMedicines[] = $medicineId;
            $this->medicineCc[$medicineId] = null;
            // Store current total_cc for this medicine
            $this->medicineTotalCc[$medicineId] = $medicine->total_cc ?? 0;
        }
    }
    
    public function removeMedicineFromHistory($medicineId): void
    {
        $this->selectedMedicines = array_values(array_filter($this->selectedMedicines, fn($id) => $id != $medicineId));
        unset($this->medicineCc[$medicineId]);
        unset($this->medicineTotalCc[$medicineId]);
        $this->resetErrorBag('medicineCc.' . $medicineId);
    }
    
    public function updatedMedicineCc($value, $key): void
    {
        // Validate in real-time that CC doesn't exceed available
        // $key format: "medicineCc.123" where 123 is the medicine ID
        if ($value && $value > 0) {
            $medicineId = (int) str_replace('medicineCc.', '', $key);
            $medicine = Medicine::find($medicineId);
            if ($medicine && $medicine->total_cc !== null) {
                $availableCc = $medicine->total_cc ?? 0;
                if ($value > $availableCc) {
                    $this->addError('medicineCc.' . $medicineId, 
                        "La cantidad de CC ({$value}) excede el disponible ({$availableCc} cc).");
                } else {
                    $this->resetErrorBag('medicineCc.' . $medicineId);
                }
            }
        } else {
            // Clear error if value is empty or 0
            $medicineId = (int) str_replace('medicineCc.', '', $key);
            $this->resetErrorBag('medicineCc.' . $medicineId);
        }
    }

    public function closeModals(): void
    {
        $this->showingCowModal = false;
        $this->showingViewCowModal = false;
        $this->showingHistoryModal = false;
        $this->showingGallery = false;
        $this->showingFiltersModal = false;
        $this->resetCowForm();
        $this->resetHistoryForm();
    }
    
    public function openFiltersModal(): void
    {
        $this->showingFiltersModal = true;
    }
    
    public function closeFiltersModal(): void
    {
        $this->showingFiltersModal = false;
    }

    public function updatedSearchNumber(): void
    {
        // Trigger re-render when search changes
    }

    public function updatedSearchGender(): void
    {
        // Trigger re-render when search changes
    }

    public function updatedSearchHistory(): void
    {
        // Trigger re-render when search changes
    }

    public function clearSearch(): void
    {
        $this->searchNumber = '';
        $this->searchGender = '';
        $this->searchHistory = '';
    }

    public function render(): View
    {
        // Get user's farms
        $user = auth()->user();
        $farms = $user->farms;
        
        // Get all cows from user's farms, grouped by cow_type from last history
        $cowsByType = collect();
        
        if ($farms->isNotEmpty()) {
            $farmIds = $farms->pluck('id');
            $cows = Cow::whereIn('farm_id', $farmIds)
                ->with(['farm', 'histories' => function ($query) {
                    $query->orderBy('date', 'desc')->with(['cowType', 'medicines' => function($medQuery) {
                        $medQuery->withPivot('cc');
                    }]);
                }])
                ->get();
            
            // Apply filters
            if (!empty($this->searchNumber)) {
                $cows = $cows->filter(function ($cow) {
                    return $cow->number && stripos((string)$cow->number, $this->searchNumber) !== false;
                });
            }
            
            if (!empty($this->searchGender)) {
                $cows = $cows->filter(function ($cow) {
                    return $cow->gender === $this->searchGender;
                });
            }
            
            if (!empty($this->searchHistory)) {
                $cows = $cows->filter(function ($cow) {
                    // Search in history comments, cow type names, or dates
                    return $cow->histories->contains(function ($history) {
                        $searchLower = strtolower($this->searchHistory);
                        return 
                            ($history->comments && stripos(strtolower($history->comments), $searchLower) !== false) ||
                            ($history->cowType && stripos(strtolower($history->cowType->name), $searchLower) !== false) ||
                            ($history->date && stripos($history->date->format('d/m/Y'), $this->searchHistory) !== false);
                    });
                });
            }
            
            // Group cows by cow_type from last history
            $cowsByType = $cows->groupBy(function ($cow) {
                // Get the most recent history
                $lastHistory = $cow->histories()->latest()->first();
                
                if ($lastHistory && $lastHistory->cowType) {
                    return $lastHistory->cowType->name;
                }
                
                return 'Sin Tipo';
            });
        }
        
        return view('livewire.farm-dashboard', [
            'cowsByType' => $cowsByType,
        ]);
    }
}

