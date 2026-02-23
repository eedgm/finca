<?php

namespace App\Http\Livewire;

use App\Models\Sale;
use App\Models\SaleAnimal;
use App\Models\Farm;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AnimalSales extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $showingForm = false;
    public $showingViewModal = false;
    /** @var int|null ID de la venta al editar; null = nueva venta */
    public $editingSaleId = null;
    public $saleDate;
    public $farmId = '';
    public $notes = '';
    /** @var array<int, array{id?: int, weight_kg: string, price_per_kg_usd: string, description: string}> */
    public $animals = [];
    public $saleToView = null;
    public $farmsForSelect = [];

    protected function rules(): array
    {
        return [
            'saleDate' => ['required', 'date'],
            'farmId' => ['nullable', 'exists:farms,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'animals' => ['required', 'array', 'min:1'],
            'animals.*.weight_kg' => ['nullable', 'numeric', 'min:0'],
            'animals.*.price_per_kg_usd' => ['nullable', 'numeric', 'min:0'],
            'animals.*.description' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'saleDate' => 'fecha de venta',
            'animals.*.weight_kg' => 'peso (kg)',
            'animals.*.price_per_kg_usd' => 'precio por kg (USD)',
        ];
    }

    public function mount(): void
    {
        $this->authorize('viewAny', Sale::class);
        $this->saleDate = now()->format('Y-m-d');
        $this->loadFarms();
    }

    public function loadFarms(): void
    {
        $user = auth()->user();
        $this->farmsForSelect = ['' => '-- Sin finca --'] + $user->farms->pluck('name', 'id')->toArray();
    }

    public function openForm(): void
    {
        $this->authorize('create', Sale::class);
        $this->editingSaleId = null;
        $this->showingForm = true;
        $this->saleDate = now()->format('Y-m-d');
        $this->farmId = '';
        $this->notes = '';
        $this->animals = [
            ['weight_kg' => '', 'price_per_kg_usd' => '', 'description' => ''],
        ];
        $this->resetValidation();
    }

    public function openEditForm(int $saleId): void
    {
        $sale = Sale::with('saleAnimals')->findOrFail($saleId);
        $this->authorize('update', $sale);
        $this->editingSaleId = $sale->id;
        $this->showingForm = true;
        $this->showingViewModal = false;
        $this->saleDate = $sale->sale_date->format('Y-m-d');
        $this->farmId = (string) ($sale->farm_id ?? '');
        $this->notes = (string) ($sale->notes ?? '');
        $this->animals = $sale->saleAnimals->map(fn (SaleAnimal $a) => [
            'id' => $a->id,
            'weight_kg' => $a->weight_kg !== null ? (string) $a->weight_kg : '',
            'price_per_kg_usd' => $a->price_per_kg_usd !== null ? (string) $a->price_per_kg_usd : '',
            'description' => (string) ($a->description ?? ''),
        ])->values()->all();
        if (empty($this->animals)) {
            $this->animals = [['weight_kg' => '', 'price_per_kg_usd' => '', 'description' => '']];
        }
        $this->resetValidation();
    }

    public function closeForm(): void
    {
        $this->showingForm = false;
        $this->editingSaleId = null;
        $this->resetValidation();
    }

    public function addAnimal(): void
    {
        $this->animals[] = ['weight_kg' => '', 'price_per_kg_usd' => '', 'description' => ''];
    }

    public function removeAnimal(int $index): void
    {
        if (count($this->animals) > 1) {
            array_splice($this->animals, $index, 1);
        }
    }

    public function getSubtotalProperty(): float
    {
        $sum = 0;
        foreach ($this->animals as $row) {
            $w = (float) ($row['weight_kg'] ?? 0);
            $p = (float) ($row['price_per_kg_usd'] ?? 0);
            $sum += $w * $p;
        }
        return round($sum, 2);
    }

    public function getTaxPercentProperty(): int
    {
        return Sale::TAX_PERCENT;
    }

    public function getTaxAmountProperty(): float
    {
        return round($this->subtotal * (Sale::TAX_PERCENT / 100), 2);
    }

    public function getTotalProperty(): float
    {
        return round($this->subtotal - $this->taxAmount, 2);
    }

    public function getKgToLbsProperty(): float
    {
        return Sale::KG_TO_LBS;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingSaleId) {
            $sale = Sale::findOrFail($this->editingSaleId);
            $this->authorize('update', $sale);
            $sale->update([
                'farm_id' => $this->farmId ?: null,
                'sale_date' => $this->saleDate,
                'notes' => $this->notes ?: null,
            ]);
            $keepIds = [];
            foreach ($this->animals as $i => $row) {
                $weight = $this->normalizeDecimal($row['weight_kg'] ?? '');
                $price = $this->normalizeDecimal($row['price_per_kg_usd'] ?? '');
                $description = trim($row['description'] ?? '') ?: null;
                if (!empty($row['id'] ?? null)) {
                    $animal = SaleAnimal::where('sale_id', $sale->id)->findOrFail($row['id']);
                    $animal->update([
                        'weight_kg' => $weight,
                        'price_per_kg_usd' => $price,
                        'description' => $description,
                        'sort_order' => $i,
                    ]);
                    $keepIds[] = $animal->id;
                } else {
                    $animal = SaleAnimal::create([
                        'sale_id' => $sale->id,
                        'weight_kg' => $weight,
                        'price_per_kg_usd' => $price,
                        'description' => $description,
                        'sort_order' => $i,
                    ]);
                    $keepIds[] = $animal->id;
                }
            }
            SaleAnimal::where('sale_id', $sale->id)->whereNotIn('id', $keepIds)->delete();
            session()->flash('message', 'Venta actualizada correctamente.');
        } else {
            $this->authorize('create', Sale::class);
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'farm_id' => $this->farmId ?: null,
                'sale_date' => $this->saleDate,
                'notes' => $this->notes ?: null,
            ]);
            foreach ($this->animals as $i => $row) {
                SaleAnimal::create([
                    'sale_id' => $sale->id,
                    'weight_kg' => $this->normalizeDecimal($row['weight_kg'] ?? ''),
                    'price_per_kg_usd' => $this->normalizeDecimal($row['price_per_kg_usd'] ?? ''),
                    'description' => trim($row['description'] ?? '') ?: null,
                    'sort_order' => $i,
                ]);
            }
            session()->flash('message', 'Venta registrada correctamente.');
        }

        $this->closeForm();
    }

    private function normalizeDecimal(string $value): ?float
    {
        $value = trim($value);
        if ($value === '' || $value === null) {
            return null;
        }
        return (float) $value;
    }

    public function viewSale(int $id): void
    {
        $this->saleToView = Sale::with('saleAnimals', 'user', 'farm')->findOrFail($id);
        $this->authorize('view', $this->saleToView);
        $this->showingViewModal = true;
    }

    public function closeViewModal(): void
    {
        $this->showingViewModal = false;
        $this->saleToView = null;
    }

    public function deleteSale(int $id): void
    {
        $sale = Sale::findOrFail($id);
        $this->authorize('delete', $sale);
        $sale->delete();
        session()->flash('message', 'Venta eliminada.');
        $this->closeViewModal();
    }

    public function render()
    {
        $user = auth()->user();
        $farmIds = $user->farms->pluck('id');

        $sales = Sale::with(['saleAnimals', 'user', 'farm'])
            ->where(function ($q) use ($user, $farmIds) {
                $q->where('user_id', $user->id)
                    ->orWhereIn('farm_id', $farmIds);
            })
            ->latest('sale_date')
            ->latest('id')
            ->paginate(10);

        return view('livewire.animal-sales', [
            'sales' => $sales,
        ]);
    }
}
