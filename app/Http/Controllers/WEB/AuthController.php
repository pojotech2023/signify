<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
       
        // Redirect to OTP page
        return redirect()->route('otp_verification');
    }
}
