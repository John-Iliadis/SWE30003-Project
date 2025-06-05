<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CreditCard;
use App\Models\Orderline;
use App\Models\Product;
use App\Models\User;
use App\Models\CustomerDetails;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AccountController
{
    public function register()
    {
        return view('account.register');
    }

    public function account()
    {
        $user = Auth::user();

        if ($user)
        {
            $customer_details = $user->customerDetails();
            $card_details = $user->creditCard();

            return view('account.account', [
                'user_details' => $customer_details,
                'card_details' => $card_details
            ]);
        }

        return view('account.login');
    }

    public function attemptLogin(Request $request)
    {
        $incomingFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $incomingFields['email'], 'password' => $incomingFields['password']]))
        {
            $request->session()->regenerate();
            return $this->account();
        }

        return back()->withErrors([
            'login' => '*Invalid email or password.',
        ])->onlyInput('email');
    }

    public function createAccount(Request $request)
    {
         $incomingFields = $request->validate([
            // Personal Information
            'name' => ['required', 'string', 'max:50'],

            // Ensure email validation checks the 'users' table
            'email' => ['required', 'string', 'email', 'max:50', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'max:20'],
            'phone_number' => ['required', 'string', 'regex:/^[0-9\s]{8,12}$/'],

            // Address Information
            'address' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:20'],
            'post_code' => ['required', 'string', 'max:8'],
            'state' => ['required', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:50'],

            // Payment Information
            'card_holder' => ['required', 'string', 'max:50'],
            'card_number' => ['required', 'string', 'regex:/^[0-9\s]{13,19}$/'],
            'card_expire' => ['required', 'date_format:Y-m', 'after_or_equal:' . now()->format('Y-m')],
            'card_cvv' => ['required', 'regex:/^[0-9\s]{3,4}$/']
        ]);

        $expire = explode("-", $incomingFields['card_expire']);

        try
        {
            DB::beginTransaction();

            // create customer details record
            $customer_details = CustomerDetails::firstOrCreate([
                'email' => $incomingFields['email'],
                'name' => $incomingFields['name'],
                'phone_number' => $incomingFields['phone_number'],
                'address' => $incomingFields['address'],
                'city' => $incomingFields['city'],
                'zip_code' => $incomingFields['post_code'],
                'state' => $incomingFields['state'],
                'country' => $incomingFields['country']
            ]);

            // create credit record
            $credit_card = CreditCard::firstOrCreate([
                'card_number' => $incomingFields['card_number'],
                'cardholder_name' => $incomingFields['card_holder'],
                'expiration_month' => $expire[1],
                'expiration_year' => $expire[0],
            ]);

            // create customer account
            $user = User::create([
                'name' => $incomingFields['name'],
                'email' => $incomingFields['email'],
                'password' => bcrypt($incomingFields['password']),
                'customer_details_id' => $customer_details['customer_details_id'],
                'card_id' => $credit_card['card_id'],
            ]);

            DB::commit();
            Auth::login($user);

            return $this->account();
        }
        catch (\Throwable $e)
        {
            DB::rollBack();
            return back()->with('error', 'Registration failed. Please try again. Error: '.$e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user)
        {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        // Ensure password is only updated if provided and not empty
        if ($request->filled('password'))
        {
            $request['password'] = bcrypt($request['password']);
        }
        else
        {
            // Remove password from data if not being updated
            unset($request['password']);
        }

        $data = $request->except('password_confirmation');

        try
        {
            $user->update($data);
            $user->customerDetails()->update($data);
            $user->creditCard()->update($data);

            return response()->json([
                'success' => true
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update: ' . $e->getMessage()
            ], 500);
        }
    }

    public function orderHistory()
    {
        $user = Auth::user();

        $orders = Order::where('customer_id', $user['id'])->latest()->get();

        $data = [];

        foreach ($orders as $order)
        {
            $total = 0;
            $items = [];

            $orderItems = Orderline::where('order_id', $order['order_id'])->get();

            foreach ($orderItems as $orderItem)
            {
                $product = Product::find($orderItem->product_id);
                $subtotal = $product['price'] * $orderItem['quantity'];
                $total += $subtotal;

                $items[] = [
                    'product' => $product,
                    'subtotal' => $subtotal,
                    'quantity' => $orderItem['quantity']
                ];
            }

            $data[] = [
                'order_id' => $order['order_id'],
                'date' => Carbon::parse($order['order_date'])->format('d-m-Y'),
                'total' => $total,
                'items' => $items
            ];
        }

        return view('account.order_history', ['orders' => $data]);
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    }
}
