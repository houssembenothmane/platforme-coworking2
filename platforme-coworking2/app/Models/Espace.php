<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Espace extends Model
{
    protected $table = 'espaces';

    protected $fillable = [
        'nom',
        'description',
        'prix_heure',
        'capacite',
        'statut',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'prix_heure' => 'decimal:2',
        'latitude'   => 'float',
        'longitude'  => 'float',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    public function estDisponible(): bool
    {
        return $this->statut === 'Disponible';
    }
}
