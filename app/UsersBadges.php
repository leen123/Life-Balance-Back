<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UsersBadges extends Model
{

    use LogsActivity;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'badges_id',
        'is_grand_master'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function badges()
    {
        return $this->belongsTo(Badges::class);
    }

    protected static $logAttributes = ['*'];


}
