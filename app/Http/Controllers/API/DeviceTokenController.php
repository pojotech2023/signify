<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Auth;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        //dd($request->all());
        try {
            $validate = Validator::make($request->all(), [
                'token' => 'required|string'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validate->errors()
                ], 422);
            }
            
            $internalUserId = auth('api')->id();
             
            if (!$internalUserId) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // // Check if token already exists
            $existing = DeviceToken::where('internal_user_id', $internalUserId)
            ->where('device_token', $request->token)
            ->first();

            if (!$existing) {
                DeviceToken::firstOrCreate([
                    'internal_user_id' => $internalUserId,
                    'device_token' => $request->token,
                ], [
                    'device_type' => 'mobile',
                ]);
             }
            return response()->json(['message' => 'Token saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
        }
    }
}
