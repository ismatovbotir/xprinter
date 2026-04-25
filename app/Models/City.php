<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class City extends Model
{
    protected $fillable = ['region_id'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function translation(): HasOne
    {
        return $this->hasOne(CityTranslation::class)->where('lang', app()->getLocale());
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CityTranslation::class);
    }
}
