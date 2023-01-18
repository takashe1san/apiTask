<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'user',
    ];
    
    public function users()
    {
        return $this->belongsTo(User::class, 'user');
    }
    
    public function images()
    {
        return $this->hasMany(Image::class, 'advertisement');
    }
}
