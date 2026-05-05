<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Called after successful login
    protected function authenticated(Request $request, $user)
    {
        if ($user->hasPaidAccess()) {
            return redirect()->intended('/dashboard');
        }
        // Not fully paid → billing page (but NOT payment)
        return redirect()->route('billing');
    }
}
