<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'short_description', 'description', 'price', 'image', 'featured', 'category_id'];

    /**
     * Has many favorites
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Belongs to category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
