<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CompanyProduct extends Model
{
    use LogsActivity;

    protected $fillable = ['company_id', 'product_id', 'is_available', 'quantity'];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('assortiment')
            ->logOnly(['company_id', 'product_id', 'is_available', 'quantity'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

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

    public function variantValues(): HasMany
    {
        return $this->hasMany(CompanyProductParameterValue::class);
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
