<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Market extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'phone', 'direction'];

    protected $searchableFields = ['*'];

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
}
