<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roles;
use App\Models\InternalUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserCreationController extends Controller
{
    public function index()
    {
        $roles = Roles::where('role_name', '!=', 'Admin')->get();
        return view('admin.user_creation', compact('roles'));
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:15',
            'email_id' => 'required|email|unique:internal_users,email_id',
            'password' => 'required|confirmed',
        ]);
         
        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }
        $user = InternalUser::create([
            'name' => $request->name,
            'email_id' => $request->email_id,
            'password' => Hash::make($request->password),
            'mobile_no' => $request->mobile_no,
            'role_id' => $request->role_id,
        ]);
        return redirect()->back()->with('success', 'User Creation added successfully!');
    }
}
