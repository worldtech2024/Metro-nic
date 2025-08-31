<?php
namespace App\Models;

use App\Models\Order;
use App\Models\Country;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'country_id',
        'name',
        'username',
        'phone',
        'email',
        'password',
        'otp',
        'image',
        'role',
        'fcm_token',
    ];
    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }
    protected $casts = [
        'permissions' => 'array',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

}
