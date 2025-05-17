<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'brand',
        'description',
        'price',
        'stock',
        'category_id'
    ];
}
