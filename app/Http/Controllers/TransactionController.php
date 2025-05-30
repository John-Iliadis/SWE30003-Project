<?php

namespace App\Http\Controllers;

use App\Models\CreditCard;
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
            $customer = Auth::user()->load(['details', 'creditCard']);
            $userDetails = $customer->details;
            $creditCard = $customer->creditCard;
        }

        return view('transaction.payment', compact('userDetails', 'creditCard'));
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
            'expiration_month' => 'required|numeric|min:1|max:12',
            'expiration_year' => 'required|numeric|min:' . date('Y') . '|max:' . (date('Y') + 10),
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
                ]);
                $customerDetailsId = $details->customer_details_id;

                // Update customer with details ID
                $customer->customer_details_id = $customerDetailsId;
                $customer->save();
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
        $creditCard = CreditCard::create([
            'cardholder_name' => $request->cardholder_name,
            'card_number' => $request->card_number,
            'expiration_month' => $request->expiration_month,
            'expiration_year' => $request->expiration_year,
        ]);

        // Create a new order
        $order = Order::create([
            'order_date' => now(),
            'customer_id' => Auth::id(), // Will be null for guest users
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
