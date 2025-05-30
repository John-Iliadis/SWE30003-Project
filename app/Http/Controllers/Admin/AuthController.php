<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminDetail; // Import the AdminDetail model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // For input validation
use Illuminate\Foundation\Auth\RegistersUsers; // Optional: for traits if you want to use them

class AuthController extends \App\Http\Controllers\Admin\AuthController
{
    // Optional: If you want to use built-in registration traits (less flexible for custom guards initially)
    // use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = '/admin/login'; // Or '/admin/dashboard' if you log them in directly

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Allow guests to access registration form/process, except for logout
        // $this->middleware('guest:admin')->except('logout'); 
        // We'll configure middleware more specifically later
    }

    /**
     * Show the admin registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('admin.auth.register'); // We'll create this view next
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admin_details'], // Check uniqueness in admin_details table
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.register')
                        ->withErrors($validator)
                        ->withInput();
        }

        $admin = AdminDetail::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
        ]);

        // Optional: Log the admin in directly after registration
        // auth()->guard('admin')->login($admin);
        // return redirect()->route('admin.dashboard')->with('success', 'Registration successful! Welcome!');

        return redirect()->route('admin.login') // Redirect to admin login page
                         ->with('success', 'Registration successful! Please log in.');
    }

    // We will add login and logout methods later
}
