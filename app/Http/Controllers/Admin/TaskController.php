<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AggregatorForm;
use App\Models\InternalUser;
use App\Models\Task;
use App\Models\AssignExecutive;
use App\Models\ExecutiveTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{
    public function getTaskForm($id)
    {
        $lead = AggregatorForm::findOrFail($id);

        //Assign - Executive list
        $executive_list = InternalUser::with('role')->whereHas('role', function ($query) {
            $query->whereIn('role_name', ['Accounts', 'PR', 'HR', 'R&D']);
        })->get();

        return view('admin.task.task_create', compact('lead', 'executive_list'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'lead_id'            => 'required|exists:aggregator_forms,id',
            'task_priority'      => 'required|string|max:255',
            'entry_time'         => 'required|date',
            'delivery_needed_by' => 'required',
            'description'        => 'required',
            'attachments'        => 'required|array',
            'attachments.*'      => 'file|mimes:jpeg,png,jpg,webp',
            'vendor_name'        => 'required',
            'vendor_mobile'      => 'required|string|max:15',
            'customer_name'      => 'required',
            'customer_mobile'    => 'required|string|max:15',
            'start_date'         => 'required|date',
            'end_date'           => 'required|date|after_or_equal:start_date',
            'whatsapp_message'   => 'nullable|string',
            'executive_id'       => 'required|exists:internal_users,id'

        ]);

        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        $attachments = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('task/attachments', 'public');
                $attachments[] = $path;
            }
        }
        $task = Task::create([
            'lead_id'            => $request->lead_id,
            'task_priority'      => $request->task_priority,
            'entry_time'         => $request->entry_time,
            'delivery_needed_by' => $request->delivery_needed_by,
            'description'        => $request->description,
            'attachments'        => implode(',', $attachments), // Convert array to comma-separated string
            'vendor_name'        => $request->vendor_name,
            'vendor_mobile'      => $request->vendor_mobile,
            'customer_name'      => $request->customer_name,
            'customer_mobile'    => $request->customer_mobile,
            'start_date'         => $request->start_date,
            'end_date'           => $request->end_date,
            'whatsapp_message'   => $request->whatsapp_message,
            'created_by'         => auth('admin')->id(),
        ]);

        AssignExecutive::create([
            'task_id'     => $task->id,
            'executive_id' => $request->executive_id,
            'status'       => 'Assigned',
        ]);

        return redirect()->back()->with('success', 'Task created successfully!');
    }

    //Task List
    public function index()
    {
        $role = session('role_name');
        $user = Auth::guard('admin')->user();

        if ($user) {
            $userID = $user->id;

            if ($role === 'Admin' || $role === 'Superuser') {
                // Admin and Superuser see all tasks
                $tasks = Task::with([
                    'aggregatorForm',
                    'CreatedBy',
                    'assignExecutive'
                ])->get();
            } elseif (in_array($role, ['Accounts', 'PR', 'HR', 'R&D'])) {
                // Executives only see assigned tasks
                $tasks = Task::with([
                    'aggregatorForm',
                    'CreatedBy',
                    'assignExecutive',
                ])->whereHas('assignExecutive', function ($query) use ($userID) {
                    $query->where('executive_id', $userID);
                })->get();
            } else {
                $tasks = collect(); // If no valid role, return an empty collection
            }

            return view('admin.task.task_list', compact('tasks'));
        } else {
            return redirect()->route('admin.login')->withErrors(['message' => 'Please login first']);
        }
    }

    public function show($task_id)
    {

        $task = Task::with('aggregatorForm', 'CreatedBy', 'assignExecutive.executiveTask')->findOrFail($task_id);

        //Re-assign - Executive list
        $executive_list = InternalUser::with('role')->whereHas('role', function ($query) {
            $query->whereIn('role_name', ['Accounts', 'PR', 'HR', 'R&D']);
        })->get();

        // Fetch the latest assignment for this task_id
        $assign_executive = AssignExecutive::where('task_id', $task_id)
            ->latest('created_at')
            ->first();

         // Fetch the executive name of the latest assigned task_id
         $assignedExecutive = InternalUser::find($assign_executive->executive_id);
         if ($assignedExecutive) {
             $assignedExecutiveName = $assignedExecutive->name;
         }

        return view('admin.task.task_details', compact('task', 'executive_list', 'assignedExecutiveName'));
    }

    public function executiveStoreTask(Request $request)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'assigned_executive_id'  => 'required',
            'remarks'       => 'required',
            'geo_latitude'  => 'required',
            'geo_longitude' => 'required',
            'status'        => 'required'
        ]);

        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        ExecutiveTask::create([
            'assigned_executive_id' => $request->assigned_executive_id,
            'remarks'               => $request->remarks,
            'geo_latitude'          => $request->geo_latitude,
            'geo_longitude'         => $request->geo_longitude,
            'status'                => $request->status
        ]);
        return back()->with('success', 'Executive task accepted successfully');
    }

    public function reassignExecutive(Request $request)
    {
       //dd($request->all());
        $validate = Validator::make($request->all(), [
            'task_id'        => 'required|exists:tasks,id',
            'executive_id'    => 'required|exists:internal_users,id',
        ]);

        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        AssignExecutive::create([
            'executive_id' => $request->executive_id,
            'task_id' => $request->task_id,
            'status' => 'Re-Assigned'
        ]);

        return back()->with('success', 'Task has been successfully re-assigned.');
    }

    public function showLeadTasks($lead_id)
    {

        $lead_tasks = Task::with([
            'aggregatorForm',
            'CreatedBy',
            'assignExecutive',
        ])->where('lead_id', $lead_id)->get();

        return view('admin.task.lead_task_list', compact('lead_tasks'));
    }

}
