<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\InternalUser;
use App\Models\Order;
use App\Models\OrderTask;
use App\Models\OrderTaskAssign;
use App\Models\OrderExecutiveTask;
use Carbon\Carbon;

class OrderTaskController extends Controller
{
    public function getOrderTaskForm($id)
    {
        $order = Order::findOrFail($id);

        //Assign - All role Internal User list
        $internal_user_list = InternalUser::all();

        return view('admin.order_task.task_create', compact('order', 'internal_user_list'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'order_id'            => 'required|exists:orders,id',
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
        ],[
            'internal_user_id.required' => 'Please select an assignee.',
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
        $task = OrderTask::create([
            'order_id'            => $request->order_id,
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

        OrderTaskAssign::create([
            'order_task_id'     => $task->id,
            'internal_user_id' => $request->internal_user_id,
            'status'       => 'Assigned',
        ]);

        $task->update([
            'status' => 'Assigned',
        ]);

        return redirect()->back()->with('success', 'Order Task created successfully!');
    }


    //Task Details
    public function show($task_id)
    {

        $task = OrderTask::with('order', 'CreatedBy', 'orderTaskAssign.orderExecutiveTask')->findOrFail($task_id);

        //Re-assign - All user list
        $internal_user_list = InternalUser::all();

        // Fetch the latest assignment for this task_id
        $assign_executive = orderTaskAssign::where('order_task_id', $task_id)
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

        return view('admin.order_task.task_details', compact('task', 'internal_user_list', 'assignedExecutiveName'));
    }

    //Order wise Tasks
    public function showOrderTasks($order_id)
    {

        $order_tasks = OrderTask::where('order_id', $order_id)
            ->whereDate('created_at', Carbon::today())
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.order_task.order_task_list', compact('order_tasks', 'order_id'));
    }

     //filter date and status in lead wise tasks
     public function filterOrderTask(Request $request, $order_id)
     {
         $status = $request->input('status');
         $date = $request->input('date');
 
         // Validation
         if (!$status || !$date) {
             return redirect()->back()
                 ->withInput()
                 ->withErrors(['Both status and date are required.']);
         }

         $query = OrderTask::where('order_id', $order_id)
         ->whereDate('created_at', $date);

         if (!empty($status) && $status !== 'All') {
            $query->where('status', $status);
        }

        $order_tasks = $query->orderBy('id', 'desc')->get();

        return view('admin.order_task.order_task_list', compact('order_tasks', 'order_id'));
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

        $task = OrderTask::findOrFail($id);

        //Initialize $attachments with existing attachments (if any)
        $attachments = $task->attachments ? explode(',', $task->attachments) : [];

        //Handle new attachments (if any)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('task/attachments', 'public');
                $attachments[] = $path;
            }
        }

        //Update task data
        $task->update([
            'order_id'               => $request->order_id,
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
            OrderTaskAssign::create([
                'order_task_id'           => $task->id,
                'internal_user_id'  => $request->internal_user_id,
                'status'            => 'Re-Assigned',
            ]);

            // Update task status
            OrderTask::where('id', $task->id)->update(['status' => 'Re-Assigned']);
        }

        if ($request->filled('status')) {
            $task->update([
                'status' => $request->status
            ]);

            OrderTaskAssign::where('order_task_id', $task->id)
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
            'task_assigned_user_id'  => 'required|exists:order_task_assigns,id',
            'remarks'                => 'required',
            'address'                => 'required',
            'end_date_time'          => 'required',
        ]);

        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        OrderExecutiveTask::create([
            'task_assigned_user_id' => $request->task_assigned_user_id,
            'remarks'               => $request->remarks,
            'address'               => $request->address,
            'end_date_time'         => $request->end_date_time,
        ]);

        return back()->with('success', 'Executive task accepted successfully');
    }

    //Executive Form Update or change status
    public function executiveUpdateTask(Request $request, $id)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'task_id'                => 'required',
            'task_assigned_user_id'  => 'nullable|exists:order_task_assigns,id',
            'remarks'                => 'nullable',
            'address'                => 'nullable',
            'end_date_time'          => 'nullable',
        ]);

        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        $executive_task = OrderExecutiveTask::findOrFail($id);

        $executive_task->update([
            'task_assigned_user_id' => $request->task_assigned_user_id,
            'remarks'               => $request->remarks,
            'address'               => $request->address,
            'end_date_time'         => $request->end_date_time,
        ]);

        if ($request->filled('status')) {
            OrderTask::where('id', $request->task_id)->update([
                'status' => $request->status
            ]);

            OrderTaskAssign::where('order_task_id', $request->task_id)
                ->where('internal_user_id', auth('admin')->id())
                ->update([
                    'status' => $request->status
                ]);
        }

        return back()->with('success', 'Executive task accepted successfully');
    }
}
