<?php

namespace App\Core;

use App\Models\Product;

/**
 * Class Cart
 *
 * Handles operations related to the shopping cart, such as adding,
 * modifying, removing items, and calculating totals.
 */
class Cart
{
    // Stores cart items with product ID as the key
    private array $cartItems;

    /**
     * Cart constructor.
     *
     * Initializes the cart from session data, retrieving saved product IDs and quantities.
     * Calculates subtotal for each item and stores full product info.
     */
    public function __construct()
    {
        $this->cartItems = [];

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

    /**
     * Adds a product to the cart or updates quantity if already exists.
     *
     * @param int $product_id
     * @param int $quantity
     */
    public function add(int $product_id, int $quantity)
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

    /**
     * Modifies the quantity of a product in the cart.
     *
     * @param int $product_id
     * @param int $quantity
     */
    public function modify(int $product_id, int $quantity)
    {
        $cartItem =& $this->cartItems[$product_id];
        $cartItem['quantity'] = $quantity;
        $cartItem['subtotal'] = $quantity * $cartItem['product']['price'];
    }

    /**
     * Removes a product from the cart.
     *
     * @param int $product_id
     */
    public function remove(int $product_id)
    {
        if (isset($this->cartItems[$product_id]))
            unset($this->cartItems[$product_id]);
    }

    /**
     * Clears all items from the cart and updates the session.
     */
    public function clear()
    {
        $this->cartItems = [];
        session()->put('cart', []);
    }

    /**
     * Saves the current cart item quantities into the session.
     * Only product ID and quantity are saved (not full product info).
     */
    public function put()
    {
        $cart = array_map(function ($item) {
            return $item['quantity'];
        }, $this->cartItems);
        session()->put('cart', $cart);
    }

    /**
     * Calculates and returns the total cost of all items in the cart.
     *
     * @return int
     */
    public function getTotal()
    {
        $total = 0;

        foreach ($this->cartItems as $productID => $item)
            $total += $item['subtotal'];

        return $total;
    }

    /**
     * Returns all items currently in the cart.
     *
     * @return array(product, quantity, total)
     */
    public function getAllItems()
    {
        $cartItems = [];

        foreach ($this->cartItems as $productId => $item)
            $cartItems[] = $item;

        return $cartItems;
    }

    /**
     * Returns a specific item from the cart by product ID.
     *
     * @param int $product_id
     * @return array|null
     */
    public function getItem(int $product_id)
    {
        if (isset($this->cartItems[$product_id]))
            return $this->cartItems[$product_id];
        return null;
    }
}
