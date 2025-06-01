<?php

namespace App\Http\Controllers;

use App\Core\Cart;
use App\Models\Product;

class CartController
{
    public function cart()
    {
        $cart = new Cart();

        $data = [
            'cart_items' => $cart->getAllItems(),
            'total' => $cart->getTotal()
        ];

        return view('commerce.cart', $data);
    }

    public function add($product_id, $quantity)
    {
        $cart = new Cart();
        $cart->add($product_id, $quantity);
        $cart->put();

        $product = Product::find($product_id);

        $msg = "Added " . $quantity . "x " . $product['brand'] . " " . $product['name'] ." to the cart.";

        return ['msg' => $msg];
    }

    public function remove($product_id)
    {
        $cart = new Cart();
        $cart->remove($product_id);
        $cart->put();

        // calculate the new total
        $total = $cart->getTotal();

        return ['total' => $total];
    }

    public function clear()
    {
        $cart = new Cart();
        $cart->clear();
    }

    public function modify($product_id, $quantity)
    {
        $cart = new Cart();
        $cart->modify($product_id, $quantity);
        $cart->put();

        $cartItem = $cart->getItem($product_id);
        $total = $cart->getTotal();
        $subtotal = $cartItem['subtotal'];

        return [
            'total' => $total,
            'subtotal' => $subtotal
        ];
    }
}
