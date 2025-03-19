<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use App\Models\JobAssign;
use App\Models\Roles;
use App\Models\InternalUser;

class JobController extends Controller
{
    //job list
    public function index()
    {
        $role = session('role_name');
        $user = Auth::guard('admin')->user();

        if ($user) {

            $jobs = Job::with('role', 'jobAssign')->get();

            return view('admin.job.jobs_list', compact('jobs'));
        } else {
            return redirect()->route('admin.login')->withErrors(['message' => 'Please login first']);
        }
    }

    //show form
    public function getJobForm($id = null) //id as a job id
    {
        $internal_user = null;

        if ($id) {
            $job = Job::findOrFail($id);
        } else {
            $job = null;
        }
        //department
        $roles = Roles::whereIn('role_name', ['PR', 'HR', 'Accounts', 'R&D'])->get();

        return view('admin.job.job_create', compact('roles', 'job'));
    }


    public function store(Request $request)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'name'      => 'required|string',
            'role_id'   => 'required|exists:roles,id',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $job = Job::create([
            'name'      => $request->name,
            'role_id'   => $request->role_id
        ]);

        return redirect()->back()->with('success', 'Job created successfully!');
    }

    //job detailed view
    public function show($id)
    {
        $job = Job::with('roles')->get();

        return view('admin.job.job_details', compact('job'));
    }

    public function update(Request $request, $id)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'name'      => 'required|string',
            'role_id'   => 'required|exists:roles,id',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $job = Job::findOrFail($id);
        $job->update([
            'name'      => $request->name,
            'role_id'   => $request->role_id
        ]);
        return redirect()->back()->with('success', 'Job updated successfully!');
    }
}
