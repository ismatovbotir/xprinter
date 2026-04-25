<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ParameterValue extends Model
{
    protected $fillable = ['parameter_id'];

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(Parameter::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_parameter_values');
    }

    public function translation(): HasOne
    {
        return $this->hasOne(ParameterValueTranslation::class)->where('lang', app()->getLocale());
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ParameterValueTranslation::class);
    }
}
