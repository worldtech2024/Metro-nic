<?php

namespace App\Models;

use App\Models\User;
use App\Models\Admin;
use App\Models\Busbare;
use App\Models\Country;
use App\Models\OrderUnit;
use App\Models\ProductUnit;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'country_id',
        'admin_id',
        'user_id',
        'admin_buy_id',
        'admin_install_id',
        'orderNumber',
        'projectName',
        'sendNotification',
        'send',
        'CustomerFileNumber',
        'description',
        'deadline',
        "subTotal",
        "totalBusbar",
        "DiscountTotal",
        "totalVAT",
        "totalPrice",

        'status',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function admin_buy()
    {
        return $this->belongsTo(Admin::class, 'admin_buy_id');
    }

    public function admin_install()
    {
        return $this->belongsTo(Admin::class, 'admin_install_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderUnits()
    {
        return $this->hasMany(OrderUnit::class);

    }

    public function busbars()
    {
        return $this->hasMany(Busbare::class, 'order_id');
    }


    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
