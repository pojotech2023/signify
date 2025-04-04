<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\InternalUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'mobile_no' => 'required|numeric|digits:10'
        ]);

        if ($validate->fails()) {
            return response()->json([
                $validate->errors()
            ], 422);
        }

        $loggedUser = InternalUser::where('mobile_no', $request->mobile_no)->first();

        if ($loggedUser) {
            $token = $loggedUser->createToken('authToken')->plainTextToken;

            return response()->json([
                'data' => [
                    'response code' => 200,
                    'token' => $token,
                    'user' => $loggedUser,
                    'role_name' => $loggedUser->role->role_name ?? null
                ]
            ]);
        }
        return response()->json([
           'response code' => 401,
            'message' => 'Invalid mobile number'
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return response()->json([
            'response code' => 200,
            'message' => 'User Successfully Loggedout'
        ]);
    }
}
