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
    
    // Gallery properties
    public $showingGallery = false;
    public $galleryImages = [];
    public $currentImageIndex = 0;
    
    // Data for selects
    public $farmsForSelect = [];
    public $cowTypesForSelect = [];
    public $medicinesForSelect = [];
    
    // Search properties
    public $searchNumber = '';
    public $searchGender = '';
    public $searchHistory = '';
    
    protected $rules = [
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
        $this->cowTypesForSelect = CowType::pluck('name', 'id')->toArray();
        $this->medicinesForSelect = Medicine::pluck('name', 'id')->toArray();
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
        $this->cow = Cow::with(['farm', 'histories.cowType', 'histories.medicines'])->findOrFail($cowId);
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
        
        $this->showingCowModal = true;
    }

    public function newHistory($cowId): void
    {
        $this->selectedCowId = $cowId;
        $this->resetHistoryForm();
        $this->historyDate = now()->format('Y-m-d');
        $this->selectedMedicines = [];
        $this->medicineCc = [];
        $this->showingHistoryModal = true;
    }
    
    public function getCowHistoriesProperty()
    {
        if (!$this->selectedCowId) {
            return collect();
        }
        
        return Cow::with(['histories.cowType', 'histories.medicines'])
            ->findOrFail($this->selectedCowId)
            ->histories()
            ->orderBy('date', 'desc')
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
            $this->cow->picture = $this->cowPicture->store('public');
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
            // Optimize and resize image using GD
            $imagePath = $this->historyPicture->getRealPath();
            $imageInfo = getimagesize($imagePath);
            
            if ($imageInfo) {
                $maxWidth = 1200;
                $maxHeight = 1200;
                list($width, $height) = $imageInfo;
                
                // Calculate new dimensions maintaining aspect ratio
                $ratio = min($maxWidth / $width, $maxHeight / $height);
                $newWidth = (int)($width * $ratio);
                $newHeight = (int)($height * $ratio);
                
                // Create image resource based on type
                $source = null;
                switch ($imageInfo[2]) {
                    case IMAGETYPE_JPEG:
                        $source = imagecreatefromjpeg($imagePath);
                        break;
                    case IMAGETYPE_PNG:
                        $source = imagecreatefrompng($imagePath);
                        break;
                    case IMAGETYPE_GIF:
                        $source = imagecreatefromgif($imagePath);
                        break;
                }
                
                if ($source) {
                    // Create new image with optimized size
                    $optimized = imagecreatetruecolor($newWidth, $newHeight);
                    
                    // Preserve transparency for PNG
                    if ($imageInfo[2] == IMAGETYPE_PNG) {
                        imagealphablending($optimized, false);
                        imagesavealpha($optimized, true);
                        $transparent = imagecolorallocatealpha($optimized, 255, 255, 255, 127);
                        imagefilledrectangle($optimized, 0, 0, $newWidth, $newHeight, $transparent);
                    }
                    
                    imagecopyresampled($optimized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    
                    // Save optimized image
                    $path = 'public/histories/' . uniqid() . '.jpg';
                    $fullPath = storage_path('app/' . $path);
                    
                    // Create directory if it doesn't exist
                    $dir = dirname($fullPath);
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                    
                    imagejpeg($optimized, $fullPath, 85);
                    imagedestroy($source);
                    imagedestroy($optimized);
                    
                    $history->picture = $path;
                } else {
                    // Fallback to original if optimization fails
                    $history->picture = $this->historyPicture->store('public/histories');
                }
            } else {
                // Fallback to original if getimagesize fails
                $history->picture = $this->historyPicture->store('public/histories');
            }
        }

        $history->save();
        $history->cows()->attach($this->selectedCowId);
        
        // Attach medicines if selected
        if (!empty($this->selectedMedicines)) {
            $medicinesData = [];
            foreach ($this->selectedMedicines as $medicineId) {
                $medicinesData[$medicineId] = [
                    'cc' => $this->medicineCc[$medicineId] ?? null,
                ];
            }
            $history->medicines()->attach($medicinesData);
        }

        $this->showingHistoryModal = false;
        $this->resetHistoryForm();
    }
    
    public function addMedicineToHistory($medicineId): void
    {
        if (!in_array($medicineId, $this->selectedMedicines)) {
            $this->selectedMedicines[] = $medicineId;
            $this->medicineCc[$medicineId] = null;
        }
    }
    
    public function removeMedicineFromHistory($medicineId): void
    {
        $this->selectedMedicines = array_values(array_filter($this->selectedMedicines, fn($id) => $id != $medicineId));
        unset($this->medicineCc[$medicineId]);
    }

    public function closeModals(): void
    {
        $this->showingCowModal = false;
        $this->showingViewCowModal = false;
        $this->showingHistoryModal = false;
        $this->showingGallery = false;
        $this->resetCowForm();
        $this->resetHistoryForm();
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
                    $query->orderBy('date', 'desc')->with(['cowType', 'medicines']);
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
                $lastHistory = $cow->histories->first();
                
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

