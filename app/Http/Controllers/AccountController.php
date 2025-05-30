<?php

namespace App\Http\Controllers;

use App\Models\Customer;
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
        $customer = Auth::user()->load(['details', 'creditCard']);
        return view('account.show', compact('customer'));
    }

    public function update(Request $request)
    {
        $customer = auth()->user();
        $request['password'] = bcrypt($request['password']);
        $data = $request->all();

        try {
            $customer->update($data);

            if ($customer->details) {
                $customer->details->update($data);
            }

            if ($customer->creditCard) {
                $customer->creditCard->update($data);
            }

            return response()->json([
                'success' => true,
                'customer' => $customer->load('details', 'creditCard')
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

        if (auth()->attempt(['email' => $incomingFields['email'], 'password' => $incomingFields['password']])){
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
            'email' => ['required', 'string', 'email', 'max:50', Rule::unique('customers', 'email')],
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
        
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $expire = explode("-", $incomingFields['card_expire']);

        DB::beginTransaction();
        try {
            $details = CustomerDetails::create([
                'name' => $incomingFields['name'],
                'email' => $incomingFields['email'],
                'phone_number' => $incomingFields['phone_number'],
                'address' => $incomingFields['address'],
                'city' => $incomingFields['city'],
                'zip_code' => $incomingFields['post_code'],
                'state' => $incomingFields['state'],
                'country' => $incomingFields['country'],
            ]);
    
            $card = CreditCard::create([
                'cardholder_name' => $incomingFields['card_holder'],
                'card_number' => $incomingFields['card_number'],
                'expiration_month' => $expire[1],
                'expiration_year' => $expire[0],
            ]);
    
            $customer = Customer::create([
                'name' => $incomingFields['name'],
                'email' => $incomingFields['email'],
                'password' => $incomingFields['password'],
                'customer_details_id' => $details->customer_details_id,
                'card_id' => $card->card_id,
            ]);
            DB::commit();
            auth()->login($customer);
            return redirect('/account')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Registration failed: '.$e->getMessage());
        }
    }
}
