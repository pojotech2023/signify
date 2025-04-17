<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function getLoginForm()
    {
        return view('login');
    }

    public function webLogin(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'mobile_no' => 'required|numeric|digits:10'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        // Check if user already exists
        $existingUser = User::where('mobile_no', $request->mobile_no)->first();

        if ($existingUser) {
            // If user exists, log them in and go to aggregator form
            Auth::login($existingUser);
            return redirect()->route('form');
        } else {
            // If not, create user and go to OTP page
            $newUser = User::create([
                'mobile_no' => $request->mobile_no,
            ]);
            Auth::login($newUser);
            return redirect()->route('otp.page');
        }
    }
}
