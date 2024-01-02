<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'image', 'sizes', 'color_id', 'product_id'];

    /**
     * Belongs to product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Belongs to color
     */
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
}
