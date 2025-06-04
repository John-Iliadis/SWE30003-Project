<?php

namespace App\Http\Controllers;

use App\Models\CreditCard;
use App\Models\Customer;
use App\Models\CustomerDetails;
use App\Models\Order;
use App\Models\Orderline;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controller;

class TransactionController extends Controller
{
    /**
     * Display the checkout page.
     *
     * @return \Illuminate\View\View
     */
    public function checkout()
    {
        // Get cart items
        $cartItems = session('cart', []);

        // If cart is empty, redirect to cart page
        if (empty($cartItems)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        return view('transaction.checkout');
    }

    /**
     * Display the payment form.
     *
     * @return \Illuminate\View\View
     */
    public function payment()
    {
        // Get user details if authenticated
        $userDetails = null;
        $creditCard = null;

        if (Auth::check()) {
            $user = Auth::user();
            $userDetails = $user->details; // Access the relationship directly
            $creditCard = $user->creditCard; // Access the relationship directly
        }

        // Get cart items and calculate pricing details
        $cartItems = session('cart', []);
        $products = [];
        $subtotal = 0;

        foreach ($cartItems as $productId => $item) {
            $product = Product::find($productId);
            $quantity = isset($item['quantity']) ? $item['quantity'] : $item;
            $itemTotal = $product->price * $quantity;
            $subtotal += $itemTotal;

            $products[] = [
                'product' => $product,
                'quantity' => $quantity,
                'total' => $itemTotal
            ];
        }

        // Total is now just the subtotal (no tax or shipping)
        $total = $subtotal;

        return view('transaction.payment', compact(
            'userDetails',
            'creditCard',
            'products',
            'subtotal',
            'total'
        ));
    }

    /**
     * Process the payment and create the order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
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
        $customerDetailsId = null;

        if (Auth::check()) {
            $customer = Auth::user();

            if ($customer->details) {
                // Update existing details
                $customer->details->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'city' => $request->city,
                    'zip_code' => $request->zip_code,
                    'state' => $request->state,
                    'country' => $request->country,
                ]);
                $customerDetailsId = $customer->details->customer_details_id;
            } else {
                // Create new details for registered user
                $details = CustomerDetails::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'city' => $request->city,
                    'zip_code' => $request->zip_code,
                    'state' => $request->state,
                    'country' => $request->country,
                    'user_id' => Auth::id(), // Set the user_id directly when creating
                ]);
                $customerDetailsId = $details->customer_details_id;
                
                // No need to update the user model
                // Remove these lines:
                // $customer->customer_details_id = $customerDetailsId;
                // $customer->save();
            }
        } else {
            // Create details for guest user
            $details = CustomerDetails::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
                'state' => $request->state,
                'country' => $request->country,
            ]);
            $customerDetailsId = $details->customer_details_id;
        }

        // Create a new credit card
        $expire = explode("-", $request->card_expire);
        $creditCard = CreditCard::create([
            'cardholder_name' => $request->cardholder_name,
            'card_number' => $request->card_number,
            'expiration_month' => $expire[1],
            'expiration_year' => $expire[0],
        ]);

        // Create a new order
        // Check if the authenticated user exists in the customers table
        $customerId = null;
        if (Auth::check()) {
            $customer = Customer::find(Auth::id());
            if ($customer) {
                $customerId = $customer->customer_id;
            }
        }

        $order = Order::create([
            'order_date' => now(),
            'customer_id' => $customerId, // Will be null for guest users or if customer doesn't exist
            'customer_details_id' => $customerDetailsId,
            'card_id' => $creditCard->card_id,
        ]);

        // Get cart items from session and create orderlines
        $cartItems = session('cart', []);
        foreach ($cartItems as $productId => $item) {
            Orderline::create([
                'order_id' => $order->order_id,
                'product_id' => $productId,
                'quantity' => $item,
            ]);

            // Update product stock
            $product = Product::find($productId);
            $product->stock -= $item;
            $product->save();
        }

        // Clear the cart
        session()->forget('cart');

        return redirect()->route('transaction.confirmation', ['order_id' => $order->order_id]);
    }

    /**
     * Display the order confirmation page.
     *
     * @param  int  $orderId
     * @return \Illuminate\View\View
     */
    public function confirmation($orderId)
    {
        $order = Order::with(['customerDetails'])->find($orderId);
        $orderlines = Orderline::where('order_id', $orderId)->get();
        $products = [];

        foreach ($orderlines as $line) {
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
            'customerDetails' => $order->customerDetails
        ]);
    }
}
