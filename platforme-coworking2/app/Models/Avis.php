<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $table = 'avis';

    protected $fillable = [
        'client_id',
        'espace_id',
        'note',
        'commentaire',
        'sentiment',
        'sentiment_score'
    ];

    protected $casts = [
        'note' => 'integer',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function espace()
    {
        return $this->belongsTo(Espace::class);
    }
   
}