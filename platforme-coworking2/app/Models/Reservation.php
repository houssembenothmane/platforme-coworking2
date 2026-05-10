<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'IdReservation';

    protected $fillable = [
        'IdClient', 'IdEspace',
        'date', 'heure_debut', 'heure_fin',
        'statut', 'montant', 'numero_siege',
    ];

    protected $casts = [
        'date'    => 'date',
        'montant' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'IdClient', 'IdClient');
    }

    public function espace()
    {
        return $this->belongsTo(Espace::class, 'IdEspace', 'IdEspace');
    }

    public function genererFacture(): array
    {
        return [
            'numero'      => 'FACT-' . str_pad($this->IdReservation, 6, '0', STR_PAD_LEFT),
            'date'        => $this->date->format('d/m/Y'),
            'client'      => $this->client?->nom,
            'espace'      => $this->espace?->nom,
            'heure_debut' => $this->heure_debut,
            'heure_fin'   => $this->heure_fin,
            'montant'     => number_format($this->montant, 2) . ' TND',
            'statut'      => $this->statut,
        ];
    }
}