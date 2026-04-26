<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $name
 * @property string|null $brand
 * @property string $inn
 * @property string|null $phone
 * @property string|null $legal_form
 * @property string|null $logo
 * @property string|null $slug
 * @property array $types
 * @property string $status
 * @property string $vat_status
 * @property string $manufacturer_status
 */
class Company extends Model
{
    use HasUuids;

    protected $fillable = [
        'name', 'brand', 'inn', 'phone', 'legal_form', 'logo', 'slug',
        'types', 'status', 'vat_status', 'manufacturer_status', 'admin_note',
    ];

    protected $casts = [
        'types' => 'array',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function companyProducts(): HasMany
    {
        return $this->hasMany(CompanyProduct::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isAuthorized(): bool
    {
        return \in_array($this->manufacturer_status, ['authorized_partner', 'authorized_distributor'], true);
    }

    public function serials(): HasMany
    {
        return $this->hasMany(ProductSerial::class);
    }
}
