<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Images extends Model
{
    use LogsActivity;
    protected $fillable = [
       'image'
    ];
    protected static $logAttributes = ['*'];

}
