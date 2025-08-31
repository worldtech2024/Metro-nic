<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Busbare;
use App\Models\ProductUnit;
use Illuminate\Database\Eloquent\Model;

class OrderUnit extends Model
{
     protected $fillable = [
        'order_id',
        'name',
        
        'subTotal',
        'brandDiscount',
        'totalBusbar',
        'workWages',
        'workWagesPercentage',
        'generalCost',
        'generalCostPercentage',
        'profitMargin',
        'profitMarginPercentage',
        'vat',
        'vatPercentage',
        'finalDiscount',
        'totalPrice',
        'notes',
        
        'totalVAT',
        'totalUp',
        'totalTP',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productUnits()
    {
        return $this->hasMany(ProductUnit::class, 'order_unit_id');
    }
    public function busbares()
    {
        return $this->hasMany(Busbare::class);
    }
}