<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\InternalUser;
use App\Models\Job;
use App\Models\JobTask;
use App\Models\JobTaskAssign;
use App\Models\JobExecutiveTask;

class JobTaskController extends Controller
{
    public function store(Request $request)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'job_id'            => 'required|exists:jobs,id',
            'task_name'          => 'required|string',
            'task_priority'      => 'required|string|max:255',
            'completion_expected_by' => 'required',
            'description'        => 'required',
            'attachments'        => 'required|array',
            'attachments.*'      => 'file|mimes:jpeg,png,jpg,webp',
            'vendor_name'        => 'required',
            'vendor_mobile'      => 'required|string|max:15',
            'customer_name'      => 'required',
            'customer_mobile'    => 'required|string|max:15',
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
        $task = JobTask::create([
            'job_id'             => $request->job_id,
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

        JobTaskAssign::create([
            'job_task_id'     => $task->id,
            'internal_user_id' => $request->internal_user_id,
            'status'       => 'Assigned',
        ]);

        $task->update([
            'status' => 'Assigned',
        ]);

        return  response()->json([
            'response code' => 200,
            'message' => 'Job Task Created successfully',
            'data' => $task
        ]);
    }

    //Job wise Tasks
    public function showJobTasks($job_id)
    {

        $job_tasks = JobTask::where('job_id', $job_id)
            ->orderBy('id', 'desc')
            ->get();

        return  response()->json([
            'response code' => 200,
            'message' => 'Job Wise Task Fetched successfully',
            'data' => $job_tasks
        ]);
    }

    //Task Details
    public function show($task_id)
    {
        $task = JobTask::select(
            'job_tasks.*',
            'internal_users.name as created_by_name',
            'executive.name as assigned_executive_name',
            'job_executive_tasks.id as job_executive_task_id',
            'job_executive_tasks.task_assigned_user_id',
            'job_executive_tasks.remarks',
            'job_executive_tasks.address',
            'job_executive_tasks.end_date_time'

        )
            ->leftJoin('internal_users', 'job_tasks.created_by', '=', 'internal_users.id')
            ->leftJoin('job_task_assigns', 'job_task_assigns.job_task_id', '=', 'job_tasks.id')
            ->leftJoin('internal_users as executive', 'job_task_assigns.internal_user_id', '=', 'executive.id')
            ->leftJoin('job_executive_tasks', 'job_executive_tasks.task_assigned_user_id', '=', 'job_task_assigns.id')
            ->where('job_tasks.id', $task_id)
            ->latest('job_task_assigns.created_at')
            ->firstOrFail();

        return  response()->json([
            'response code' => 200,
            'message' => 'Job Task Details Fetched successfully',
            'data' => $task
        ]);
    }

    //superuser job task update
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

        $task = JobTask::findOrFail($id);


        //updating only STATUS
        if ($request->filled('status') && $request->only('status')) {
            $task->update(['status' => $request->status]);

            // Update the latest assigned task status
            JobTaskAssign::where('job_task_id', $task->id)
                ->latest('created_at')
                ->first()
                ->update(['status' => $request->status]);

            return response()->json([
                'response code' => 200,
                'message' => 'Job Task status updated successfully',
                'data' => $task
            ]);
        }

        //Reassign to new executive 
        if ($request->filled('internal_user_id') && $request->only('internal_user_id')) {
            JobTaskAssign::create([
                'job_task_id'          => $task->id,
                'internal_user_id' => $request->internal_user_id,
                'status'           => 'Re-Assigned',
            ]);

            $task->update(['status' => 'Re-Assigned']);

            return response()->json([
                'response code' => 200,
                'message' => 'Job Task Re-assigned successfully',
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
            'job_id'               => $request->job_id,
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
            'message' => 'Job Task updated successfully',
            'data' => $task
        ]);
    }

    //Executive Form add, update, change status

    //Executive Form filled
    public function executiveStoreTask(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'task_assigned_user_id'  => 'required|exists:job_task_assigns,id',
            'remarks'                => 'required',
            'address'                => 'required',
            'end_date_time'          => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $executive_task = JobExecutiveTask::create([
            'task_assigned_user_id' => $request->task_assigned_user_id,
            'remarks'               => $request->remarks,
            'address'               => $request->address,
            'end_date_time'         => $request->end_date_time,
        ]);
        return  response()->json([
            'response code' => 200,
            'message' => 'Job Executive task accepted successfully',
            'data' => $executive_task
        ]);
    }

    //Executive Form Update or change status
    public function executiveUpdateTask(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'task_id'                => 'nullable',
            'task_assigned_user_id'  => 'nullable|exists:job_task_assigns,id',
            'remarks'                => 'nullable',
            'address'                => 'nullable',
            'end_date_time'          => 'nullable',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $executive_task = JobExecutiveTask::findOrFail($id);

        if ($request->filled('status') && $request->only('status')) {
            JobTask::where('id', $request->task_id)->update([
                'status' => $request->status
            ]);

            JobTaskAssign::where('job_task_id', $request->task_id)
                ->where('internal_user_id', auth('api')->id())
                ->update([
                    'status' => $request->status
                ]);

            return response()->json([
                'response code' => 200,
                'message' => 'Lead Executive Task status updated successfully',

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
            'message' => 'Lead Executive task updated successfully',
            'data' => $executive_task
        ]);
    }
}
