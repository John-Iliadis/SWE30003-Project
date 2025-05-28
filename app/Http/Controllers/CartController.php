<?php

namespace App\Http\Controllers;

class CartController
{
    public function add($product_id, $quantity)
    {
        $cart = session()->get('cart', []);
        $quantity = (integer)$quantity;

        if (isset($cart[$product_id]))
        {
            $cart[$product_id]['qty'] += $quantity;
        }
        else
        {
            $cart[$product_id]['qty'] = $quantity;
        }

        session()->put('cart', $cart);

        return $cart;
    }
}
