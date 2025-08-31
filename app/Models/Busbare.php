<?php

namespace App\Models;

use App\Models\Order;
use App\Models\OrderUnit;
use Illuminate\Database\Eloquent\Model;

class Busbare extends Model
{
    protected $fillable = [
        'order_id',
        'order_unit_id',
        'amp',
        'quantity',
        'priceForMeter',
        'PL',
        'UP',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderUnit()
    {
        return $this->belongsTo(OrderUnit::class, 'order_unit_id');
    }
    
}