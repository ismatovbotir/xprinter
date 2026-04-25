<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSerial extends Model
{
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
