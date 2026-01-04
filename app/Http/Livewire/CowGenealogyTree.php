<?php

namespace App\Http\Livewire;

use App\Models\Cow;
use App\Models\Farm;
use Livewire\Component;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CowGenealogyTree extends Component
{
    use AuthorizesRequests;

    public $selectedCowId = null;
    public $selectedCow = null;
    public $cowsForSelect = [];
    public $genealogyTree = [];
    public $maxDepth = 5; // Maximum depth to prevent infinite recursion

    public function mount(): void
    {
        $this->authorize('view-any', Cow::class);
        $this->loadCowsForSelect();
    }

    public function loadCowsForSelect(): void
    {
        $user = auth()->user();
        $farmIds = $user->farms->pluck('id');
        
        $cows = Cow::whereIn('farm_id', $farmIds)
            ->orderBy('number')
            ->get();
        
        foreach ($cows as $cow) {
            $label = ($cow->number ? '#' . $cow->number : '') . ($cow->name ? ' - ' . $cow->name : '');
            $this->cowsForSelect[$cow->id] = $label ?: 'Vaca #' . $cow->id;
        }
    }

    public function selectCow($cowId): void
    {
        $this->selectedCowId = $cowId;
        $this->selectedCow = Cow::with(['parent', 'mother', 'farm'])->findOrFail($cowId);
        $this->authorize('view', $this->selectedCow);
        $this->buildGenealogyTree();
    }

    public function buildGenealogyTree(): void
    {
        if (!$this->selectedCow) {
            return;
        }

        $this->genealogyTree = $this->buildNode($this->selectedCow, 0);
    }

    private function buildNode($cow, $depth): array
    {
        if ($depth >= $this->maxDepth || !$cow) {
            return null;
        }

        $node = [
            'id' => $cow->id,
            'number' => $cow->number,
            'name' => $cow->name,
            'gender' => $cow->gender,
            'picture' => $cow->picture,
            'born' => $cow->born,
            'depth' => $depth,
        ];

        // Load parent and mother with their relationships
        $parent = $cow->parent ? Cow::with(['parent', 'mother'])->find($cow->parent_id) : null;
        $mother = $cow->mother ? Cow::with(['parent', 'mother'])->find($cow->mother_id) : null;

        $node['parent'] = $parent ? $this->buildNode($parent, $depth + 1) : null;
        $node['mother'] = $mother ? $this->buildNode($mother, $depth + 1) : null;

        // Get siblings (same parent or same mother)
        $siblings = collect();
        if ($parent) {
            $siblings = Cow::where('parent_id', $parent->id)
                ->where('id', '!=', $cow->id)
                ->get();
        }
        if ($mother) {
            $motherSiblings = Cow::where('mother_id', $mother->id)
                ->where('id', '!=', $cow->id)
                ->get();
            $siblings = $siblings->merge($motherSiblings)->unique('id');
        }
        $node['siblings'] = $siblings->map(function($sibling) {
            return [
                'id' => $sibling->id,
                'number' => $sibling->number,
                'name' => $sibling->name,
                'gender' => $sibling->gender,
            ];
        })->values()->toArray();

        // Get children
        $children = Cow::where(function($query) use ($cow) {
            $query->where('parent_id', $cow->id)
                  ->orWhere('mother_id', $cow->id);
        })->get();
        $node['children'] = $children->map(function($child) {
            return [
                'id' => $child->id,
                'number' => $child->number,
                'name' => $child->name,
                'gender' => $child->gender,
            ];
        })->values()->toArray();

        return $node;
    }

    public function render(): View
    {
        return view('livewire.cow-genealogy-tree');
    }
}

