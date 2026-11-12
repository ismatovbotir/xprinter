<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property string      $id
 * @property string      $name
 * @property string      $email
 * @property string      $password
 * @property string      $role
 * @property string|null $company_id
 * @property string      $lang
 * @property string|null $telegram_chat_id
 * @property string|null $two_factor_secret
 * @property \Carbon\Carbon|null $two_factor_confirmed_at
 * @property array|null  $permissions
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids, LogsActivity;

    /**
     * Capabilities an owner can grant to a `user` inside their company.
     * null permissions = legacy "all" so existing operators keep working.
     */
    public const PERMISSIONS = [
        'manage_prices'  => 'Narxlar va assortiment',
        'manage_serials' => 'Seriya raqamlari va sotuvlar',
        'view_leads'     => 'Lidlar (so\'rovlar)',
    ];

    protected $fillable = [
        'name', 'last_name', 'email', 'password', 'role', 'company_id', 'lang', 'phone', 'telegram_chat_id',
        'permissions', 'two_factor_secret', 'two_factor_confirmed_at',
    ];

    protected $hidden = ['password', 'remember_token', 'two_factor_secret'];

    protected function casts(): array
    {
        return [
            'email_verified_at'       => 'datetime',
            'password'                => 'hashed',
            'two_factor_secret'       => 'encrypted',
            'two_factor_confirmed_at' => 'datetime',
            'permissions'             => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('users')
            ->logOnly(['name', 'email', 'role', 'company_id', 'permissions', 'two_factor_confirmed_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function clientMessages(): HasMany
    {
        return $this->hasMany(ClientMessage::class);
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

    // ── Company permissions ──────────────────────────────────

    /** Owners (and admins) can do everything; operators only what they were granted. */
    public function hasPermission(string $key): bool
    {
        if ($this->isAdmin() || $this->isOwner()) {
            return true;
        }

        if (!$this->isUser()) {
            return false;
        }

        // Legacy operators created before permissions existed keep full access.
        if ($this->permissions === null) {
            return true;
        }

        return in_array($key, $this->permissions, true);
    }

    // ── Two-factor auth ──────────────────────────────────────

    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_secret !== null && $this->two_factor_confirmed_at !== null;
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
