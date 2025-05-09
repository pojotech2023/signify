<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\InternalUser;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class UserCreationController extends Controller
{
    public function index()
    {
        $internal_users = InternalUser::with('role')
            ->whereHas('role', function ($query) {
                $query->where('role_name', '!=', 'Admin');
            })->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'response code' => 200,
            'message' => 'User Fetched Successfully',
            'data' => $internal_users
        ]);
    }

    //UserType list except admin
    public function role()
    {
        $roles = Roles::where('role_name', '!=', 'Admin')->get();
        return response()->json([
            'response code' => 200,
            'message' => 'Role execpted Admin Fetched Successfully',
            'data' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|numeric|digits:10',
            'email_id' => 'required|email|unique:internal_users,email_id',
            // 'password' => 'required|confirmed',
            'designation' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $defaultPassword = Hash::make('Password@123');

        $internal_user = InternalUser::create([
            'name' => $request->name,
            'email_id' => $request->email_id,
            'password' => $defaultPassword,
            'mobile_no' => $request->mobile_no,
            'role_id' => $request->role_id,
            'designation' => $request->designation,
        ]);
        return response()->json([
            'response code' => 200,
            'message' => 'User Creation Added Successfully',
            'data' => $internal_user
        ], 200);
    }

    public function update(Request $request, $id)
    {

        $validate = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|numeric|digits:10',
            'email_id' => 'required|email|unique:internal_users,email_id,' . $id,
            // 'password' => 'required|confirmed',
            'designation' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $internal_user = InternalUser::findOrFail($id);

        $internal_user->update([
            'name' => $request->name,
            'email_id' => $request->email_id,
            'mobile_no' => $request->mobile_no,
            'role_id' => $request->role_id,
            'designation' => $request->designation,
        ]);

        return response()->json([
            'response code' => 200,
            'message' => 'User Creation Updated Successfully',
            'data' => $internal_user
        ], 200);
    }


    public function delete($id)
    {

        $internal_user = InternalUser::findOrFail($id);
        $internal_user->delete();

        return response()->json([
            'response code' => 200,
            'message' => 'User Creation Deleted Successfully',
            'data' => $internal_user
        ], 200);
    }

    //All internal user list
    public function internalUserAll()
    {
        $all_internalUser = InternalUser::with('role')->get();
        return response()->json([
            'response code' => 200,
            'message' => 'All Internal User list Fetched Successfully',
            'data' => $all_internalUser->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'role_id' => $user->role_id,
                'role_name' => $user->role->role_name ?? null,
            ])
        ]);
    }

    //Admin and Superuser List
    public function adminSuperuserList()
    {
        $admin_superuser = InternalUser::with('role')->whereHas('role', function ($query) {
            $query->whereIn('role_name', ['Admin', 'SuperUser']);
        })->get();

        return response()->json([
            'response code' => 200,
            'message' => 'Admin and Super User list Fetched Successfully',
            'data' => $admin_superuser->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'role_id' => $user->role_id,
                'role_name' => $user->role->role_name ?? null,
            ])
        ]);
    }
}
