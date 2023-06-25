<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $logName = 'products';

    protected $fillable = [
        'id',
        'category_id',
        'attribute_id',
        'name',
        'qty',
        'description',
        'status',
    ];

    // public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }



    // public function category()
    // {
    //     return $this->belongsToMany(ProductCategory::class, 'product_categories', 'product_id', 'category_id');
    // }

    public function productAttribute()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
