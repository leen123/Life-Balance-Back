<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class Activity extends Model
{

    use LogsActivity;


    protected $fillable = [
        'name', 'image', 'points', 'section_id', 'user_id' , 'ar_name'
    ];


    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Section::class);
    }


    public function users()
    {
      return $this->belongsToMany(User::class, 'user_activities');
    }

    protected static $logAttributes = ['*'];

}
