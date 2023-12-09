<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Farm extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'description',
        'cattle_brand',
    ];

    protected $searchableFields = ['*'];

    public function cows()
    {
        return $this->hasMany(Cow::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
