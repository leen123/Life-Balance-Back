<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class Goals extends Model
{
    use LogsActivity;


    protected $fillable = [
        'name', 'image', 'final_date', 'section_/id', 'user_id' , 'duration' , 'points' , 'is_completed' , 'ar_name'
    ];

    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class);
    }
    protected static $logAttributes = ['*'];

}
