<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Make sure Auth is imported

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home'; // Default redirect

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        // For admins, we only care about email and password.
        // You might have a separate check or a flag on the login form 
        // if you want to explicitly try to log in as admin.
        // For simplicity here, we assume the same credentials are used.
        return $request->only($this->username(), 'password');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Example: Check if the authenticated user is an admin
        // Assumes your User model (e.g., Customer) has an 'is_admin' attribute or a 'role' attribute
        if ($user->is_admin) { // Or $user->role === 'admin'
            return redirect()->intended('/admin/dashboard'); // Redirect admins to admin dashboard
        }

        return redirect()->intended($this->redirectPath()); // Default redirect for other users
    }

    // If you need to specify which field is used as the username (default is 'email')
    public function username()
    {
        return 'email';
    }
}