<?php

namespace App\Http\Controllers;

use App\Core\Cart;
use App\Models\Product;
use Illuminate\View\View;

class CartController
{
    /**
     * Display the current cart with all items and total.
     *
     * @return View
     */
    public function cart()
    {
        $cart = new Cart();

        $data = [
            'cart_items' => $cart->getAllItems(),
            'total' => $cart->getTotal()
        ];

        return view('commerce.cart', $data);
    }

    /**
     * Add a product to the cart.
     *
     * @param int $product_id
     * @param int $quantity
     * @return array<string, string>
     */
    public function add(int $product_id, int $quantity)
    {
        $cart = new Cart();
        $cart->add($product_id, $quantity);
        $cart->put();

        $product = Product::find($product_id);

        $msg = "Added " . $quantity . "x " . $product['brand'] . " " . $product['name'] ." to the cart.";

        return ['msg' => $msg];
    }

    /**
     * Remove a product from the cart.
     *
     * @param int $product_id
     * @return array<string, float>
     */
    public function remove(int $product_id)
    {
        $cart = new Cart();
        $cart->remove($product_id);
        $cart->put();

        // calculate the new total
        $total = $cart->getTotal();

        return ['total' => $total];
    }

    /**
     * Clear all items from the cart.
     *
     * @return void
     */
    public function clear()
    {
        $cart = new Cart();
        $cart->clear();
    }

    /**
     * Modify the quantity of a specific product in the cart.
     *
     * @param int $product_id
     * @param int $quantity
     * @return array<string, float>
     */
    public function modify(int $product_id, int $quantity)
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
