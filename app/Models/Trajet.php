<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trajet extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'transporteur_id',
        'service_id',
        'date_heure_depart',
        'date_heure_arrivee',
        'point_depart',
        'point_arrivee',
        'prix',
        'note',
        'etat'
    ];

    public function transporteur()
    {
        return $this->belongsTo(Transporteur::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
