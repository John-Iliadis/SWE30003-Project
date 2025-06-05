<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CreditCard;
use Illuminate\Http\Request;
use App\Models\CustomerDetails;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

    public function logout() {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    }

    public function historyPage()
    {
        return view('account.history');
    }

    public function orderHistory()
    {
        try {
            $user = Auth::user();

            // Check if user has customer details
            if (!$user->details) {
                \Illuminate\Support\Facades\Log::error('No customer details found for user: ' . $user->id);
                return view('account.history', [
                    'orders' => collect(),
                    'error' => 'Customer profile not found'
                ]);
            }

            // Debug: Log customer details ID
            \Illuminate\Support\Facades\Log::info('Fetching orders for customer_details_id: ' . $user->details->customer_details_id);

            // Get orders using customer_details_id directly
            $orders = Order::with(['orderlines.product', 'customerDetails', 'creditCard'])
                ->where('customer_details_id', $user->details->customer_details_id)
                ->latest()
                ->get();

            // Debug: Log number of orders found
            \Illuminate\Support\Facades\Log::info('Found ' . $orders->count() . ' orders');

            return view('account.history', [
                'orders' => $orders,
                'selectedOrder' => $orders->first() ?? null
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Order history error: ' . $e->getMessage());
            return view('account.history', [
                'orders' => collect(),
                'error' => 'Could not load order history. Please try again later.'
            ]);
        }
    }

    public function showOrder($orderId)
    {
        $user = Auth::user();

        // Check if user has customer details
        if (!$user->details) {
            abort(404, 'Customer profile not found');
        }

        // Get all orders for the customer (for sidebar)
        $orders = Order::with(['orderlines.product', 'customerDetails', 'creditCard'])
            ->where('customer_details_id', $user->details->customer_details_id) // Changed from customer_id
            ->latest()
            ->get();

        // Get the specific order being requested
        $selectedOrder = $orders->firstWhere('order_id', $orderId);

        if (!$selectedOrder) {
            abort(404);
        }

        if (request()->wantsJson()) {
            return response()->json([
                'html' => view('partials.order_details', [
                    'order' => $selectedOrder,
                    'orders' => $orders // Pass orders for active state
                ])->render()
            ]);
        }

        return view('account.history', compact('orders', 'selectedOrder'));
    }
}
