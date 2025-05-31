<?php

namespace App\Core;

use App\Models\Product;

class Cart
{
    private array $cartItems;

    public function __construct()
    {
        $cart = session()->get('cart', []);
        foreach ($cart as $productId => $quantity)
        {
            $product = Product::find($productId);
            $subtotal = $product->price * $quantity;

            $this->cartItems[$productId] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
    }

    public function add($product_id, $quantity)
    {
        $quantity = (int)$quantity;

        if (isset($this->cartItems[$product_id]))
        {
            $this->cartItems[$product_id]['quantity'] += $quantity;
        }
        else
        {
            $product = Product::find($product_id);
            $subtotal = $product->price * $quantity;

            $this->cartItems[$product_id] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
    }

    public function modify($product_id, $quantity)
    {
        $cartItem =& $this->cartItems[$product_id];
        $cartItem['quantity'] = $quantity;
        $cartItem['subtotal'] = $quantity * $cartItem['product']['price'];
    }

    public function remove($product_id)
    {
        if (isset($this->cartItems[$product_id]))
            unset($this->cartItems[$product_id]);
    }

    public function clear()
    {
        $this->cartItems = [];
        session()->put('cart', []);
    }

    public function put()
    {
        $cart = array_map(function ($item) {
            return $item['quantity'];
        }, $this->cartItems);
        session()->put('cart', $cart);
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->cartItems as $productID => $item)
            $total += $item['subtotal'];

        return $total;
    }

    public function getAllItems()
    {
        $cartItems = [];

        foreach ($this->cartItems as $productId => $item)
            $cartItems[] = $item;

        return $cartItems;
    }

    public function getItem($product_id)
    {
        if (isset($this->cartItems[$product_id]))
            return $this->cartItems[$product_id];
        return null;
    }
}
