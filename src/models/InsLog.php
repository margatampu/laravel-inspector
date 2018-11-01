<?php

namespace MargaTampu\LaravelInspector\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class InsLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ins_auth_id', 'level', 'message', 'trace'
    ];

    /**
     * Delete expired rows to minimize database size
     */
    public static function removeExpired($expirationDays)
    {
        if ($expirationDays !== 0) {
            self::where('created_at', '<', Carbon::now()->subDays($expirationDays)->toDateTimeString())
                    ->delete();
        }
    }

    /**
     * Delete over limit records
     */
    public static function removeOverLimit($recordsLimit)
    {
        if ($recordsLimit !== 0) {
            self::destroy(
                self::orderBy('created_at', 'desc')
                ->offset($recordsLimit)
                ->limit($recordsLimit)
                ->pluck('id')
                ->toArray()
            );
        }
    }
}
