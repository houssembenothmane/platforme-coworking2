<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Ajouter le rôle
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

    // Helper pour vérifier si l'utilisateur est admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Helper pour vérifier si l'utilisateur est client
    public function isClient(): bool
    {
        return $this->role === 'client';
    }
}