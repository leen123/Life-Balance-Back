<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class Ad extends Model
{
    use LogsActivity;

    protected $table = 'ads';

    protected $fillable = [
        'title',
        'description',
        'url',
        'image',
        'video',
        'starts_at',
        'ends_at',
        'active',
        'company_id',
        'ar_title',
        'ar_description'
    ];


    protected static $logAttributes = ['*'];

    public function company()
    {

        return $this->belongsTo(Company::class, 'company_id');
    }


}

