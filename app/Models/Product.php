<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'name',
        'brand',
        'description',
        'price',
        'stock',
        'category_name',
        'image_url'
    ];

    /**
     * Get all distinct brands from the products table.
     *
     * @return array
     */
    public static function getBrands()
    {
        return Product::select('brand')->distinct()->pluck('brand');
    }

    /**
     * Get predefined product price ranges.
     *
     * @return array<int, array>
     */
    public static function getPriceRanges()
    {
        return [
            [0, 50],
            [50, 100],
            [200, 500],
            [500, 1000],
            [1000, 2000],
            [2000, 5000],
            [5000, 10000]
        ];
    }
}
