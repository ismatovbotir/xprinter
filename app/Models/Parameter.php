<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Parameter extends Model
{
    public const TYPES = ['string', 'integer', 'boolean'];

    protected $fillable = ['type'];

    public function values(): HasMany
    {
        return $this->hasMany(ParameterValue::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_parameters')->withPivot('sort_order', 'is_variant');
    }

    public function translation(): HasOne
    {
        return $this->hasOne(ParameterTranslation::class)->where('lang', app()->getLocale());
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ParameterTranslation::class);
    }
}
