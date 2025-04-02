<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\LeadTask;
use App\Models\OrderTask;
use App\Models\JobTask;


class TaskListController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();
        $userID = $user->id;
        $roleId = $user->role_id;

        $role = Roles::where('id', $roleId)->first();
        $role = $role->role_name;

        if ($user) {
            if (in_array($role, ['Accounts', 'PR', 'HR', 'R&D'])) {

                //Fetch the Lead Task
                $leadTasks = LeadTask::whereHas('leadTaskAssign', function ($query) use ($userID) {
                        $query->where('internal_user_id', $userID);
                    })->select('id', 'lead_id', 'task_name', 'task_priority', 'entry_time', 'description', 'vendor_name', 'vendor_mobile', 'status')
                    ->orderBy('id', 'desc')  
                    ->get();
                      
                // Fetch Order Tasks
                $orderTasks = OrderTask::whereHas('orderTaskAssign', function ($query) use ($userID) {
                        $query->where('internal_user_id', $userID);
                    })->select('id', 'order_id', 'task_name', 'task_priority', 'entry_time', 'description', 'vendor_name', 'vendor_mobile', 'status')
                    ->orderBy('id', 'desc')
                    ->get();

                // Fetch Job Tasks
                $jobTasks = JobTask::whereHas('jobTaskAssign', function ($query) use ($userID) {
                        $query->where('internal_user_id', $userID);
                    })->select('id', 'job_id', 'task_name', 'task_priority', 'entry_time', 'description', 'vendor_name', 'vendor_mobile', 'status')
                    ->orderBy('id', 'desc')
                    ->get();
            }
            return response()->json([
                'response_code' => 200,
                'data' => [
                    'orderTasks' => $orderTasks,
                    'leadTasks' => $leadTasks,
                    'jobTasks' => $jobTasks,
                ]
            ]);
        }
        else{
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
