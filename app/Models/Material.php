<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'code',
        'farm_id',
        'market_id',
        'status',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function inventoryMaterials()
    {
        return $this->hasMany(InventoryMaterial::class);
    }
}
