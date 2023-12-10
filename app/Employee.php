<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class Employee extends Model
{

    use LogsActivity;
    protected $table = 'employees';
    protected $fillable = [
        'name', 'email', 'address' , 'number','company_id' , 'ar_name', 'ar_address'
    ];

    public function company()
    {

        return $this->belongsToMany(Company::class, 'employee_company');
    }
    protected static $logAttributes = ['*'];


}
