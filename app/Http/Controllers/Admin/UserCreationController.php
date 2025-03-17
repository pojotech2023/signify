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
    public function getUserForm($id = null)
    {
        $internal_user = null;

        if ($id) {
            $internal_user = InternalUser::findOrFail($id);
        }

        $roles = Roles::where('role_name', '!=', 'Admin')->get();

        return view('admin.user_creation.user_creation', compact('roles', 'internal_user'));
    }

    public function index()
    {
        $internal_users = InternalUser::with('role')
            ->whereHas('role', function ($query) {
                $query->where('role_name', '!=', 'Admin');
            })
            ->get();

        return view('admin.user_creation.user_list', compact('internal_users'));
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:10',
            'email_id' => 'required|email|unique:internal_users,email_id',
            // 'password' => 'required|confirmed',
            'designation' => 'required'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $defaultPassword = Hash::make('Password@123');

        $user = InternalUser::create([
            'name' => $request->name,
            'email_id' => $request->email_id,
            'password' => $defaultPassword,
            'mobile_no' => $request->mobile_no,
            'role_id' => $request->role_id,
            'designation' => $request->designation,
        ]);
        return redirect()->back()->with('success', 'User Creation added successfully!');
    }

    public function update(Request $request, $id)
    {

        $validate = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:10',
            'email_id' => 'required|email|unique:internal_users,email_id,' . $id,
            // 'password' => 'required|confirmed',
            'designation' => 'required'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $internal_user = InternalUser::findOrFail($id);

        $internal_user->update([
            'name' => $request->name,
            'email_id' => $request->email_id,
            'mobile_no' => $request->mobile_no,
            'role_id' => $request->role_id,
            'designation' => $request->designation,
        ]);
        return redirect()->back()->with('success', 'User Creation updated successfully!');
    }


    public function delete($id)
    {

        $internal_user = InternalUser::findOrFail($id);
        $internal_user->delete();
        return back()->with('Success', 'Internal User Deleted Successfully!');
    }
}
