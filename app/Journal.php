<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Journal extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id', 'iconType', 'nameType', 'image', 'moodImage' , 'title' , 'subtitle' , 'description',
        'date',
        'dayDate',
        'monthDate',
        'yearDate',
        'hoursDate',
        'minutesDate',
        'secondsDate',
        'ar_title',
        'ar_description',
        'ar_subtitle'

    ];
    protected static $logAttributes = ['*'];


}
