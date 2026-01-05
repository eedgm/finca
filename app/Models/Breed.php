<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breed extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $searchableFields = ['*'];

    public function cows()
    {
        return $this->belongsToMany(Cow::class, 'breed_cow')->withPivot('percentage');
    }
}
