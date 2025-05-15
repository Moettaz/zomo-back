<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'transporteur_id',
        'service_id',
        'date_reservation',
        'status',
        'commentaire',
        'type_menagement',
        'type_vehicule',
        'distance',
        'from',
        'to',
        'heure_reservation',
        'etage',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
