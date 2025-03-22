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
            $userID = $user->id;
            $query = Job::with(['role', 'jobAssign']);

            if ($role === 'Superuser') {
                $query->whereHas('jobAssign', function ($q) use ($userID) {
                    $q->where('internal_user_id', $userID);
                });
            }
            $jobs = $query->orderBy('id', 'desc')->get();

            return view('admin.job.jobs_list', compact('jobs'));
        } else {
            return redirect()->route('admin.login')->withErrors(['message' => 'Please login first']);
        }
    }


    public function store(Request $request)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'name'      => 'required|string',
            'role_id'   => 'required|exists:roles,id',
            'assign_user_id' => 'required|exists:internal_users,id'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $job = Job::create([
            'name'      => $request->name,
            'role_id'   => $request->role_id,
            'status' => 'Assigned'
        ]);

        JobAssign::create([
            'internal_user_id' => $request->assign_user_id,
            'job_id' => $job->id,
            'status' => 'Assigned'
        ]);

        return redirect()->back()->with('success', 'Job created successfully!');
    }


    public function update(Request $request, $id)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'name'      => 'required|string',
            'role_id'   => 'required|exists:roles,id',
            'reassign_user_id'   => 'nullable|exists:internal_users,id',
            'status'             => 'nullable|string'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $job = Job::findOrFail($id);

        $job->update([
            'name'      => $request->name,
            'role_id'   => $request->role_id
        ]);

        if ($request->filled('reassign_user_id')) {
            JobAssign::create([
                'internal_user_id' => $request->reassign_user_id,
                'job_id' => $job->id,
                'status' => 'Re-Assigned'
            ]);
            $job->update(['status' => 'Re-Assigned']);
        }

        if ($request->filled('status')) {

            $job->update(['status' => $request->status]);

            JobAssign::where('job_id', $job->id)
                ->latest('created_at')
                ->first()
                ->update(['status' => $request->status]);
        }

        return redirect()->back()->with('success', 'Job updated successfully!');
    }

    //show empty form and job detailed view
    public function getJobForm($id = null) //id as a job id
    {
        $internal_user = null;
        // Default values
        $assignEnabled = true;
        $reassignEnabled = false;
        $assignedUserName = null;


        //job detailed view
        if ($id) {
            $job = Job::with('role')->findOrFail($id);

            // Fetch the latest assignment for this job_id
            $assign_admin_superuser = JobAssign::where('job_id', $id)
                ->latest('created_at')
                ->first();

            // If an assignment exists, update values
            if ($assign_admin_superuser) {
                $assignEnabled = false;
                $reassignEnabled = true;

                // Fetch the name of the assigned user
                $assignedUser = InternalUser::find($assign_admin_superuser->internal_user_id);
                if ($assignedUser) {
                    $assignedUserName = $assignedUser->name;
                }
            }

            // Once Assigned User job details see then status change Inprogress
            $loggedInUserId = auth('admin')->id();

            if ($assign_admin_superuser && $assign_admin_superuser->internal_user_id == $loggedInUserId) {
                if (in_array($job->status, ['Assigned', 'Re-Assigned'])) {
                    $job->update([
                        'status' => 'Inprogress'
                    ]);

                    $assign_admin_superuser->update([
                        'status' => 'Inprogress'
                    ]);
                }
            }
        } else {
            $job = null;
        }
        //department
        $roles = Roles::whereIn('role_name', ['PR', 'HR', 'Accounts', 'R&D'])->get();

        //Admin & Superuser list
        $admin_super_user = InternalUser::with('role')->whereHas('role', function ($query) {
            $query->whereIn('role_name', ['Admin', 'SuperUser']);
        })->get();

        return view('admin.job.job_create', compact('roles', 'job', 'admin_super_user', 'assignEnabled', 'reassignEnabled', 'assignedUserName'));
    }
}
