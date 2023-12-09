<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicine extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'name',
        'manufacturer_id',
        'expiration_date',
        'code',
        'cc',
        'cost',
        'market_id',
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'expiration_date' => 'date',
    ];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function histories()
    {
        return $this->belongsToMany(History::class);
    }
}
