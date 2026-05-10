<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Espace extends Model
{
    protected $table = 'espaces';
    protected $primaryKey = 'IdEspace';

    protected $fillable = [
        'nom', 'description', 'prix_heure',
        'capacite', 'statut', 'latitude', 'longitude',
    ];

    protected $casts = [
        'prix_heure' => 'decimal:2',
        'latitude'   => 'float',
        'longitude'  => 'float',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'IdEspace', 'IdEspace');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'IdEspace', 'IdEspace');
    }

    public function estDisponible(): bool
    {
        return $this->statut === 'Disponible';
    }
}