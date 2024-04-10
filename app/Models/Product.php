<?php

namespace App\Models;

use App\Helpers\Images;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'slug',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
