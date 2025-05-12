<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transporteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'username',
        'password',
        'phone',
        'points',
        'image_url',
        'service_id',
        'disponibilite',
        'note_moyenne',
    ];
}
