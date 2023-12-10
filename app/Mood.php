<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class Mood extends Model
{

     use LogsActivity;

    protected $fillable = [
        'name', 'image' , 'ar_name'
    ];


    public function users()
    {
      return $this->belongsToMany(User::class, 'user_moods');
    }
    protected static $logAttributes = ['*'];

}
