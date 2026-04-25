<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CityTranslation extends Model
{
    protected $fillable = ['city_id', 'lang', 'name'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
