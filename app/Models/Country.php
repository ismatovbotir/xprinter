<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Country extends Model
{
    protected $fillable = ['code'];

    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }

    public function translation(): HasOne
    {
        return $this->hasOne(CountryTranslation::class)->where('lang', app()->getLocale());
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CountryTranslation::class);
    }
}
