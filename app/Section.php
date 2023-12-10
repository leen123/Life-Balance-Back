<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class Section extends  Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'code',
        'image',
        'icon',
        'description',
        'ar_name',
        'ar_description'
    ];

    public function activity(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function habit(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Habits::class);
    }

    public function goal(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Goals::class);
    }

    protected static $logAttributes = ['*'];

}
