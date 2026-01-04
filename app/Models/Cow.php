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
}
