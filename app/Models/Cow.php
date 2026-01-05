<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cow extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'number',
        'name',
        'gender',
        'picture',
        'parent_id',
        'mother_id',
        'farm_id',
        'cow_type_id',
        'owner',
        'sold',
        'born',
        'birth_weight',
        'height',
        'observations',
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'sold' => 'boolean',
        'born' => 'date',
    ];

    public function solds()
    {
        return $this->hasMany(Sold::class);
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function histories()
    {
        return $this->belongsToMany(History::class);
    }

    public function cowType()
    {
        return $this->belongsTo(CowType::class);
    }

    public function parent()
    {
        return $this->belongsTo(Cow::class, 'parent_id');
    }

    public function mother()
    {
        return $this->belongsTo(Cow::class, 'mother_id');
    }

    public function children()
    {
        return $this->hasMany(Cow::class, 'parent_id');
    }

    public function childrenByMother()
    {
        return $this->hasMany(Cow::class, 'mother_id');
    }

    public function breeds()
    {
        return $this->belongsToMany(Breed::class, 'breed_cow')->withPivot('percentage');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'color_cow');
    }

    public function markings()
    {
        return $this->belongsToMany(Marking::class, 'marking_cow');
    }

    /**
     * Get the predominant breed based on highest percentage
     */
    public function getPredominantBreedAttribute()
    {
        return $this->breeds()
            ->orderBy('breed_cow.percentage', 'desc')
            ->first();
    }

    /**
     * Calculate breed percentages from parents
     */
    public function calculateBreedPercentages(): void
    {
        $breeds = [];
        
        // Get breeds from parent (50% contribution)
        if ($this->parent) {
            foreach ($this->parent->breeds as $breed) {
                $percentage = $breed->pivot->percentage / 2; // 50% from parent
                if (!isset($breeds[$breed->id])) {
                    $breeds[$breed->id] = 0;
                }
                $breeds[$breed->id] += $percentage;
            }
        }
        
        // Get breeds from mother (50% contribution)
        if ($this->mother) {
            foreach ($this->mother->breeds as $breed) {
                $percentage = $breed->pivot->percentage / 2; // 50% from mother
                if (!isset($breeds[$breed->id])) {
                    $breeds[$breed->id] = 0;
                }
                $breeds[$breed->id] += $percentage;
            }
        }
        
        // Sync breeds with calculated percentages
        if (!empty($breeds)) {
            $this->breeds()->sync($breeds);
        }
    }
}
