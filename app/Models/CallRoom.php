<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallRoom extends Model
{
    protected $guarded = ["id" ];
    protected $table = 'call_rooms';

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
