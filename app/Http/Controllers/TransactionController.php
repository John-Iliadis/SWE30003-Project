<?php

namespace App\Http\Controllers;

use App\Core\Cart;
use App\Models\CreditCard;
use App\Models\CustomerDetails;
use App\Models\Order;
use App\Models\Orderline;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display the checkout page.
     *
     * @return View
     */
    public function checkout()
    {
        $userDetails = null;
        $creditCard = null;

        if (Auth::check())
        {
            $user = Auth::user();
            $userDetails = $user->customerDetails();
            $creditCard = $user->creditCard();
        }

        $cart = new Cart();
        $cartItems = $cart->getAllItems();
        $total = $cart->getTotal();

        return view('transaction.checkout', [
            'userDetails' => $userDetails,
            'creditCard' => $creditCard,
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    /**
     * Process the payment and create the order.
     *
     * @param  Request  $request
     * @return View
     */
    public function processPayment(Request $request)
    {
        // Validate the form
        $request->validate([
            // Delivery information validation
            'email' => 'required|email|max:255',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|regex:/^[0-9\s]{8,12}$/',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',

            // Payment information validation
            'cardholder_name' => 'required|string|max:255',
            'card_number' => 'required|string|size:16',
            'card_expire' => 'required|date_format:Y-m|after_or_equal:' . now()->format('Y-m'),
            'cvv' => 'required|numeric|min:100|max:9999',
        ]);

        // Create or update customer details
        $customerDetails = CustomerDetails::updateOrCreate(
            ['email' => $request->email]
            ,
            [
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
                'state' => $request->state,
                'country' => $request->country,
            ]
        );

        // Create a new credit card
        $expire = explode("-", $request->card_expire);
        $creditCard = CreditCard::updateOrCreate(
            ['card_number' => $request->card_number],
            [
                'cardholder_name' => $request->cardholder_name,
                'expiration_month' => $expire[1],
                'expiration_year' => $expire[0]
            ]
        );

        $userID = null;

        if (Auth::check())
        {
            $userID = Auth::user()['id'];
        }

        $order = Order::create([
            'order_date' => now(),
            'customer_id' => $userID,
            'customer_details_id' => $customerDetails['customer_details_id'],
            'card_id' => $creditCard['card_id'],
        ]);

        // Get cart items from session and populate Orderline table
        $cart = new Cart();
        $cartItems = $cart->getAllItems();

        foreach ($cartItems as $cartItem)
        {
            $productId = $cartItem['product']['product_id'];

            Orderline::create([
                'order_id' => $order->order_id,
                'product_id' => $productId,
                'quantity' => $cartItem['quantity']
            ]);

            // Update product stock
            $product = Product::find($productId);
            $product->stock -= $cartItem['quantity'];
            $product->save();
        }

        // Clear the cart
        session()->forget('cart');

        return $this->confirmation($order['order_id']);
    }

    /**
     * Display the order confirmation page.
     *
     * @param int $orderId
     * @return View
     */
    public function confirmation(int $orderId)
    {
        $order = Order::find($orderId);
        $orderlines = Orderline::where('order_id', $orderId)->get();
        $customerDetails = CustomerDetails::find($order->customer_details_id);
        $products = [];

        foreach ($orderlines as $line)
        {
            $product = Product::find($line->product_id);
            $products[] = [
                'product' => $product,
                'quantity' => $line->quantity,
                'total' => $product->price * $line->quantity
            ];
        }

        return view('transaction.confirmation', [
            'order' => $order,
            'products' => $products,
            'total' => collect($products)->sum('total'),
            'customerDetails' => $customerDetails
        ]);
    }
}
