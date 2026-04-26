<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = ['category_id', 'slug', 'model_number', 'photo'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function parameterValues(): BelongsToMany
    {
        return $this->belongsToMany(ParameterValue::class, 'product_parameter_values');
    }

    public function companyProducts(): HasMany
    {
        return $this->hasMany(CompanyProduct::class);
    }

    public function translation(): HasOne
    {
        return $this->hasOne(ProductTranslation::class)->where('lang', app()->getLocale());
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function serials(): HasMany
    {
        return $this->hasMany(ProductSerial::class);
    }
}
