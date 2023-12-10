<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UserMoods extends Model
{

    use LogsActivity;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'moods_id',
        'note',
        'date',
        'dayDate',
        'monthDate',
        'yearDate',
        'hoursDate',
        'minutesDate',
        'secondsDate'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mood()
    {
        return $this->belongsTo(Mood::class);
    }
    protected static $logAttributes = ['*'];

}
