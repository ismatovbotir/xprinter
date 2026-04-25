<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryParameter extends Model
{
    protected $fillable = ['category_id', 'parameter_id', 'sort_order'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(Parameter::class);
    }
}
