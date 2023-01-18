<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerificationTokens extends Model
{
    use HasFactory;
    protected $dates = ['expires_at'];
    const UPDATED_AT = null;

    protected static function boot()
    {
        parent::boot();
        static::creating(function($model){
            $model->expires_at = Carbon::now()->addDay();
        });
    }

    protected $fillable = [
        'user_id',
        'token',
    ];
}
