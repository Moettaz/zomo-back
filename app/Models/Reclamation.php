<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'transporteur_id',
        'service_id',
        'date_creation',
        'sujet',
        'description',
        'status',
        'priorite'
    ];

    /**
     * Get the client that owns the reclamation.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the transporteur that owns the reclamation.
     */
    public function transporteur()
    {
        return $this->belongsTo(Transporteur::class);
    }

    /**
     * Get the service that owns the reclamation.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the date_creation attribute in a specific format.
     */
    protected $casts = [
        'date_creation' => 'datetime',
    ];
}
