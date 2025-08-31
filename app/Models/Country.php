<?php

namespace App\Models;

use App\Models\User;
use App\Models\Admin;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_en',
        'name_ar',
        'code',
        'currency',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

}
