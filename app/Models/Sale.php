<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    const KG_TO_LBS = 2.20462;
    const TAX_PERCENT = 4;

    protected $fillable = [
        'user_id',
        'farm_id',
        'sale_date',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function saleAnimals()
    {
        return $this->hasMany(SaleAnimal::class)->orderBy('sort_order');
    }

    public function getSubtotalAttribute(): float
    {
        return (float) $this->saleAnimals->sum(fn (SaleAnimal $item) => $item->line_total ?? 0);
    }

    public function getTaxAmountAttribute(): float
    {
        return round($this->subtotal * (self::TAX_PERCENT / 100), 2);
    }

    public function getTotalAttribute(): float
    {
        return round($this->subtotal - $this->tax_amount, 2);
    }

    public function getTotalWeightKgAttribute(): float
    {
        return (float) $this->saleAnimals->sum('weight_kg');
    }
}
