<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'transporteur_id',
        'service_id',
        'montant',
        'methode_paiement',
        'date_paiement',
        'status',
        'reference'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function transporteur()
    {
        return $this->belongsTo(Transporteur::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    

    
}
