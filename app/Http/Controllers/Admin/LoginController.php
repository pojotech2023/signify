<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InternalUser;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'mobile_no' => 'required|numeric|digits:10'
        ]);
    
        $user = InternalUser::where('mobile_no', $request->mobile_no)->first();
    
        if ($user) {
            session(['role_name' => $user->role->role_name]);
            Auth::guard('admin')->login($user);
            return redirect()->route('admin.dashboard');
        }
    
        return back()->withErrors(['mobile_no' => 'Invalid mobile number']);
    }
    
    public function adminLogout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
