<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use LogsActivity;
    use HasApiTokens, Notifiable , HasRoles;

    protected $guard_name = 'api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password' , 'role' ,'user_name' , 'points', 'is_active' , 'plan' , 'expiry_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected static $logAttributes = ['name', 'email','role' ,'user_name' , 'points', 'is_active' , 'plan' , 'expiry_date'];



    public function activities()
    {
      return $this->belongsToMany(Activity::class, 'user_activities');
    }


    public function badges()
    {
      return $this->belongsToMany(Badges::class, 'users_badges');
    }

    public function moods()
    {
      return $this->belongsToMany(Mood::class, 'user_moods');
    }

    public function coupons(){

      return $this->belongsToMany(Coupon::class,'user_coupon')->withPivot('points');
    }

    public function fcm_tokens(){

        return $this->hasMany(FCM::class,'user_id');
    }


}
