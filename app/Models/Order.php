<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'status',
        'reject_reason',
        'advertisement',
    ];

    protected $casts = [
        'status' => OrderStatusEnum::class,
    ];
}
