<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'hex_code', 'description'];

    protected $searchableFields = ['*'];

    public function cows()
    {
        return $this->belongsToMany(Cow::class, 'color_cow');
    }
}
