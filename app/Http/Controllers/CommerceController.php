<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;

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
}
