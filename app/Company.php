<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class Company extends Model
{

    protected $table = 'companies';

    use LogsActivity ;

    protected $fillable = [
        'name',
        'description',
        'address',
        'email',
        'phone_number',
        'long',
        'lat',
        'social_media',
        'active',
        'ar_name',
        'ar_description',
        'ar_address'
    ];


    public function coupons()
    {

        return $this->hasMany(Coupon::class, 'company_id');
    }

    public function ads()
    {

        return $this->hasMany(Ad::class, 'company_id');
    }

    protected static $logAttributes = ['*'];


    public function employees()
    {

        return $this->belongsToMany(Employee::class, 'employee_company');
    }

}
