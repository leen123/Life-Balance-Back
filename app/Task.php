<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class Task extends Model
{

    use LogsActivity;
    protected $fillable = [
        'title', 'goals_id', 'is_Finished' , 'user_id', 'ar_title'
    ];


    public function goal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Goals::class);
    }

    protected static $logAttributes = ['*'];


}
