<?php

namespace App\Http\Controllers;

use App\Models\CreditCard;
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
        return view('transaction.checkout');
    }

    /**
     * Display the payment form.
     *
     * @return \Illuminate\View\View
     */
    public function payment()
    {
        return view('transaction.payment');
    }

    /**
     * Process the payment and create the order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment(Request $request)
    {
        // Validate the payment form
        $request->validate([
            'cardholder_name' => 'required|string|max:255',
            'card_number' => 'required|string|size:16',
            'expiration_month' => 'required|numeric|min:1|max:12',
            'expiration_year' => 'required|numeric|min:' . date('Y') . '|max:' . (date('Y') + 10),
            'cvv' => 'required|numeric|min:100|max:9999',
        ]);

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
            'customer_id' => Auth::id(),
            'customer_details_id' => null, // This would be set if you have a customer details model
            'card_id' => $creditCard->card_id,
        ]);

        // Get cart items from session and create orderlines
        $cartItems = session('cart', []);
        foreach ($cartItems as $productId => $item) {
            Orderline::create([
                'order_id' => $order->order_id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
            ]);

            // Update product stock
            $product = Product::find($productId);
            $product->stock -= $item['quantity'];
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
        $order = Order::find($orderId);
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
            'total' => collect($products)->sum('total')
        ]);
    }
}
