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
        'owner',
        'sold',
        'born',
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
}
