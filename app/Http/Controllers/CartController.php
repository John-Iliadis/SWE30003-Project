<?php

namespace App\Http\Controllers;

use App\Models\Product;

class CartController
{
    public function restartSession()
    {
        session()->flush();
        session()->regenerate();

        return view('home');
    }

    public function cart()
    {
        $cart = session()->get('cart', []);

        $cart_items = [];
        $total = 0;

        foreach ($cart as $key => $value)
        {
            $product = Product::find($key);

            $subtotal = $product->price * $value;

            $cart_items[] = [
                'product' => $product,
                'qty' => $value,
                'subtotal' => $subtotal
            ];

            $total += $product->price;
        }

        $data = [
            'cart_items' => $cart_items,
            'total' => $total
        ];

        return view('cart', $data);
    }

    public function add($product_id, $quantity)
    {
        $cart = session()->get('cart', []);
        $quantity = (integer)$quantity;

        if (isset($cart[$product_id]))
        {
            $cart[$product_id] += $quantity;
        }
        else
        {
            $cart[$product_id] = $quantity;
        }

        session()->put('cart', $cart);

        return $cart;
    }
}
