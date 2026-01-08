<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'quantity',
        'cost',
        'type',
        'user_id',
        'status',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
    ];

    public static $types = [
        'entrada',
        'salida',
        'ajuste',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
