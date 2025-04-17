<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderTask;
use App\Models\LeadTask;
use App\Models\JobTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{
    //Executive Lead , Order, Job Task List
    public function index()
    {
        $role = session('role_name');
        $user = Auth::guard('admin')->user();

        if ($user) {
            $userID = $user->id;

            if (in_array($role, ['Accounts', 'PR', 'HR', 'R&D'])) {

                //Get Lead Task
                $leadTasks = LeadTask::with(['aggregatorForm', 'createdBy', 'leadTaskAssign'])
                    ->whereHas('leadTaskAssign', function ($query) use ($userID) {
                        $query->where('internal_user_id', $userID);
                    })
                    ->whereDate('created_at', Carbon::today())
                    ->get()
                    ->map(function ($task) {
                        $task->setAttribute('type', 'lead');
                        return $task;
                    });

                // Get Order Task
                $orderTasks = OrderTask::with(['order', 'createdBy', 'orderTaskAssign'])
                    ->whereHas('orderTaskAssign', function ($query) use ($userID) {
                        $query->where('internal_user_id', $userID);
                    })
                    ->whereDate('created_at', Carbon::today())
                    ->get()
                    ->map(function ($task) {
                        $task->setAttribute('type', 'order');
                        return $task;
                    });

                //Get Job Task
                $jobTasks = JobTask::with(['job', 'createdBy', 'jobTaskAssign'])
                    ->whereHas('jobTaskAssign', function ($query) use ($userID) {
                        $query->where('internal_user_id', $userID);
                    })
                    ->whereDate('created_at', Carbon::today())
                    ->get()
                    ->map(function ($task) {
                        $task->setAttribute('type', 'job');
                        return $task;
                    });
            } else {
                $orderTasks = collect();
                $leadTasks = collect();
                $jobTasks = collect();
            }
            return view('admin.lead_task.task_list', compact('orderTasks', 'leadTasks', 'jobTasks'));
        } else {
            return redirect()->route('admin.login')->withErrors(['message' => 'Please login first']);
        }
    }

    //filter date and status in lead wise tasks
    public function filterTask(Request $request)
    {
        $status = $request->input('status');
        $date = $request->input('date');

        // Validation
        if (!$status || !$date) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['Both status and date are required.']);
        }

        $role = session('role_name');
        $user = Auth::guard('admin')->user();

        if ($user) {
            $userID = $user->id;

            if (in_array($role, ['Accounts', 'PR', 'HR', 'R&D'])) {

                //Get Lead Task
                $LeadTaskquery = LeadTask::with(['aggregatorForm', 'createdBy', 'leadTaskAssign'])
                    ->whereHas('leadTaskAssign', function ($query) use ($userID) {
                        $query->where('internal_user_id', $userID);
                    })->whereDate('created_at', $date);

                if (!empty($status) && $status !== 'All') {
                    $LeadTaskquery->where('status', $status);
                }

                $leadTasks = $LeadTaskquery->get()->map(function ($task) {
                    $task->setAttribute('type', 'lead');
                    return $task;
                });

                // Get Order Task
                $orderTaskquery = OrderTask::with(['order', 'createdBy', 'orderTaskAssign'])
                    ->whereHas('orderTaskAssign', function ($query) use ($userID) {
                        $query->where('internal_user_id', $userID);
                    })->whereDate('created_at', $date);

                if (!empty($status) && $status !== 'All') {
                    $orderTaskquery->where('status', $status);
                }

                $orderTasks = $orderTaskquery->get()->map(function ($task) {
                    $task->setAttribute('type', 'order');
                    return $task;
                });

                //Get Job Task
                $jobTaskquery = JobTask::with(['job', 'createdBy', 'jobTaskAssign'])
                    ->whereHas('jobTaskAssign', function ($query) use ($userID) {
                        $query->where('internal_user_id', $userID);
                    })->whereDate('created_at', $date);

                if (!empty($status) && $status !== 'All') {
                    $jobTaskquery->where('status', $status);
                }

                $jobTasks = $jobTaskquery->get()->map(function ($task) {
                    $task->setAttribute('type', 'job');
                    return $task;
                });
            } else {
                $orderTasks = collect();
                $leadTasks = collect();
                $jobTasks = collect();
            }
            return view('admin.lead_task.task_list', compact('orderTasks', 'leadTasks', 'jobTasks'));
        } else {
            return redirect()->route('admin.login')->withErrors(['message' => 'Please login first']);
        }
    }
}
