<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;
    protected $fillable = [
        'sender_id',
        'receiver_id', 
        'service_id',
        'type',
        'message',
        'status',
        'date_notification'
    ];
}
