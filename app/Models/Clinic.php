<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'email',
        'open_time',
        'close_time',
        'website',
        'description',
        'image',
        'spesialis',
    ];
}
