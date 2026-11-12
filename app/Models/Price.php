<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Price extends Model
{
    use LogsActivity;

    protected $fillable = ['company_product_id', 'type', 'value', 'currency'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('prices')
            ->logOnly(['company_product_id', 'type', 'value', 'currency'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function companyProduct(): BelongsTo
    {
        return $this->belongsTo(CompanyProduct::class);
    }
}
