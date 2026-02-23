<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleAnimal extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'description',
        'weight_kg',
        'price_per_kg_usd',
        'sort_order',
    ];

    protected $casts = [
        'weight_kg' => 'decimal:2',
        'price_per_kg_usd' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function getWeightLbsAttribute(): ?float
    {
        if ($this->weight_kg === null) {
            return null;
        }
        return round((float) $this->weight_kg * Sale::KG_TO_LBS, 2);
    }

    public function getLineTotalAttribute(): ?float
    {
        if ($this->weight_kg === null || $this->price_per_kg_usd === null) {
            return null;
        }
        return round((float) $this->weight_kg * (float) $this->price_per_kg_usd, 2);
    }

    public function hasWeightAndPrice(): bool
    {
        return $this->weight_kg !== null && $this->price_per_kg_usd !== null;
    }
}
