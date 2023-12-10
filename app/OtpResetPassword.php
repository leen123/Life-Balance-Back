<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtpResetPassword extends Model
{
    protected $table = 'otp_reset_passwords';

    protected $fillable = [
        'email',
        'code',
        'expires_at',
    ];
}
