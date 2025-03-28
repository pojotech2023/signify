<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeadTask;
use App\Models\LeadTaskAssign;
use App\Models\InternalUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LeadTaskController extends Controller
{
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
            return response()->json(['error' => $validate->errors()], 422);
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
            'created_by'         => auth('api')->id(),
        ]);

        LeadTaskAssign::create([
            'task_id'     => $task->id,
            'internal_user_id' => $request->internal_user_id,
            'status'       => 'Assigned',
        ]);

        $task->update([
            'status' => 'Assigned',
        ]);

        return  response()->json([
            'response code' => 200,
            'data' => $task
        ]);
    }

    //Lead wise tasks
    public function showLeadTasks($lead_id)
    {

        $lead_tasks = LeadTask::where('lead_id', $lead_id)
            ->orderBy('id', 'desc')
            ->get();

        return  response()->json([
            'response code' => 200,
            'data' => $lead_tasks
        ]);
    }

    //Task Details
    public function show($task_id)
    {
        $task = LeadTask::select(
                'lead_tasks.*',
                'internal_users.name as created_by_name',
                'executive.name as assigned_executive_name'
            )
            ->leftJoin('internal_users', 'lead_tasks.created_by', '=', 'internal_users.id')
            ->leftJoin('lead_task_assigns', 'lead_task_assigns.task_id', '=', 'lead_tasks.id')
            ->leftJoin('internal_users as executive', 'lead_task_assigns.internal_user_id', '=', 'executive.id')
            ->where('lead_tasks.id', $task_id)
            ->latest('lead_task_assigns.created_at')
            ->firstOrFail();

        return  response()->json([
            'response code' => 200,
            'data' => $task
        ]);
    }
}
