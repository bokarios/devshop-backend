<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Has many favorites
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
