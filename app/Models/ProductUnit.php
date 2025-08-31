<?php

namespace App\Models;

use App\Models\Product;
use App\Models\OrderUnit;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    protected $fillable = [
        'product_id',
        'order_unit_id',
        'quantity'
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderUnit()
    {
        return $this->belongsTo(OrderUnit::class);
    }

}