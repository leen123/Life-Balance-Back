<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UserActivity extends Model
{

    use LogsActivity;
    public $timestamps = false;

    protected $fillable = [
        'activity_id',
        'user_id',
        'mood_id',
        'section_id',
        'note',
        'form',
        'to',
        'date',
        'dayDate',
        'monthDate',
        'yearDate',
        'hoursDate',
        'minutesDate',
        'secondsDate',
        'points'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    protected static $logAttributes = ['*'];
}
