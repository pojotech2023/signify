<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AggregatorForm;
use App\Models\InternalUser;
use App\Models\JobTask;
use App\Models\OrderTask;
use App\Models\LeadExecutiveTask;
use App\Models\LeadTask;
use App\Models\LeadTaskAssign;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class LeadTaskController extends Controller
{
    //form view
    public function getTaskForm($id)
    {
        $lead = AggregatorForm::findOrFail($id);

        //Assign - All role Internal User list
        $internal_user_list = InternalUser::all();

        return view('admin.lead_task.task_create', compact('lead', 'internal_user_list'));
    }

    //lead task create
    public function store(Request $request)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'lead_id'            => 'required|exists:aggregator_forms,id',
            'task_name'          => 'required|string',
            'task_priority'      => 'required|string|max:255',
            'completion_expected_by' => 'required',
            'description'        => 'required',
            'attachments'        => 'required|array',
            'attachments.*'      => 'file|mimes:jpeg,png,jpg,webp',
            'vendor_name'        => 'required',
            'vendor_mobile'      => 'required|numeric|digits:10',
            'customer_name'      => 'required',
            'customer_mobile'    => 'required|numeric|digits:10',
            'internal_user_id'   => 'required|exists:internal_users,id'

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
        $task = LeadTask::create([
            'lead_id'            => $request->lead_id,
            'task_name'          => $request->task_name,
            'task_priority'      => $request->task_priority,
            'entry_time'         => now(),
            'completion_expected_by' => $request->completion_expected_by,
            'description'        => $request->description,
            'attachments'        => implode(',', $attachments), // Convert array to comma-separated string
            'vendor_name'        => $request->vendor_name,
            'vendor_mobile'      => $request->vendor_mobile,
            'customer_name'      => $request->customer_name,
            'customer_mobile'    => $request->customer_mobile,
            'created_by'         => auth('admin')->id(),
        ]);

        LeadTaskAssign::create([
            'task_id'     => $task->id,
            'internal_user_id' => $request->internal_user_id,
            'status'       => 'Assigned',
        ]);

        $task->update([
            'status' => 'Assigned',
        ]);

        return  redirect()->route('leads-list')->with('success', 'Lead Task created successfully!');
    }
    //Lead wise tasks
    public function showLeadTasks($lead_id)
    {

        $lead_tasks = LeadTask::where('lead_id', $lead_id)
            ->orderBy('id', 'desc')
            ->get();

            // with([
            //     'aggregatorForm',
            //     'CreatedBy',
            //     'leadTaskAssign',
            // ])->
        return view('admin.lead_task.lead_task_list', compact('lead_tasks'));
    }


    //Task Details
    public function show($task_id)
    {

        $task = LeadTask::with('aggregatorForm', 'CreatedBy', 'leadTaskAssign.leadExecutiveTask')->findOrFail($task_id);

        //Re-assign - All user list
        $internal_user_list = InternalUser::all();

        // Fetch the latest assignment for this task_id
        $assign_executive = LeadTaskAssign::where('task_id', $task_id)
            ->latest('created_at')
            ->first();

        // Fetch the executive name of the latest assigned task_id
        $assignedExecutive = InternalUser::find($assign_executive->internal_user_id);
        if ($assignedExecutive) {
            $assignedExecutiveName = $assignedExecutive->name;
        }

        // Once Assigned User task details see then status change Inprogress
        $loggedInUserId = auth('admin')->id();

        if ($assign_executive && $assign_executive->internal_user_id == $loggedInUserId) {
            if (in_array($task->status, ['Assigned', 'Re-Assigned'])) {
                $task->update([
                    'status' => 'Inprogress'
                ]);

                $assign_executive->update([
                    'status' => 'Inprogress'
                ]);
            }
        }

        return view('admin.lead_task.task_details', compact('task', 'internal_user_list', 'assignedExecutiveName'));
    }


    //Executive Lead , Order, Job Task List
    public function index()
    {
        $role = session('role_name');
        $user = Auth::guard('admin')->user();

        if ($user) {
            $userID = $user->id;

            if (in_array($role, ['Accounts', 'PR', 'HR', 'R&D'])) {
                // Get Order Task
                $orderTasks = OrderTask::with(['order', 'createdBy', 'orderTaskAssign'])
                    ->whereHas('orderTaskAssign', function ($query) use ($userID) {
                        $query->where('internal_user_id', $userID);
                    })->get()->map(function ($task) {
                        $task->setAttribute('type', 'order');
                        return $task;
                    });

                //Get Lead Task
                $leadTasks = LeadTask::with(['aggregatorForm', 'createdBy', 'leadTaskAssign'])
                    ->whereHas('leadTaskAssign', function ($query) use ($userID) {
                        $query->where('internal_user_id', $userID);
                    })->get()->map(function ($task) {
                        $task->setAttribute('type', 'lead');
                        return $task;
                    });

                //Get Job Task
                $jobTasks = JobTask::with(['job', 'createdBy', 'jobTaskAssign'])
                    ->whereHas('jobTaskAssign', function ($query) use ($userID) {
                        $query->where('internal_user_id', $userID);
                    })->get()->map(function ($task) {
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

    //Superuser Task form Update
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'task_name'          => 'nullable|string',
            'task_priority'      => 'nullable|string|max:255',
            'completion_expected_by' => 'nullable|date',
            'description'        => 'nullable|string',
            'attachments.*'      => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048',
            'vendor_name'        => 'nullable|string|max:255',
            'vendor_mobile'      => 'nullable|string|max:15',
            'customer_name'      => 'nullable|string|max:255',
            'customer_mobile'    => 'nullable|string|max:15',
            'internal_user_id'   => 'nullable|exists:internal_users,id',
            'status'             => 'nullable|string'
        ]);

        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        $task = LeadTask::findOrFail($id);

        // Initialize $attachments with existing attachments (if any)
        $attachments = $task->attachments ? explode(',', $task->attachments) : [];

        //Handle new attachments (if any)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('task/attachments', 'public');
                $attachments[] = $path;
            }
        }

        // Update task data
        $task->update([
            'lead_id'               => $request->lead_id,
            'task_name'             => $request->task_name,
            'task_priority'         => $request->task_priority,
            'entry_time'            => now(),
            'completion_expected_by' => $request->completion_expected_by,
            'description'           => $request->description,
            'attachments'           => $attachments ? implode(',', $attachments) : null, // Keep existing if no new files
            'vendor_name'           => $request->vendor_name,
            'vendor_mobile'         => $request->vendor_mobile,
            'customer_name'         => $request->customer_name,
            'customer_mobile'       => $request->customer_mobile,
            'created_by'            => auth('admin')->id(),
        ]);

        //Reassign to new executive (if provided)
        //dd($request->all());
        if ($request->filled('internal_user_id')) {
            LeadTaskAssign::create([
                'task_id'           => $task->id,
                'internal_user_id'  => $request->internal_user_id,
                'status'            => 'Re-Assigned',
            ]);

            // Update task status
            LeadTask::where('id', $task->id)->update(['status' => 'Re-Assigned']);
        }

        if ($request->filled('status')) {
            $task->update([
                'status' => $request->status
            ]);

            LeadTaskAssign::where('task_id', $task->id)
                ->latest('created_at')
                ->first()
                ->update(['status' => $request->status]);
        }

        return redirect()->back()->with('success', 'Task updated successfully');
    }

    //Executive Form add, update, change status

    //Executive Form filled
    public function executiveStoreTask(Request $request)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'task_assigned_user_id'  => 'required|exists:lead_task_assigns,id',
            'remarks'                => 'required',
            'address'                => 'required',
            'end_date_time'          => 'required',
        ]);

        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        LeadExecutiveTask::create([
            'task_assigned_user_id' => $request->task_assigned_user_id,
            'remarks'               => $request->remarks,
            'address'               => $request->address,
            'end_date_time'         => $request->end_date_time,
        ]);

        return redirect()->back()->with('success', 'Executive task accepted successfully');
    }

    //Executive Form Update or change status
    public function executiveUpdateTask(Request $request, $id)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'task_id'                => 'required',
            'task_assigned_user_id'  => 'nullable|exists:lead_task_assigns,id',
            'remarks'                => 'nullable',
            'address'                => 'nullable',
            'end_date_time'          => 'nullable',
        ]);

        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        $executive_task = LeadExecutiveTask::findOrFail($id);

        $executive_task->update([
            'task_assigned_user_id' => $request->task_assigned_user_id,
            'remarks'               => $request->remarks,
            'address'               => $request->address,
            'end_date_time'         => $request->end_date_time,
        ]);

        if ($request->filled('status')) {
            LeadTask::where('id', $request->task_id)->update([
                'status' => $request->status
            ]);

            LeadTaskAssign::where('task_id', $request->task_id)
                ->where('internal_user_id', auth('admin')->id())
                ->update([
                    'status' => $request->status
                ]);
        }

        return back()->with('success', 'Executive task accepted successfully');
    }
}
