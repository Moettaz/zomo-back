<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'transporteur_id',
        'note',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function transporteur()
    {
        return $this->belongsTo(Transporteur::class);
    }
}
