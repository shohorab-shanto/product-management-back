<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $logName = 'categories';

    protected $fillable = [
        'id',
        'name',
        'parent_cat_id',
        'description',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }
}
