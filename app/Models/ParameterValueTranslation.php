<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParameterValueTranslation extends Model
{
    protected $fillable = ['parameter_value_id', 'lang', 'name'];

    public function parameterValue(): BelongsTo
    {
        return $this->belongsTo(ParameterValue::class);
    }
}
