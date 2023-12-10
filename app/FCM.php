<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FCM extends Model
{
    protected $table = 'fcm_tokens';


    protected $fillable = [
        'token',
        'user_id'
    ];

    public function user()
    {

        return $this->belongsTo(User::class, 'user_id');
    }
}
