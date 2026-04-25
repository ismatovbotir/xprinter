<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParameterTranslation extends Model
{
    protected $fillable = ['parameter_id', 'lang', 'name'];

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(Parameter::class);
    }
}
