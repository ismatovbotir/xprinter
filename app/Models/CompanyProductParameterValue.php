<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyProductParameterValue extends Model
{
    protected $fillable = ['company_product_id', 'parameter_id', 'parameter_value_id'];

    public function companyProduct(): BelongsTo
    {
        return $this->belongsTo(CompanyProduct::class);
    }

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(Parameter::class);
    }

    public function parameterValue(): BelongsTo
    {
        return $this->belongsTo(ParameterValue::class);
    }
}
