<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

// todo: product may not exist

class CommerceController
{
    public function catalogue()
    {
        $data = [
            'products' => Product::orderBy('brand')->orderBy('name')->get(),
            'categories' => Category::all(),
            'brands' => Product::getBrands(),
            'price_ranges' => Product::getPriceRanges()
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

    public function filter(Request $request)
    {
        $data = $request->all();

        $sort = $data['sort'];
        $categories = $data['category'] ?? null;
        $brands = $data['brand'] ?? null;
        $price_ranges = $data['price_range'] ?? null;

        $products = Product::query();

        if ($categories)
        {
            $products->whereIn('category_name', $categories);
        }

        if ($brands)
        {
            $products->whereIn('brand', $brands);
        }

        if ($price_ranges)
        {
            $products->where(function ($query) use ($price_ranges)
            {
                foreach ($price_ranges as $range)
                {
                    [$min, $max] = explode(' ', $range);
                    $query->orWhereBetween('price', [(float)$min, (float)$max]);
                }
            });
        }

        switch ($sort)
        {
            case 'alphabetical_ascending':
                $products->orderBy('brand')->orderBy('name');
                break;
            case 'alphabetical_descending':
                $products->orderBy('brand', 'desc')->orderBy('name', 'desc');
                break;
            case 'price_ascending':
                $products->orderBy('price');
                break;
            case 'price_descending':
                $products->orderBy('price', 'desc');
                break;
        }

        $products = $products->get();

        return view('catalogue_items', ['products' => $products]);
    }
}
