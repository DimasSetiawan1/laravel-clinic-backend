<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ChatRooms extends Model
{
    use HasUuids;
    protected $guarded = [];



    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }
}
