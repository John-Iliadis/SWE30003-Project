<?php

namespace App\Http\Controllers;

use App\Models\User; // Add this line
use App\Models\CreditCard;
use Illuminate\Http\Request;
use App\Models\CustomerDetails;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AccountController
{
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
            return view('account.show', compact('customer'));
        }
        return redirect('/login'); // Or handle unauthenticated user appropriately
    }

    public function update(Request $request)
    {
        $user = Auth::user(); // Changed from auth()->user()
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
        $data = $request->except('password_confirmation'); // Assuming you might have password confirmation

        try {
            $user->update($data);

            if ($user->details) {
                $user->details->update($data);
            }

            if ($user->creditCard) {
                $user->creditCard->update($data);
            }

            return response()->json([
                'success' => true,
                'customer' => $user->load('details', 'creditCard') // Or 'user' => $user->load(...) if you prefer consistency
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
