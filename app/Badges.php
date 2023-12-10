<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class Badges extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name', 'is_from_section', 'points', 'section_id', 'badges_id' , 'count_of_badges' , 'image' , 'is_grand_master' , 'ar_name'
    ];


    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Section::class);
    }


    public function badges(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Badges::class);
    }


    public function users()
    {
      return $this->belongsToMany(User::class, 'users_badges');
    }

    protected static $logAttributes = ['*'];
}
