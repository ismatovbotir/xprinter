<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductParameterValue extends Model
{
    protected $fillable = ['product_id', 'parameter_value_id'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function parameterValue(): BelongsTo
    {
        return $this->belongsTo(ParameterValue::class);
    }
}
