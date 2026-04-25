<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    protected $fillable = ['company_product_id', 'type', 'value', 'currency'];

    public function companyProduct(): BelongsTo
    {
        return $this->belongsTo(CompanyProduct::class);
    }
}
