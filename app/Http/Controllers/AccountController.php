<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class AccountController
{
    public function login(Request $request){
        $incomingFields = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        return redirect('/account');
    }

    public function register(Request $request) {
        $incomingFields = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        
        $incomingFields['password'] = bcrypt($incomingFields['passord']);
        Customer::create($incomingFields);
    }
}
