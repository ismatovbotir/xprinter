<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyProduct extends Model
{
    protected $fillable = ['company_id', 'product_id', 'is_available', 'quantity'];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function retailPrice()
    {
        return $this->prices()->where('type', 'retail')->first();
    }

    public function wholesalePrice()
    {
        return $this->prices()->where('type', 'wholesale')->first();
    }
}
