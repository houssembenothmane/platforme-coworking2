<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $table = 'avis';
    protected $primaryKey = 'IdAvis';

    protected $fillable = [
        'IdClient', 'IdEspace',
        'note', 'commentaire',
    ];

    protected $casts = [
        'note' => 'integer',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'IdClient', 'IdClient');
    }

    public function espace()
    {
        return $this->belongsTo(Espace::class, 'IdEspace', 'IdEspace');
    }
}