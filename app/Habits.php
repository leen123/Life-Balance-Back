<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class Habits extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name', 'image', 'active_date', 'section_id', 'user_id' , 'repetition_type' , 'repetition_number' , 'doHabits' , 'date_type' , 'op_points' , 'ar_name'
    ];

    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    protected static $logAttributes = ['*'];

}
