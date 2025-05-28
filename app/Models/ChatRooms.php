<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ChatRooms extends Model
{
    use HasUuids;
    protected $guarded = [];


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
