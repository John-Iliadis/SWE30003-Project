<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;

// todo: product may not exist

class CommerceController
{
    public function catalogue()
    {
        $data = [
            'categories' => Category::all(),
            'products' => Product::all(),
        ];

        return view('catalogue', $data);
    }

    public function product($id)
    {
        $data = [
            'product' => Product::find($id)
        ];

        return view('product', $data);
    }
}
