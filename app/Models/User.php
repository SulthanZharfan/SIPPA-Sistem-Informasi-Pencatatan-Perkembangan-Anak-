<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Hanya ADMIN yang bisa akses panel Filament
    public function canAccessPanel(Panel $panel): bool
    {
        return match($panel->getId()) {
            'admin' => $this->hasRole('admin'),
            'wali' => $this->hasRole('wali'),
            'kepsek' => $this->hasRole('kepsek'),
            'guru' => $this->hasRole('guru'),
            default => false,
        };
    }
}
