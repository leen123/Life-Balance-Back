<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;

class Coupon extends Model
{
    use LogsActivity;

    protected $table = 'coupons';

    protected $fillable = [
        'name',
        'description',
        'code',
        'type',
        'value',
        'max_uses',
        'active',
        'QR',
        'points',
        'starts_at',
        'ends_at',
        'company_id',
        'ar_name',
        'ar_description'
    ];

    public function company()
    {

        return $this->belongsTo(Company::class, 'company_id');
    }


    public function users()
    {

        return $this->belongsToMany(User::class, 'user_coupon');
    }

    protected static $logAttributes = ['*'];


    public function scopeAvailable($query, $userId)
    {
        $now = Carbon::now();

        return $query->where(function ($query) {
            $query->where('max_uses', '>', 0)
                ->orWhere('max_uses', null);
        })->whereDate('starts_at', '<=', $now)
            ->where(function ($query)  use ($now) {
                $query->whereDate('ends_at', '>=', $now)
                    ->orWhere('ends_at', null);
            })->whereDoesntHave('users', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            })->where('active', true);
    }
}
