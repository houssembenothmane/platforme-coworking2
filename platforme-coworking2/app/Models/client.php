<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use Notifiable;

    protected $table = 'clients';
    protected $primaryKey = 'IdClient'; // ← ajouter ici
    protected $fillable = [
        'nom',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function reservations()
{
    return $this->hasMany(Reservation::class, 'IdClient', 'IdClient');
}

public function avis()
{
    return $this->hasMany(Avis::class, 'IdClient', 'IdClient');
}
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }
}