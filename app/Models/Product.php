<?php

namespace App\Models;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['brand_id', 'name', 'productNum', "sellingPrice"];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}