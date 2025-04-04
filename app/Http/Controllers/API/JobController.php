<?php

namespace App\Http\Controllers\API;

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

    public function departmentList()
    {
        $roles = Roles::whereIn('role_name', ['PR', 'HR', 'Accounts', 'R&D'])->get();

        return response()->json([
            'response code' => 200,
            'message' => 'Department list Fetched Successfully',
            'data' => $roles->map(fn($role) => [
                'id' => $role->id,
                'role_name' => $role->role_name,
            ])
        ]);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name'      => 'required|string',
            'role_id'   => 'required|exists:roles,id',
            'assign_user_id' => 'required|exists:internal_users,id'
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
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
        return response()->json([
            'response code' => 200,
            'message' => 'Job Added Successfully!.',
            'data' => $job
        ]);
    }

    public function index()
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json([
                'response code' => 401,
                'message' => 'Unauthorized. Please log in.'
            ]);
        }

        $userID = $user->id;
        $role  = $user->role->role_name;

        $query = Job::with(['role', 'jobAssign']);

        if ($role === 'Superuser') {
            $query->whereHas('jobAssign', function ($q) use ($userID) {
                $q->where('internal_user_id', $userID);
            });
        }
        $jobs = $query->orderBy('id', 'desc')->get();

        //Change the response to include only required fields
        $filteredJobs = $jobs->map(function ($job) {
            return [
                'id' => $job->id,
                'name' => $job->name,
                'status' => $job->status,
                'created_at' => $job->created_at,
                'department' => $job->role->role_name
            ];
        });
        return response()->json([
            'response_code' => 200,
            'message' => 'Job Fetched Successfully!.',
            'data' => $filteredJobs
        ]);
    }

    public function show($id)
    {
        $job = Job::with('role')->findOrFail($id);

        // Fetch the latest assignment for this job_id
        $assign_admin_superuser = JobAssign::where('job_id', $id)
            ->latest('created_at')
            ->first();

        // Default values
        $assignEnabled = true;
        $reassignEnabled = false;
        $assignedUserName = 'Not Assigned Yet';

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

        // Once Assigned User lead details see then status change Inprogress
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

        $data = [
            'id' => $job->id,
            'name' => $job->name,
            'status' => $job->status,
            'created_at' => $job->created_at,
            'department' => $job->role->role_name,
            'assignEnabled' => $assignEnabled,
            'reassignEnabled' =>  $reassignEnabled,
            'assignedUserName' => $assignedUserName,
        ];

        return response()->json([
            'response code' => 200,
            'message' => 'Job Details Fetched Successfully!.',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name'      => 'nullable|string',
            'role_id'   => 'nullable|exists:roles,id',
            'reassign_user_id'   => 'nullable|exists:internal_users,id',
            'status'             => 'nullable|string'
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $job = Job::findOrFail($id);

        if ($request->filled('reassign_user_id') && $request->only('reassign_user_id')) {
            JobAssign::create([
                'internal_user_id' => $request->reassign_user_id,
                'job_id' => $job->id,
                'status' => 'Re-Assigned'
            ]);
            $job->update(['status' => 'Re-Assigned']);

            return response()->json([
                'response code' => 200,
                'message' => 'Job Re-assigned successfully',
                'data' => $job->status
            ]);
        }

        if ($request->filled('status') && $request->only('status')) {

            $job->update(['status' => $request->status]);

            JobAssign::where('job_id', $job->id)
                ->latest('created_at')
                ->first()
                ->update(['status' => $request->status]);

            return response()->json([
                'response code' => 200,
                'message' => 'Job Change Status successfully',
                'data' => $job->status
            ]);
        }

        $job->update([
            'name'      => $request->name,
            'role_id'   => $request->role_id
        ]);

        return response()->json([
            'response code' => 200,
            'message' => 'Job Updated Successfully!.',
            'data' => $job
        ]);
    }
}
