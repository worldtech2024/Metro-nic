<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HiddenCost extends Model
{
    protected $fillable = [
        "workWages",
        "generalCost",
        "profitMargin",
        "tax",
        "wirePrice",
    ];

    
}