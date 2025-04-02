<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeadTask;
use App\Models\LeadTaskAssign;
use App\Models\LeadExecutiveTask;
use App\Models\InternalUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LeadTaskController extends Controller
{
    //Superuser lead task create
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
            'executive.name as assigned_executive_name',
            'lead_executive_tasks.id as lead_executive_task_id',
            'lead_executive_tasks.task_assigned_user_id',
            'lead_executive_tasks.remarks',
            'lead_executive_tasks.address',
            'lead_executive_tasks.end_date_time'

        )
            ->leftJoin('internal_users', 'lead_tasks.created_by', '=', 'internal_users.id')
            ->leftJoin('lead_task_assigns', 'lead_task_assigns.task_id', '=', 'lead_tasks.id')
            ->leftJoin('internal_users as executive', 'lead_task_assigns.internal_user_id', '=', 'executive.id')
            ->leftJoin('lead_executive_tasks', 'lead_executive_tasks.task_assigned_user_id', '=', 'lead_task_assigns.id')
            ->where('lead_tasks.id', $task_id)
            ->latest('lead_task_assigns.created_at')
            ->firstOrFail();

        return  response()->json([
            'response code' => 200,
            'data' => $task
        ]);
    }

    //superuser lead task update
    public function update(Request $request, $id)
    {
        //dd($request->all());
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
            return response()->json(['error' => $validate->erros()], 422);
        }

        $task = LeadTask::findOrFail($id);


        //updating only STATUS
        if ($request->filled('status') && $request->only('status')) {
            $task->update(['status' => $request->status]);

            // Update the latest assigned task status
            LeadTaskAssign::where('task_id', $task->id)
                ->latest('created_at')
                ->first()
                ->update(['status' => $request->status]);

            return response()->json([
                'response code' => 200,
                'message' => 'Task status updated successfully',
                'data' => $task
            ]);
        }

        //Reassign to new executive 
        if ($request->filled('internal_user_id') && $request->only('internal_user_id')) {
            LeadTaskAssign::create([
                'task_id'          => $task->id,
                'internal_user_id' => $request->internal_user_id,
                'status'           => 'Re-Assigned',
            ]);

            $task->update(['status' => 'Re-Assigned']);

            return response()->json([
                'response code' => 200,
                'message' => 'Task reassigned successfully',
                'data' => $task
            ]);
        }

        // Update task data

        // Initialize $attachments with existing attachments (if any)
        $attachments = $task->attachments ? explode(',', $task->attachments) : [];

        //Handle new attachments (if any)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('task/attachments', 'public');
                $attachments[] = $path;
            }
        }

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
        ]);

        return  response()->json([
            'response code' => 200,
            'message' => 'Task  updated successfully',
            'data' => $task
        ]);
    }

    //Executive Form add, update, change status

    //Executive Form filled
    public function executiveStoreTask(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'task_assigned_user_id'  => 'required|exists:lead_task_assigns,id',
            'remarks'                => 'required',
            'address'                => 'required',
            'end_date_time'          => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $executive_task = LeadExecutiveTask::create([
            'task_assigned_user_id' => $request->task_assigned_user_id,
            'remarks'               => $request->remarks,
            'address'               => $request->address,
            'end_date_time'         => $request->end_date_time,
        ]);
        return  response()->json([
            'response code' => 200,
            'message' => 'Executive task accepted successfully',
            'data' => $executive_task
        ]);
    }

    //Executive Form Update or change status
    public function executiveUpdateTask(Request $request, $id)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'task_id'                => 'nullable',
            'task_assigned_user_id'  => 'nullable|exists:lead_task_assigns,id',
            'remarks'                => 'nullable',
            'address'                => 'nullable',
            'end_date_time'          => 'nullable',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $executive_task = LeadExecutiveTask::findOrFail($id);

        if ($request->filled('status') && $request->only('status')) 
        {
            LeadTask::where('id', $request->task_id)->update([
                'status' => $request->status
            ]);

            LeadTaskAssign::where('task_id', $request->task_id)
                ->where('internal_user_id', auth('admin')->id())
                ->update([
                    'status' => $request->status
                ]);

            return response()->json([
                'response code' => 200,
                'message' => 'Task status updated successfully',
                'data' => $executive_task->status
            ]);
        }

        $executive_task = $executive_task->update([
            'task_assigned_user_id' => $request->task_assigned_user_id,
            'remarks'               => $request->remarks,
            'address'               => $request->address,
            'end_date_time'         => $request->end_date_time,
        ]);

        return  response()->json([
            'response code' => 200,
            'message' => 'Executive task updated successfully',
            'data' => $executive_task
        ]);
    }
}
