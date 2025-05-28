<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRooms extends Model
{
    protected $guarded = ["id"];


    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
