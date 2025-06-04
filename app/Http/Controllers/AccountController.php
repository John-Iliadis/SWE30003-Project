<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\CreditCard;
use Illuminate\Http\Request;
use App\Models\CustomerDetails;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Ensure this line is present

class AccountController
{

    public function orderHistory()
    {
        try {
            $user = auth()->user();
            
            // Check if user has customer details
            if (!$user->details) {
                \Log::error('No customer details found for user: ' . $user->id);
                return view('account.history', [
                    'orders' => collect(),
                    'error' => 'Customer profile not found'
                ]);
            }

            // Debug: Log customer details ID
            \Log::info('Fetching orders for customer_details_id: ' . $user->details->customer_details_id);

            // Get orders using customer_details_id directly
            $orders = Order::with(['orderlines.product', 'customerDetails', 'creditCard'])
                ->where('customer_details_id', $user->details->customer_details_id)
                ->latest()
                ->get();

            // Debug: Log number of orders found
            \Log::info('Found ' . $orders->count() . ' orders');

            return view('account.history', [
                'orders' => $orders,
                'selectedOrder' => $orders->first() ?? null
            ]);

        } catch (\Exception $e) {
            \Log::error('Order history error: ' . $e->getMessage());
            return view('account.history', [
                'orders' => collect(),
                'error' => 'Could not load order history. Please try again later.'
            ]);
        }
    }
    
    public function showOrder($orderId)
    {
        $user = auth()->user();
        
        // Get all orders for the customer (for sidebar)
        $orders = Order::with(['orderlines.product', 'customerDetails', 'creditCard'])
            ->where('customer_id', $user->customer->customer_id)
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

    public function logout() {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    }

    public function show()
    {
        // No change needed here if Auth::user() correctly returns your User model instance.
        // If Intelephense still complains, you could add a check or PHPDoc, but it's usually fine.
        $user = Auth::user();
        if ($user) {
            $customer = User::with(['details', 'creditCard'])->find($user->id);
            return view('account.account', compact('customer'));
        }
        return redirect('/login'); // Or handle unauthenticated user appropriately
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user(); 

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }
        // Ensure password is only updated if provided and not empty
        if ($request->filled('password')) {
            $request['password'] = bcrypt($request['password']);
        } else {
            // Remove password from data if not being updated
            unset($request['password']);
        }
        $data = $request->except('password_confirmation');

        try {
            $user->update($data); // Intelephense should now recognize $user as User model

            if ($user->details) {
                $user->details->update($data);
            }

            if ($user->creditCard) {
                $user->creditCard->update($data);
            }
            
            if ($user->customer) {
                $user->customer->update($data);
            }

            return response()->json([
                'success' => true,
                'customer' => $user->load('details', 'creditCard') // And here for load()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update: ' . $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request){
        $incomingFields = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $incomingFields['email'], 'password' => $incomingFields['password']])){ // Changed from auth()->attempt()
            $request->session()->regenerate();
            
            /** @var \App\Models\User $user */
            $user = Auth::user();
            if ($user && $user->is_admin) {
                return redirect()->route('admin.products.index'); // Or 'admin.dashboard' if you prefer
            }
            return redirect('/account');
        }

        return back()->withErrors([
            'login' => '*Invalid email or password.',
        ])->onlyInput('email');
    }

    public function register(Request $request) {
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
        
        // Password is already hashed in User model's mutator if you set it up like that,
        // but explicit hashing here is also fine.
        // $incomingFields['password'] = bcrypt($incomingFields['password']);
        $expire = explode("-", $incomingFields['card_expire']);

        DB::beginTransaction();
        try {
            // Create the User first
            $user = User::create([
                'name' => $incomingFields['name'],
                'email' => $incomingFields['email'],
                'password' => $incomingFields['password'], // User model should handle hashing
            ]);
    
            // Create CustomerDetails and associate with the User
            $details = new CustomerDetails([
                'name' => $incomingFields['name'],
                'email' => $incomingFields['email'], // Or $user->email
                'phone_number' => $incomingFields['phone_number'],
                'address' => $incomingFields['address'],
                'city' => $incomingFields['city'],
                'zip_code' => $incomingFields['post_code'],
                'state' => $incomingFields['state'],
                'country' => $incomingFields['country'],
            ]);
            $user->details()->save($details);
    
            // Create CreditCard and associate with the User
            $card = new CreditCard([
                'cardholder_name' => $incomingFields['card_holder'],
                'card_number' => $incomingFields['card_number'],
                'expiration_month' => $expire[1],
                'expiration_year' => $expire[0],
            ]);
            $user->creditCard()->save($card);

            $user = Customer::create([
                'name' => $incomingFields['name'],
                'email' => $incomingFields['email'],
                'password' => $incomingFields['password'], //Not sure why added a new User model but the customer model is what I'm using
            ]);
            
            DB::commit();
            Auth::login($user); // Changed from auth()->login()
            return redirect('/account')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            DB::rollBack();
            // It's good to log the actual error for debugging
            // Log::error('Registration failed: ' . $e->getMessage());
            return back()->with('error', 'Registration failed. Please try again. Error: '.$e->getMessage()); // Added $e->getMessage() for more info
        }
    }
}
