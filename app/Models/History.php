<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class History extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'date',
        'weight',
        'cow_type_id',
        'comments',
        'picture',
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'date' => 'date',
    ];

    public function cowType()
    {
        return $this->belongsTo(CowType::class);
    }

    public function medicines()
    {
        return $this->belongsToMany(Medicine::class);
    }

    public function cows()
    {
        return $this->belongsToMany(Cow::class);
    }
}
