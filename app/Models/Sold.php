<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sold extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'date',
        'cow_id',
        'pounds',
        'kilograms',
        'price',
        'number_sold',
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'date' => 'date',
    ];

    public function cow()
    {
        return $this->belongsTo(Cow::class);
    }
}
