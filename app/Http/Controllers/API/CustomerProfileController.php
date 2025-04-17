<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CustomerProfileController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('mobile_no', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('id', 'desc')->get();

        return response()->json([
            'response code' => 200,
            'message' => 'Users Fetched Successfully!.',
            'data' => $users
        ]);
    }

    public function update(Request $request)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name'  => 'required|string',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
            'gender' => 'required|string',
            'company_name' => 'required|string',
            'designation' => 'required|string'
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $user = User::findOrFail($request->user_id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'company_name' => $request->company_name,
            'designation' => $request->designation
        ]);

        return response()->json([
            'response code' => 200,
            'message' => 'Customer Profile Updated Successfully!',
            'data' => $user
        ]);
    }

    public function show()
    {
        $userID = auth()->id();

        $user = User::where('id', $userID)->first();

        return response()->json([
            'response code' => 200,
            'message' => 'Customer Profile Fetched Successfully!',
            'data' => $user
        ]);
    }
}
