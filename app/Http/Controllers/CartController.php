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

        $cart_items = $this->getCartItems($cart);
        $total = $this->getCartTotal($cart_items);

        $data = [
            'cart_items' => $cart_items,
            'total' => $total
        ];

        return view('commerce.cart', $data);
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

        $product = Product::find($product_id);

        $msg = "Added " . $quantity . "x " . $product['brand'] . " " . $product['name'] ." to the cart.";

        return ['msg' => $msg];
    }

    public function remove($product_id)
    {
        $cart = session()->get('cart', []);

        // remove item
        if (isset($cart[$product_id]))
            unset($cart[$product_id]);

        session()->put('cart', $cart);

        // calculate the new total
        $cart_items = $this->getCartItems($cart);
        $total = $this->getCartTotal($cart_items);

        return ['total' => $total];
    }

    public function clear()
    {
        session()->put('cart', []);
    }

    public function modify($product_id, $quantity)
    {
        $product = Product::find($product_id);

        $cart = session()->get('cart', []);
        $cart[$product_id] = $quantity;
        $subtotal = $cart[$product_id] * $product['price'];
        $total = $this->getCartTotal($this->getCartItems($cart));

        session()->put('cart', $cart);

        return [
            'subtotal' => $subtotal,
            'total' => $total
        ];
    }

    private function getCartItems($cart)
    {
        $cart_items = [];

        foreach ($cart as $key => $value)
        {
            $product = Product::find($key);
            $subtotal = $product->price * $value;
            $cart_items[] = [
                'product' => $product,
                'qty' => $value,
                'subtotal' => $subtotal
            ];
        }

        return $cart_items;
    }

    private function getCartTotal($cart_items)
    {
        $total = 0;

        foreach ($cart_items as $cart_item)
        {
            $total += $cart_item['subtotal'];
        }

        return $total;
    }
}
