<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property string      $id
 * @property string      $name
 * @property string      $email
 * @property string      $password
 * @property string      $role
 * @property string|null $company_id
 * @property string      $lang
 * @property string|null $telegram_chat_id
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'name', 'last_name', 'email', 'password', 'role', 'company_id', 'lang', 'phone', 'telegram_chat_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isProducer(): bool { return $this->role === 'producer'; }
    public function isOwner(): bool    { return $this->role === 'owner'; }
    public function isUser(): bool     { return $this->role === 'user'; }
    public function isClient(): bool   { return $this->role === 'client'; }

    /** Roles that must never be linked to a company. */
    public function isCompanyForbidden(): bool
    {
        return in_array($this->role, ['admin', 'producer'], true);
    }

    /** Roles that can belong to a company. */
    public function canHaveCompany(): bool
    {
        return in_array($this->role, ['owner', 'user'], true);
    }

    public function redirectPath(): string
    {
        return match($this->role) {
            'admin'    => '/admin',
            'producer' => '/producer',
            'owner'    => $this->ownerRedirect(),
            'user'     => $this->userRedirect(),
            default    => '/',
        };
    }

    private function ownerRedirect(): string
    {
        $company = $this->company;
        if (!$company) {
            return '/marketplace/onboarding';
        }
        return match($company->status) {
            'approved' => '/marketplace',
            default    => '/marketplace/pending',
        };
    }

    private function userRedirect(): string
    {
        $company = $this->company;
        if (!$company || $company->status !== 'approved') {
            return '/';
        }
        return '/marketplace';
    }
}
