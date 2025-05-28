<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CreditCard;
use Illuminate\Http\Request;
use App\Models\CustomerDetails;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AccountController
{

    public function login(Request $request){
        $incomingFields = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (auth()->attempt(['email' => $incomingFields['email'], 'password' => $incomingFields['password']])){
            $request->session()->regenerate();
            return view('account');
        }

        return view('account');
    }

    public function register(Request $request) {
        $incomingFields = $request->validate([
            // Personal Information
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:50', Rule::unique('customers', 'email'), Rule::unique('customer_details', 'email')],
            'password' => ['required', 'string', 'min:8', 'max:20'],
            'phone_number' => ['required', 'string', 'min:8', 'max:12'],
            
            // Address Information
            'address' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:20'],
            'post_code' => ['required', 'string', 'max:8'],
            'state' => ['required', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:50'],
            
            // Payment Information
            'card_holder' => ['required', 'string', 'max:50'],
            'card_number' => ['required', 'string', 'min:10', 'max:16', 'regex:/^[0-9\s]{13,19}$/'],
            'card_expire' => ['required', 'date_format:Y-m', 'after_or_equal:' . now()->format('Y-m')],
            'card_cvv' => ['required', 'digits_between:3,4']
        ]);
        
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $expire = explode("-", $incomingFields['card_expire']);

        DB::beginTransaction();

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

        $customer = customer::create([
            'name' => $incomingFields['name'],
            'email' => $incomingFields['email'],
            'password' => $incomingFields['password'],
            'customer_details_id' => $details->customer_details_id,
            'card_id' => $card->card_id,
        ]);

        DB::commit();

        auth()->login($customer);
        return redirect('/account')->with('success', 'Registration successful!');
    }
}
