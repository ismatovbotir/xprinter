<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductSerial extends Model
{
    use LogsActivity;

    protected $fillable = [
        'product_id',
        'serial_number',
        'status',
        'company_id',
        'warranty_expires_at',
        'notes',
    ];

    protected $casts = [
        'warranty_expires_at' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('serials')
            ->logOnly(['serial_number', 'status', 'company_id', 'warranty_expires_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}
