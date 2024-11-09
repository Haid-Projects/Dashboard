<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Specialist extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'phone_number',
        'fcm_token'
    ];
    protected $hidden = [
        'password',
        'remember_token',
        'fcm_token'
    ];
    public function sessions(){
        return $this->hasMany(Session::class);
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token; // assuming 'fcm_token' is a field in your users table
    }
}
