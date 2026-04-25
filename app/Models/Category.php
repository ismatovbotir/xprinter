<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    protected $fillable = ['slug'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class, 'category_parameters')
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }

    public function translation(): HasOne
    {
        return $this->hasOne(CategoryTranslation::class)->where('lang', app()->getLocale());
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class);
    }
}
