<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'company_id', 'lang',
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

    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isOwner(): bool   { return $this->role === 'owner'; }
    public function isUser(): bool    { return $this->role === 'user'; }
    public function isClient(): bool  { return $this->role === 'client'; }

    public function redirectPath(): string
    {
        return match($this->role) {
            'admin'         => '/admin',
            'owner', 'user' => '/marketplace',
            default         => '/',
        };
    }
}
