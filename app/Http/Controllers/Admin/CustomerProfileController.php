<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
       //dd($request->all());
        $validate = Validator::make($request->all(),[
            'user_id' => 'required|exists:users,id',
            'name'  => 'required|string',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
            'gender' => 'required|string',
            'company_name' => 'required|string',
            'designation' => 'required|string'
        ]);

        if($validate->fails())
        {
            return redirect()->back()->withErrors($validate)->withInput();
        }
        
        $user = User::findOrFail($request->user_id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'company_name' => $request->company_name,
            'designation' => $request->designation
        ]);

        return redirect()->back()->with('success', 'User Profile Updated successfully!');
    }

    public function index(Request $request)
    {
        $query = User::query();
 
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
    
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile_no', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }
    
        $users = $query->orderBy('id', 'desc')->get();
    
        return view('admin.customer.profile_list', compact('users'));

    }
}
